-- =============================================================================
-- Base de données : Takalo-Takalo (Plateforme d'échange d'objets)
-- Version : 1.0
-- Date : 2026-02-12
-- Description : Structure complète pour gérer les utilisateurs, les objets,
--               et les échanges sur la plateforme Takalo-Takalo
-- =============================================================================

-- Suppression des tables existantes (si elles existent)
DROP TABLE IF EXISTS historique_echange;
DROP TABLE IF EXISTS proposition_echange;
DROP TABLE IF EXISTS objet;
DROP TABLE IF EXISTS utilisateur;

-- =============================================================================
-- Création de la base de données
-- =============================================================================
CREATE DATABASE IF NOT EXISTS takalo_db;
USE takalo_db;

-- =============================================================================
-- TABLE : utilisateur
-- Description : Données des utilisateurs de la plateforme
-- =============================================================================
CREATE TABLE utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    nombre_echange_effectue INT DEFAULT 0,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_derniere_connexion DATETIME,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_statut (statut_utilisateur)
) ENGINE=InnoDB;

-- =============================================================================
-- TABLE : objet
-- Description : Objet à échanger (vêtement, livre, DVD, etc.)
-- =============================================================================
CREATE TABLE objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proprietaire INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    categorie VARCHAR(100) NOT NULL,
    etat ENUM('neuf', 'bon_etat', 'acceptable', 'usé') DEFAULT 'bon_etat',
    image_1 VARCHAR(255),
    image_2 VARCHAR(255),
    image_3 VARCHAR(255),
    prix_estimatif DECIMAL(10,2),
    disponible BOOLEAN DEFAULT TRUE,
    date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_objet_proprietaire
        FOREIGN KEY (id_proprietaire) REFERENCES utilisateur(id) ON DELETE CASCADE,
    INDEX idx_proprietaire (id_proprietaire),
    INDEX idx_categorie (categorie),
    INDEX idx_disponible (disponible)
) ENGINE=InnoDB;

-- =============================================================================
-- TABLE : proposition_echange
-- Description : Proposition d'échange entre deux utilisateurs
-- =============================================================================
CREATE TABLE proposition_echange (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet_propose INT NOT NULL,
    id_utilisateur_propose INT NOT NULL,
    id_objet_demande INT NOT NULL,
    id_utilisateur_demande INT NOT NULL,
    statut_proposition ENUM('en_attente', 'acceptee', 'refusee', 'annulee') DEFAULT 'en_attente',
    message_proposition TEXT,
    date_proposition DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_reponse DATETIME,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_proposition_objet_propose
        FOREIGN KEY (id_objet_propose) REFERENCES objet(id) ON DELETE CASCADE,
    CONSTRAINT fk_proposition_utilisateur_propose
        FOREIGN KEY (id_utilisateur_propose) REFERENCES utilisateur(id) ON DELETE CASCADE,
    CONSTRAINT fk_proposition_objet_demande
        FOREIGN KEY (id_objet_demande) REFERENCES objet(id) ON DELETE CASCADE,
    CONSTRAINT fk_proposition_utilisateur_demande
        FOREIGN KEY (id_utilisateur_demande) REFERENCES utilisateur(id) ON DELETE CASCADE,
    INDEX idx_statut (statut_proposition),
    INDEX idx_utilisateur_propose (id_utilisateur_propose),
    INDEX idx_utilisateur_demande (id_utilisateur_demande)
) ENGINE=InnoDB;

-- =============================================================================
-- TABLE : historique_echange
-- Description : Historique complet des échanges effectués
-- =============================================================================
CREATE TABLE historique_echange (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proposition_echange INT NOT NULL,
    id_objet_1 INT NOT NULL,
    id_proprietaire_1 INT NOT NULL,
    id_objet_2 INT NOT NULL,
    id_proprietaire_2 INT NOT NULL,
    date_echange DATETIME DEFAULT CURRENT_TIMESTAMP,
    lieu_remise VARCHAR(255),
    notes_echange TEXT,
    note_utilisateur_1 INT,
    note_utilisateur_2 INT,
    commentaire_utilisateur_1 TEXT,
    commentaire_utilisateur_2 TEXT,
    CONSTRAINT fk_historique_proposition
        FOREIGN KEY (id_proposition_echange) REFERENCES proposition_echange(id) ON DELETE CASCADE,
    CONSTRAINT fk_historique_objet_1
        FOREIGN KEY (id_objet_1) REFERENCES objet(id),
    CONSTRAINT fk_historique_proprietaire_1
        FOREIGN KEY (id_proprietaire_1) REFERENCES utilisateur(id),
    CONSTRAINT fk_historique_objet_2
        FOREIGN KEY (id_objet_2) REFERENCES objet(id),
    CONSTRAINT fk_historique_proprietaire_2
        FOREIGN KEY (id_proprietaire_2) REFERENCES utilisateur(id),
    INDEX idx_date_echange (date_echange),
    INDEX idx_proprietaire_1 (id_proprietaire_1),
    INDEX idx_proprietaire_2 (id_proprietaire_2)
) ENGINE=InnoDB;

-- =============================================================================
-- INSERTION DE DONNÉES DE TEST
-- =============================================================================

