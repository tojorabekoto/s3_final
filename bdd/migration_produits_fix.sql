-- Migration corrective: Finaliser la structure produit
-- Date: 2026-02-17
-- Objectif: Corriger les problèmes de la migration précédente

USE bngrc;

-- 1. Renommer don_stock_materiel_new en don_stock_materiel
DROP TABLE IF EXISTS don_stock_materiel;
RENAME TABLE don_stock_materiel_new TO don_stock_materiel;

CREATE TABLE don_stock_materiel (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    quantite_disponible DECIMAL(10,2) NOT NULL,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
    ON DELETE CASCADE,
    INDEX idx_produit (id_produit),
    UNIQUE KEY unique_produit (id_produit)
);

-- 2. Remplir id_produit dans besoin_materiaux si NULL
UPDATE besoin_materiaux bm
JOIN produit p ON LOWER(TRIM(bm.nom_besoin)) = LOWER(TRIM(p.nom_produit))
    AND bm.id_categorie = p.id_categorie
SET bm.id_produit = p.id_produit
WHERE bm.id_produit IS NULL;

-- 3. Mettre à jour les unités pour correspondre aux produits
UPDATE besoin_materiaux bm
JOIN produit p ON bm.id_produit = p.id_produit
SET bm.unite = p.unite_standard
WHERE bm.id_produit IS NOT NULL;

-- 4. Ajouter des données de test au stock
-- (Supprimer ces lignes si tu as déjà des vraies données)
INSERT INTO don_stock_materiel (id_produit, quantite_disponible) VALUES
(1, 500),  -- Riz 500 kg
(2, 200),  -- Huile 200 L
(10, 30)   -- Couverture 30 unités
ON DUPLICATE KEY UPDATE 
    quantite_disponible = quantite_disponible + VALUES(quantite_disponible);

-- 5. Ajouter des données de test pour don_stock_argent
INSERT INTO don_stock_argent (montant_disponible) VALUES
(5000000),  -- 5M Ar
(3000000)   -- 3M Ar
ON DUPLICATE KEY UPDATE 
    montant_disponible = montant_disponible + VALUES(montant_disponible);

-- Vérification finale
SELECT 'Structure vérifiée' AS status;
SELECT COUNT(*) AS nb_produits FROM produit;
SELECT COUNT(*) AS nb_stock_materiel FROM don_stock_materiel;
SELECT COUNT(*) AS nb_stock_argent FROM don_stock_argent;

-- Afficher le stock actuel
SELECT 
    p.nom_produit,
    cb.nom AS categorie,
    dsm.quantite_disponible,
    p.unite_standard
FROM don_stock_materiel dsm
JOIN produit p ON dsm.id_produit = p.id_produit
JOIN categorie_besoin cb ON p.id_categorie = cb.id_categorie
ORDER BY cb.nom, p.nom_produit;
