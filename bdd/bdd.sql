CREATE DATABASE bngrc;
USE bngrc;

CREATE TABLE region (
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom_region VARCHAR(100) NOT NULL
);

CREATE TABLE ville (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    id_region INT NOT NULL,
    nom_ville VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_region) REFERENCES region(id_region)
    ON DELETE CASCADE
);

CREATE TABLE sinistre (
    id_sinistre INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    FOREIGN KEY (id_ville) REFERENCES ville(id_ville)
    ON DELETE CASCADE
);

CREATE TABLE categorie_besoin (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL
);

CREATE TABLE besoin_materiaux (
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    id_categorie INT NOT NULL,
    nom_besoin VARCHAR(100) NOT NULL,
    quantite DECIMAL(10,2),
    unite VARCHAR(50),
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre)
    ON DELETE CASCADE,
    FOREIGN KEY (id_categorie) REFERENCES categorie_besoin(id_categorie)
    ON DELETE CASCADE
);

CREATE TABLE besoin_argent (
    id_besoin_argent INT AUTO_INCREMENT PRIMARY KEY,
    id_sinistre INT NOT NULL,
    montant_necessaire DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (id_sinistre) REFERENCES sinistre(id_sinistre)
    ON DELETE CASCADE
);

CREATE TABLE don_materiaux (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NOT NULL,
    quantite_donnee DECIMAL(10,2),
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin) REFERENCES besoin_materiaux(id_besoin)
    ON DELETE CASCADE
);

CREATE TABLE don_argent (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin_argent INT NOT NULL,
    montant_donne DECIMAL(15,2),
    date_don TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_besoin_argent) REFERENCES besoin_argent(id_besoin_argent)
    ON DELETE CASCADE
);