-- Utilisateurs de test
INSERT INTO utilisateur (email, mot_de_passe, nom, prenom, telephone, adresse, ville, code_postal, bio, nombre_echange_effectue, statut_utilisateur) VALUES
('alice@example.com', MD5('password123'), 'Rakoto', 'Alice', '0320000001', '15 Rue de la Paix', 'Antananarivo', '101', 'J\'aime les échanges et les bonnes affaires!', 5, 'actif'),
('bob@example.com', MD5('password123'), 'Randria', 'Bob', '0320000002', '22 Avenue Principale', 'Antsirabe', '110', 'Amoureux de livres et de technologies', 3, 'actif'),
('charlie@example.com', MD5('password123'), 'Rasol', 'Charlie', '0320000003', '8 Boulevard Central', 'Toliara', '601', 'Cherche des vêtements de qualité', 2, 'actif'),
('diane@example.com', MD5('password123'), 'Ratsimbazafy', 'Diane', '0320000004', '45 Route Royale', 'Fianarantsoa', '301', 'Passionnée par le bricolage et la décoration', 7, 'actif'),
('eric@example.com', MD5('password123'), 'Andriamampoinimerina', 'Eric', '0320000005', '12 Rue de l\'Université', 'Mahajanga', '401', 'Fan de jeux vidéo et de cinéma', 4, 'actif');

-- Objets à échanger
INSERT INTO objet (id_proprietaire, titre, description, categorie, etat, image_1, prix_estimatif, disponible) VALUES
(1, 'Robe Été Bleu', 'Robe légère parfaite pour l\'été, marque Zara', 'Vêtement', 'bon_etat', 'images/robe_bleu.jpg', 45000, TRUE),
(1, 'Livre: Le Seigneur des Anneaux', 'Trilogie complète en bon état, édition de poche', 'Livre', 'acceptable', 'images/sda.jpg', 35000, TRUE),
(2, 'Encyclopédie Larousse 2024', 'Édition récente, très instructif', 'Livre', 'neuf', 'images/encyclopedia.jpg', 80000, TRUE),
(2, 'Veste en Denim', 'Veste classique bleu foncé, taille M', 'Vêtement', 'bon_etat', 'images/veste_denim.jpg', 55000, TRUE),
(3, 'DVD: Film Titanic', 'Édition spéciale 2 disques', 'DVD', 'bon_etat', 'images/titanic_dvd.jpg', 25000, TRUE),
(3, 'Baskets Nike Air Max', 'Chaussures de sport en bon état, taille 42', 'Chaussure', 'bon_etat', 'images/nike_airmax.jpg', 120000, TRUE),
(4, 'Table Basse Moderne', 'Table en bois avec plateau verre, très élégante', 'Meuble', 'bon_etat', 'images/table_basse.jpg', 200000, TRUE),
(4, 'Ensemble Décoration Mur', 'Cadres et tableaux assortis (5 pièces)', 'Décoration', 'neuf', 'images/decoration_set.jpg', 65000, TRUE),
(5, 'Console PS4 + 3 jeux', 'Console en bon état avec manettes et 3 jeux', 'Électronique', 'bon_etat', 'images/ps4_console.jpg', 350000, TRUE),
(5, 'Film DVD: Avatar', 'Édition 2 disques en excellent état', 'DVD', 'neuf', 'images/avatar_dvd.jpg', 30000, TRUE);

-- Propositions d'échange
INSERT INTO proposition_echange (id_objet_propose, id_utilisateur_propose, id_objet_demande, id_utilisateur_demande, statut_proposition, message_proposition) VALUES
(1, 1, 4, 2, 'acceptee', 'Bonjour, j\'aimerais échanger ma robe contre votre veste en denim. Elle serait parfaite pour moi!'),
(3, 2, 2, 1, 'en_attente', 'Pourriez-vous être intéressée par mon encyclopédie pour vos livres?'),
(5, 3, 6, 3, 'refusee', 'Échange proposé: DVD contre baskets'),
(7, 4, 9, 5, 'acceptee', 'J\'adore votre console! Je vous propose ma table basse en échange.'),
(8, 4, 10, 5, 'en_attente', 'Intéressée par votre collection de DVD. Mes cadres décoration vous plairaient-ils?');

-- Historique d'échanges
INSERT INTO historique_echange (id_proposition_echange, id_objet_1, id_proprietaire_1, id_objet_2, id_proprietaire_2, date_echange, lieu_remise, note_utilisateur_1, note_utilisateur_2, commentaire_utilisateur_1, commentaire_utilisateur_2) VALUES
(1, 1, 1, 4, 2, '2026-01-15 14:30:00', 'Centre Commercial Andohalo', 5, 5, 'Très bon échange! La veste est parfaite', 'Robe très agréable, excellente transaction'),
(4, 7, 4, 9, 5, '2026-02-05 16:00:00', 'Quartier Antaninarenina', 5, 4, 'Console en parfait état, merci!', 'Table impeccable, très satisfait');

-- =============================================================================
-- AFFICHAGE DES STATISTIQUES
-- =============================================================================
SELECT '====== STATISTIQUES DE LA BASE DE DONNÉES ======' AS info;
SELECT COUNT(*) AS nombre_utilisateurs FROM utilisateur;
SELECT COUNT(*) AS nombre_objets_disponibles FROM objet WHERE disponible = TRUE;
SELECT COUNT(*) AS nombre_propositions_en_attente FROM proposition_echange WHERE statut_proposition = 'en_attente';
SELECT COUNT(*) AS nombre_echanges_effectues FROM historique_echange;
