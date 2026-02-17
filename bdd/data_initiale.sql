-- ============================================================
-- DONNÉES INITIALES - Snapshot pour réinitialisation
-- Données fournies par les professeurs (Février 2026)
-- Utilisé par le bouton "Réinitialiser" pour restaurer la BDD.
-- ============================================================

-- Régions
INSERT INTO region (id_region, nom_region) VALUES
(1, 'Atsinanana'),
(2, 'Vatovavy-Fitovinany'),
(3, 'Atsimo-Atsinanana'),
(4, 'Diana'),
(5, 'Menabe');

-- Villes
INSERT INTO ville (id_ville, id_region, nom_ville, image_path) VALUES
(1, 1, 'Toamasina', '/images/toamasina.jpg'),
(2, 2, 'Mananjary', '/images/mananjary.jpg'),
(3, 3, 'Farafangana', '/images/farafangana.jpg'),
(4, 4, 'Nosy Be', '/images/nosy_be.jpg'),
(5, 5, 'Morondava', '/images/morondava.jpg');

-- Sinistres (1 par ville)
INSERT INTO sinistre (id_sinistre, id_ville) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- Catégories de besoin
INSERT INTO categorie_besoin (id_categorie, nom) VALUES
(1, 'Nature'),
(2, 'Materiaux');

-- Produits (table de référence — prix issus du tableau des profs)
INSERT INTO produit (id_produit, nom_produit, id_categorie, unite_standard, prix_unitaire) VALUES
(1, 'Riz', 1, 'kg', 3000.00),
(2, 'Eau', 1, 'L', 1000.00),
(3, 'Huile', 1, 'L', 6000.00),
(4, 'Haricots', 1, 'kg', 4000.00),
(5, 'Tôle', 2, 'm²', 25000.00),
(6, 'Bâche', 2, 'm²', 15000.00),
(7, 'Clous', 2, 'kg', 8000.00),
(8, 'Bois', 2, 'm³', 10000.00),
(9, 'Groupe électrogène', 2, 'unité', 6750000.00);

-- ════════════════════════════════════════
-- Besoins matériaux par sinistre
-- ════════════════════════════════════════

-- Toamasina (sinistre 1)
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(1, 1, 1, 'Riz', 800, 'kg'),
(2, 1, 1, 'Eau', 1500, 'L'),
(3, 1, 2, 'Tôle', 120, 'm²'),
(4, 1, 2, 'Bâche', 200, 'm²'),
(5, 1, 2, 'Groupe électrogène', 3, 'unité');

-- Mananjary (sinistre 2)
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(6, 2, 1, 'Riz', 500, 'kg'),
(7, 2, 1, 'Huile', 120, 'L'),
(8, 2, 2, 'Tôle', 80, 'm²'),
(9, 2, 2, 'Clous', 60, 'kg');

-- Farafangana (sinistre 3)
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(10, 3, 1, 'Riz', 600, 'kg'),
(11, 3, 1, 'Eau', 1000, 'L'),
(12, 3, 2, 'Bâche', 150, 'm²'),
(13, 3, 2, 'Bois', 100, 'm³');

-- Nosy Be (sinistre 4)
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(14, 4, 1, 'Riz', 300, 'kg'),
(15, 4, 1, 'Haricots', 200, 'kg'),
(16, 4, 2, 'Tôle', 40, 'm²'),
(17, 4, 2, 'Clous', 30, 'kg');

-- Morondava (sinistre 5)
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(18, 5, 1, 'Riz', 700, 'kg'),
(19, 5, 1, 'Eau', 1200, 'L'),
(20, 5, 2, 'Bâche', 180, 'm²'),
(21, 5, 2, 'Bois', 150, 'm³');

-- ════════════════════════════════════════
-- Besoins argent par sinistre
-- ════════════════════════════════════════
INSERT INTO besoin_argent (id_besoin_argent, id_sinistre, montant_necessaire) VALUES
(1, 1, 12000000),
(2, 2, 6000000),
(3, 3, 8000000),
(4, 4, 4000000),
(5, 5, 10000000);

-- ════════════════════════════════════════
-- Stock dons matériels (reçus, non attribués)
-- Consolidé par produit (UNIQUE id_produit)
-- ════════════════════════════════════════
INSERT INTO don_stock_materiel (id_stock, id_produit, quantite_disponible, date_don) VALUES
(1, 1, 2400, '2026-02-18 00:00:00'),
(2, 2, 5600, '2026-02-18 00:00:00'),
(3, 4, 188, '2026-02-17 00:00:00'),
(4, 5, 350, '2026-02-18 00:00:00'),
(5, 6, 570, '2026-02-19 00:00:00');

-- ════════════════════════════════════════
-- Stock dons argent (reçus, non attribués)
-- ════════════════════════════════════════
INSERT INTO don_stock_argent (id_stock_argent, montant_disponible, date_don) VALUES
(1, 5000000, '2026-02-16 00:00:00'),
(2, 3000000, '2026-02-16 00:00:00'),
(3, 4000000, '2026-02-17 00:00:00'),
(4, 1500000, '2026-02-17 00:00:00'),
(5, 6000000, '2026-02-17 00:00:00'),
(6, 20000000, '2026-02-19 00:00:00');

-- ════════════════════════════════════════
-- Aucune attribution initiale
-- Les tables don_materiaux, don_argent, inventaire_argent
-- restent vides au départ.
-- ════════════════════════════════════════

-- Config système
INSERT INTO config_systeme (cle, valeur, description) VALUES
('pourcentage_reduction_vente', '30', 'Pourcentage de réduction appliqué lors de la vente de dons');
