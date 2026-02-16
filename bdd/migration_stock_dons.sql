-- Migration: Ajout des tables de stock global de dons
-- Date: 2026-02-17
-- Objectif: Séparer les dons globaux (non attribués) des attributions spécifiques

USE bngrc;

-- Stock global des dons matériels (non attribués)
CREATE TABLE IF NOT EXISTS don_stock_materiel (
    id_stock INT AUTO_INCREMENT PRIMARY KEY,
    id_categorie INT NOT NULL,
    nom_produit VARCHAR(100) NOT NULL,
    quantite_disponible DECIMAL(10,2) NOT NULL,
    unite VARCHAR(50),
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id_categorie)
    ON DELETE CASCADE
);

-- Stock global des dons en argent (non attribués)
CREATE TABLE IF NOT EXISTS don_stock_argent (
    id_stock_argent INT AUTO_INCREMENT PRIMARY KEY,
    montant_disponible DECIMAL(15,2) NOT NULL,
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Exemples de données pour tester
INSERT INTO don_stock_materiel (id_categorie, nom_produit, quantite_disponible, unite) VALUES
(1, 'Riz', 500, 'kg'),
(1, 'Huile', 200, 'litres'),
(2, 'Tôle', 100, 'pièces'),
(2, 'Clou', 50, 'kg');

INSERT INTO don_stock_argent (montant_disponible) VALUES
(5000000),
(3000000);
