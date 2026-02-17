-- ============================================================
-- Migration v3 : Données initiales + configuration vente
-- ============================================================

-- Table de configuration (pourcentage de réduction pour la vente)
CREATE TABLE IF NOT EXISTS config_systeme (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(255) NOT NULL,
    description VARCHAR(255)
);

INSERT IGNORE INTO config_systeme (cle, valeur, description) VALUES
('pourcentage_reduction_vente', '30', 'Pourcentage de réduction appliqué lors de la vente de dons (ex: 30 = prix réduit de 30%)');

-- Table historique des ventes de dons
CREATE TABLE IF NOT EXISTS vente_don (
    id_vente INT AUTO_INCREMENT PRIMARY KEY,
    id_produit INT NOT NULL,
    quantite_vendue DECIMAL(10,2) NOT NULL,
    prix_unitaire_original DECIMAL(10,2) NOT NULL,
    pourcentage_reduction DECIMAL(5,2) NOT NULL,
    prix_unitaire_vente DECIMAL(10,2) NOT NULL,
    montant_total DECIMAL(15,2) NOT NULL,
    id_sinistre INT DEFAULT NULL,
    date_vente TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produit) REFERENCES produit(id_produit) ON DELETE CASCADE,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre) ON DELETE SET NULL
);

-- ============================================================
-- Snapshot des données initiales (pour le bouton réinitialiser)
-- Ceci est la copie exacte de data.sql + don_stock initial
-- ============================================================
-- Les données initiales sont dans bdd/data_initiale.sql
-- Le reset va : TRUNCATE les tables opérationnelles, puis rejouer data_initiale.sql
