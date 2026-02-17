-- ============================================================
-- DONNÉES INITIALES - Snapshot pour réinitialisation
-- Ce fichier contient l'état initial de la base de données
-- tel que fourni par les professeurs.
-- Utilisé par le bouton "Réinitialiser" pour restaurer la BDD.
-- ============================================================

-- Régions
INSERT INTO region (id_region, nom_region) VALUES
(1, 'Analamanga'),
(2, 'Atsinanana'),
(3, 'Boeny'),
(4, 'Haute Matsiatra'),
(5, 'Alaotra-Mangoro'),
(6, 'Sava');

-- Villes
INSERT INTO ville (id_ville, id_region, nom_ville, image_path) VALUES
(1, 1, 'Antananarivo', '/images/antananarivo.jpg'),
(2, 2, 'Toamasina', '/images/toamasina.jpg'),
(3, 3, 'Mahajanga', '/images/mahajanga.jpg'),
(4, 4, 'Fianarantsoa', '/images/fianarantsoa.jpg'),
(5, 5, 'Ambatondrazaka', '/images/ambato.jpg'),
(6, 6, 'Sambava', '/images/sambava1.jpg');

-- Sinistres
INSERT INTO sinistre (id_sinistre, id_ville) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6);

-- Catégories de besoin
INSERT INTO categorie_besoin (id_categorie, nom) VALUES
(1, 'Nature'),
(2, 'Materiaux');

-- Produits (table de référence)
INSERT INTO produit (id_produit, nom_produit, id_categorie, unite_standard, prix_unitaire) VALUES
(1, 'Riz', 1, 'kg', 5000.00),
(2, 'Huile', 1, 'L', 12000.00),
(3, 'Eau potable', 1, 'L', 3000.00),
(4, 'Sucre', 1, 'kg', 10000.00),
(5, 'Sel', 1, 'kg', 3000.00),
(6, 'Farine', 1, 'kg', 8000.00),
(7, 'Haricots', 1, 'kg', 1500.00),
(8, 'Lentilles', 1, 'kg', 20000.00),
(9, 'Conserves', 1, 'unité', 0.00),
(10, 'Tôle', 2, 'm²', 25000.00),
(11, 'Bois', 2, 'm³', 10000.00),
(12, 'Ciment', 2, 'sac', 35000.00),
(13, 'Clous', 2, 'kg', 15000.00),
(14, 'Tente', 2, 'unité', 150000.00),
(15, 'Bâche', 2, 'm²', 15000.00),
(16, 'Couverture', 2, 'unité', 22000.00),
(17, 'Matelas', 2, 'unité', 50000.00),
(18, 'Vêtements', 2, 'unité', 2000.00),
(19, 'Médicaments', 2, 'boîte', 7000.00);

-- Besoins matériaux
INSERT INTO besoin_materiaux (id_besoin, id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(1, 1, 1, 'Riz', 1000, 'kg'),
(2, 1, 1, 'Huile', 500, 'litres'),
(3, 1, 2, 'Tôle', 300, 'pieces'),
(4, 1, 2, 'Clous', 50, 'kg'),
(5, 2, 1, 'Eau potable', 2000, 'litres'),
(6, 2, 2, 'Bois de construction', 150, 'pieces'),
(7, 3, 1, 'Couvertures', 300, 'unites'),
(8, 3, 2, 'Briques', 1000, 'unites'),
(9, 4, 1, 'Farine', 800, 'kg'),
(10, 4, 2, 'Ciment', 200, 'sacs'),
(11, 5, 1, 'Sucre', 400, 'kg'),
(12, 5, 2, 'Tentes', 100, 'unites'),
(13, 6, 1, 'Lait en poudre', 300, 'kg'),
(14, 6, 2, 'Groupes électrogènes', 10, 'unites');

-- Besoins argent
INSERT INTO besoin_argent (id_besoin_argent, id_sinistre, montant_necessaire) VALUES
(1, 1, 10000000),
(2, 2, 25000000),
(3, 3, 5000000),
(4, 4, 8000000),
(5, 5, 12000000),
(6, 6, 20000000);

-- Dons matériaux attribués (initiaux)
INSERT INTO don_materiaux (id_don, id_besoin, quantite_donnee) VALUES
(1, 1, 400),
(2, 3, 100),
(3, 5, 500),
(4, 8, 300),
(5, 10, 50);

-- Dons argent attribués (initiaux)
INSERT INTO don_argent (id_don, id_besoin_argent, montant_donne) VALUES
(1, 1, 3000000),
(2, 2, 10000000),
(3, 3, 2000000),
(4, 5, 5000000);

-- Stock matériel (initial)
INSERT INTO don_stock_materiel (id_stock, id_produit, quantite_disponible) VALUES
(1, 1, 500),
(2, 2, 200),
(3, 10, 30);

-- Stock argent (initial)
INSERT INTO don_stock_argent (id_stock_argent, montant_disponible) VALUES
(1, 50000),
(2, 750000),
(3, 5000000),
(4, 3000000),
(5, 5000000),
(6, 3000000),
(7, 5000000),
(8, 3000000),
(9, 5000000),
(10, 3000000),
(11, 5000000),
(12, 3000000);

-- Inventaire argent par sinistre (initial = somme des don_argent par sinistre)
INSERT INTO inventaire_argent (id_sinistre, montant_disponible) VALUES
(1, 3050000),
(2, 10000000),
(3, 2000000),
(4, 0),
(5, 5000000),
(6, 0);

-- Config système
INSERT INTO config_systeme (cle, valeur, description) VALUES
('pourcentage_reduction_vente', '30', 'Pourcentage de réduction appliqué lors de la vente de dons');
