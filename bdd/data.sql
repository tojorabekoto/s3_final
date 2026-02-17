-- ============================================================
-- DONNÉES - Données des professeurs (Février 2026)
-- ============================================================

INSERT INTO region (nom_region) VALUES
('Atsinanana'),
('Vatovavy-Fitovinany'),
('Atsimo-Atsinanana'),
('Diana'),
('Menabe');

INSERT INTO ville (id_region, nom_ville, image_path) VALUES
(1, 'Toamasina', '/images/toamasina.jpg'),
(2, 'Mananjary', '/images/mananjary.jpg'),
(3, 'Farafangana', '/images/farafangana.jpg'),
(4, 'Nosy Be', '/images/nosy_be.jpg'),
(5, 'Morondava', '/images/morondava.jpg');

INSERT INTO sinistre (id_ville) VALUES
(1),
(2),
(3),
(4),
(5);

INSERT INTO categorie_besoin (nom) VALUES
('Nature'),
('Materiaux');

INSERT INTO produit (nom_produit, id_categorie, unite_standard, prix_unitaire) VALUES
('Riz', 1, 'kg', 3000.00),
('Eau', 1, 'L', 1000.00),
('Huile', 1, 'L', 6000.00),
('Haricots', 1, 'kg', 4000.00),
('Tôle', 2, 'm²', 25000.00),
('Bâche', 2, 'm²', 15000.00),
('Clous', 2, 'kg', 8000.00),
('Bois', 2, 'm³', 10000.00),
('Groupe électrogène', 2, 'unité', 6750000.00);

-- Toamasina (sinistre 1)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(1, 1, 'Riz', 800, 'kg'),
(1, 1, 'Eau', 1500, 'L'),
(1, 2, 'Tôle', 120, 'm²'),
(1, 2, 'Bâche', 200, 'm²'),
(1, 2, 'Groupe électrogène', 3, 'unité');

-- Mananjary (sinistre 2)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(2, 1, 'Riz', 500, 'kg'),
(2, 1, 'Huile', 120, 'L'),
(2, 2, 'Tôle', 80, 'm²'),
(2, 2, 'Clous', 60, 'kg');

-- Farafangana (sinistre 3)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(3, 1, 'Riz', 600, 'kg'),
(3, 1, 'Eau', 1000, 'L'),
(3, 2, 'Bâche', 150, 'm²'),
(3, 2, 'Bois', 100, 'm³');

-- Nosy Be (sinistre 4)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(4, 1, 'Riz', 300, 'kg'),
(4, 1, 'Haricots', 200, 'kg'),
(4, 2, 'Tôle', 40, 'm²'),
(4, 2, 'Clous', 30, 'kg');

-- Morondava (sinistre 5)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(5, 1, 'Riz', 700, 'kg'),
(5, 1, 'Eau', 1200, 'L'),
(5, 2, 'Bâche', 180, 'm²'),
(5, 2, 'Bois', 150, 'm³');

INSERT INTO besoin_argent (id_sinistre, montant_necessaire) VALUES
(1, 12000000),
(2, 6000000),
(3, 8000000),
(4, 4000000),
(5, 10000000);

-- Stock dons matériels (consolidé par produit)
INSERT INTO don_stock_materiel (id_produit, quantite_disponible, date_don) VALUES
(1, 2400, '2026-02-18 00:00:00'),
(2, 5600, '2026-02-18 00:00:00'),
(4, 188, '2026-02-17 00:00:00'),
(5, 350, '2026-02-18 00:00:00'),
(6, 570, '2026-02-19 00:00:00');

-- Stock dons argent
INSERT INTO don_stock_argent (montant_disponible, date_don) VALUES
(5000000, '2026-02-16 00:00:00'),
(3000000, '2026-02-16 00:00:00'),
(4000000, '2026-02-17 00:00:00'),
(1500000, '2026-02-17 00:00:00'),
(6000000, '2026-02-17 00:00:00'),
(20000000, '2026-02-19 00:00:00');

