-- Migration: Création de la table produit et normalisation des stocks
-- Date: 2026-02-17
-- Objectif: Regrouper les produits identiques et unifier les unités

USE bngrc;

-- 1. Créer la table produit (référentiel)
CREATE TABLE IF NOT EXISTS produit (
    id_produit INT AUTO_INCREMENT PRIMARY KEY,
    nom_produit VARCHAR(100) NOT NULL UNIQUE,
    id_categorie INT NOT NULL,
    unite_standard VARCHAR(50) NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id_categorie)
    ON DELETE CASCADE,
    INDEX idx_categorie (id_categorie)
);

-- 2. Insérer les produits de référence courants
INSERT INTO produit (nom_produit, id_categorie, unite_standard) VALUES
-- Produits naturels (id_categorie = 1)
('Riz', 1, 'kg'),
('Huile', 1, 'L'),
('Eau potable', 1, 'L'),
('Sucre', 1, 'kg'),
('Sel', 1, 'kg'),
('Farine', 1, 'kg'),
('Haricots', 1, 'kg'),
('Lentilles', 1, 'kg'),
('Conserves', 1, 'unité'),

-- Matériaux (id_categorie = 2)
('Tôle', 2, 'm²'),
('Bois', 2, 'm³'),
('Ciment', 2, 'sac'),
('Clous', 2, 'kg'),
('Tente', 2, 'unité'),
('Bâche', 2, 'm²'),
('Couverture', 2, 'unité'),
('Matelas', 2, 'unité'),
('Vêtements', 2, 'unité'),
('Médicaments', 2, 'boîte')
ON DUPLICATE KEY UPDATE nom_produit = nom_produit;

-- 3. Créer la nouvelle table don_stock_materiel avec id_produit
DROP TABLE IF EXISTS don_stock_materiel;
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

-- 4. Insérer des données de test (si nécessaire)
INSERT INTO don_stock_materiel (id_produit, quantite_disponible) VALUES
(1, 500),  -- Riz 500 kg
(2, 200),  -- Huile 200 L
(10, 30)   -- Couverture 30 unités
ON DUPLICATE KEY UPDATE 
    quantite_disponible = quantite_disponible + VALUES(quantite_disponible);

-- 6. Mettre à jour besoin_materiaux pour utiliser id_produit
-- Ajouter la colonne id_produit
ALTER TABLE besoin_materiaux 
ADD COLUMN id_produit INT AFTER id_categorie,
ADD FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE;

-- 5. Mettre à jour besoin_materiaux pour utiliser id_produit
-- Ajouter la colonne id_produit si elle n'existe pas
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = 'bngrc'
     AND TABLE_NAME = 'besoin_materiaux'
     AND COLUMN_NAME = 'id_produit') > 0,
    'SELECT 1',
    'ALTER TABLE besoin_materiaux ADD COLUMN id_produit INT AFTER id_categorie, ADD FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE'
));
WHERE bm.id_produit IS NOT NULL;

-- 6. Créer don_stock_argent si elle n'existe pas
CREATE TABLE IF NOT EXISTS don_stock_argent (
    id_stock_argent INT AUTO_INCREMENT PRIMARY KEY,
    montant_disponible DECIMAL(15,2) NOT NULL,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Ajouter des données de test
INSERT INTO don_stock_argent (montant_disponible) VALUES
(5000000),  -- 5M Ar
(3000000)   -- 3M Ar
ON DUPLICATE KEY UPDATE 
    montant_disponible = montant_disponible + VALUES(montant_disponible);

-- 7. Vérification finaler les unités pour correspondre aux produits
UPDATE besoin_materiaux bm
JOIN produit p ON bm.id_produit = p.id_produit
SET bm.unite = p.unite_standard;

-- 7. Agréger les dons existants par produit (pas par besoin individuel)
-- Note: On garde don_materiaux lié aux besoins individuels pour la traçabilité

-- Vérification: Afficher le nouveau stock agrégé
SELECT 
    p.nom_produit,
    cb.nom as categorie,
    dsm.quantite_disponible,
    p.unite_standard
FROM don_stock_materiel dsm
JOIN produit p ON dsm.id_produit = p.id_produit
JOIN categorie_besoin cb ON p.id_categorie = cb.id_categorie
ORDER BY cb.nom, p.nom_produit;
