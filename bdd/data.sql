INSERT INTO region (nom_region) VALUES
('Analamanga'),
('Atsinanana'),
('Boeny'),
('Haute Matsiatra'),
('Alaotra-Mangoro'),
('Sava');

INSERT INTO ville (id_region, nom_ville, image_path) VALUES
(1, 'Antananarivo', '/images/villes/antananarivo.jpg'),
(2, 'Toamasina', '/images/villes/toamasina.jpg'),
(3, 'Mahajanga', '/images/villes/mahajanga.jpg'),
(4, 'Fianarantsoa', '/images/villes/fianarantsoa.jpg'),
(5, 'Ambatondrazaka', '/images/villes/ambatondrazaka.jpg'),
(6, 'Sambava', '/images/villes/sambava.jpg');

INSERT INTO sinistre (id_ville) VALUES
(1),
(2),
(3),
(4),
(5),
(6);

INSERT INTO categorie_besoin (nom) VALUES
('Nature'),
('Materiaux');

-- Sinistre 1 (Antananarivo)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(1, 1, 'Riz', 1000, 'kg'),
(1, 1, 'Huile', 500, 'litres'),
(1, 2, 'Tôle', 300, 'pieces'),
(1, 2, 'Clous', 50, 'kg');

-- Sinistre 2 (Toamasina)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(2, 1, 'Eau potable', 2000, 'litres'),
(2, 2, 'Bois de construction', 150, 'pieces');

-- Sinistre 3 (Mahajanga)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(3, 1, 'Couvertures', 300, 'unites'),
(3, 2, 'Briques', 1000, 'unites');

-- Sinistre 4 (Fianarantsoa)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(4, 1, 'Farine', 800, 'kg'),
(4, 2, 'Ciment', 200, 'sacs');

-- Sinistre 5 (Ambatondrazaka)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(5, 1, 'Sucre', 400, 'kg'),
(5, 2, 'Tentes', 100, 'unites');

-- Sinistre 6 (Sambava)
INSERT INTO besoin_materiaux (id_sinistre, id_categorie, nom_besoin, quantite, unite) VALUES
(6, 1, 'Lait en poudre', 300, 'kg'),
(6, 2, 'Groupes électrogènes', 10, 'unites');


INSERT INTO besoin_argent (id_sinistre, montant_necessaire) VALUES
(1, 10000000),
(2, 25000000),
(3, 5000000),
(4, 8000000),
(5, 12000000),
(6, 20000000);

INSERT INTO don_materiaux (id_besoin, quantite_donnee) VALUES
(1, 400),
(3, 100),
(5, 500),
(8, 300),
(10, 50);

INSERT INTO don_argent (id_besoin_argent, montant_donne) VALUES
(1, 3000000),
(2, 10000000),
(3, 2000000),
(5, 5000000);

