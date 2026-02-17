-- ============================================================================
-- Migration tojo-v2 : Intégration inventaire, achats, prix unitaires
-- ============================================================================

-- 1. Ajouter prix_unitaire au produit (prix par défaut pour chaque produit)
ALTER TABLE produit ADD COLUMN prix_unitaire DECIMAL(10,2) DEFAULT 0 AFTER unite_standard;

-- Remplir les prix unitaires par défaut
UPDATE produit SET prix_unitaire = 5000  WHERE nom_produit = 'Riz';
UPDATE produit SET prix_unitaire = 12000 WHERE nom_produit = 'Huile';
UPDATE produit SET prix_unitaire = 8000  WHERE nom_produit = 'Farine';
UPDATE produit SET prix_unitaire = 10000 WHERE nom_produit = 'Sucre';
UPDATE produit SET prix_unitaire = 3000  WHERE nom_produit = 'Eau potable';
UPDATE produit SET prix_unitaire = 25000 WHERE nom_produit = 'Lait en poudre';
UPDATE produit SET prix_unitaire = 4000  WHERE nom_produit = 'Haricot sec';
UPDATE produit SET prix_unitaire = 3000  WHERE nom_produit = 'Sel';
UPDATE produit SET prix_unitaire = 6000  WHERE nom_produit = 'Savon';
UPDATE produit SET prix_unitaire = 25000 WHERE nom_produit = 'Tôle';
UPDATE produit SET prix_unitaire = 35000 WHERE nom_produit = 'Ciment';
UPDATE produit SET prix_unitaire = 15000 WHERE nom_produit = 'Clous';
UPDATE produit SET prix_unitaire = 40000 WHERE nom_produit = 'Bois de construction';
UPDATE produit SET prix_unitaire = 8000  WHERE nom_produit = 'Briques';
UPDATE produit SET prix_unitaire = 50000 WHERE nom_produit = 'Tentes';
UPDATE produit SET prix_unitaire = 20000 WHERE nom_produit = 'Couvertures';
UPDATE produit SET prix_unitaire = 15000 WHERE nom_produit = 'Bâche';
UPDATE produit SET prix_unitaire = 10000 WHERE nom_produit = 'Corde';
UPDATE produit SET prix_unitaire = 800000 WHERE nom_produit = 'Groupes électrogènes';

-- 2. Ajouter prix_unitaire à besoin_materiaux
ALTER TABLE besoin_materiaux ADD COLUMN prix_unitaire DECIMAL(10,2) DEFAULT NULL AFTER unite;

-- 3. Inventaire argent par sinistre (les dons argent reçus sont regroupés par sinistre)
CREATE TABLE IF NOT EXISTS inventaire_argent (
    id_inventaire_argent INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    montant_disponible DECIMAL(15,2) DEFAULT 0,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    UNIQUE KEY unique_sinistre (id_sinistre)
);

-- 4. Inventaire matériaux par sinistre (dons matériaux reçus par sinistre)
CREATE TABLE IF NOT EXISTS inventaire_materiaux (
    id_inventaire INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_disponible DECIMAL(10,2) DEFAULT 0,
    date_reception TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES besoin_materiaux(id_besoin) ON DELETE CASCADE
);

-- 5. Achat matériaux (acheter avec l'argent de l'inventaire)
CREATE TABLE IF NOT EXISTS achat_materiaux (
    id_achat INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_achetee DECIMAL(10,2) NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    prix_total DECIMAL(15,2) NOT NULL,
    date_achat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES besoin_materiaux(id_besoin) ON DELETE CASCADE
);

-- 6. Historique dons matériaux
CREATE TABLE IF NOT EXISTS historique_dons_materiaux (
    id_historique INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_ville INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_donnee DECIMAL(10,2) NOT NULL,
    unite VARCHAR(50),
    descriptif TEXT,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    FOREIGN KEY (id_ville) REFERENCES ville(id_ville) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES besoin_materiaux(id_besoin) ON DELETE CASCADE,
    INDEX idx_sinistre (id_sinistre),
    INDEX idx_ville (id_ville)
);

-- 7. Historique dons argent
CREATE TABLE IF NOT EXISTS historique_dons_argent (
    id_historique INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_ville INT NOT NULL,
    montant_donne DECIMAL(15,2) NOT NULL,
    descriptif TEXT,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    FOREIGN KEY (id_ville) REFERENCES ville(id_ville) ON DELETE CASCADE,
    INDEX idx_sinistre (id_sinistre),
    INDEX idx_ville (id_ville)
);

-- 8. Historique argent utilisé pour achats
CREATE TABLE IF NOT EXISTS historique_used_argent (
    id_historique INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_besoin INT NOT NULL,
    montant_utilise DECIMAL(15,2) NOT NULL,
    quantite_achetee DECIMAL(10,2),
    descriptif TEXT,
    date_utilisation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE CASCADE,
    FOREIGN KEY (id_besoin) REFERENCES besoin_materiaux(id_besoin) ON DELETE CASCADE,
    INDEX idx_sinistre (id_sinistre)
);

-- 9. Initialiser les inventaires argent pour les sinistres existants
-- (à partir des dons argent déjà enregistrés)
INSERT IGNORE INTO inventaire_argent (id_sinistre, montant_disponible)
SELECT s.id_sinistre, COALESCE(
    (SELECT SUM(da.montant_donne) 
     FROM don_argent da 
     JOIN besoin_argent ba ON da.id_besoin_argent = ba.id_besoin_argent 
     WHERE ba.id_sinistre = s.id_sinistre), 0
) AS total
FROM sinistre s;