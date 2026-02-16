<?php

namespace app\model;

use PDO;

class BngrcModel {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getRegions() {
        $stmt = $this->db->query("SELECT * FROM region");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVilles() {
        $stmt = $this->db->query("SELECT * FROM ville");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVillesByRegion($id_region) {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_region = ?");
        $stmt->execute([$id_region]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinistres() {
        $stmt = $this->db->query("SELECT * FROM sinistre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSinistresByVille($id_ville) {
        $stmt = $this->db->prepare("SELECT * FROM sinistre WHERE id_ville = ?");
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinMateriaux() {
        $stmt = $this->db->query("SELECT * FROM besoin_materiaux");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinArgent() {
        $stmt = $this->db->query("SELECT * FROM besoin_argent");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonMateriauxByBesoin($id_besoin) {
        $stmt = $this->db->prepare("SELECT * FROM don_materiaux WHERE id_besoin = ?");
        $stmt->execute([$id_besoin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonArgentByBesoin($id_besoin_argent) {
        $stmt = $this->db->prepare("SELECT * FROM don_argent WHERE id_besoin_argent = ?");
        $stmt->execute([$id_besoin_argent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoriesBesoin() {
        $stmt = $this->db->query("SELECT * FROM categorie_besoin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVilleById($id_ville) {
        $stmt = $this->db->prepare("SELECT * FROM ville WHERE id_ville = ?");
        $stmt->execute([$id_ville]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBesoinsMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin, bm.quantite, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             WHERE s.id_ville = ?
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonsMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin,
                    COALESCE(SUM(dm.quantite_donnee), 0) AS quantite_donnee, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             LEFT JOIN don_materiaux dm ON dm.id_besoin = bm.id_besoin
             WHERE s.id_ville = ?
             GROUP BY bm.id_besoin, cb.nom, bm.nom_besoin, bm.unite
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestantMateriauxByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT bm.id_besoin, cb.nom AS categorie, bm.nom_besoin,
                    (bm.quantite - COALESCE(SUM(dm.quantite_donnee), 0)) AS quantite_restante, bm.unite
             FROM besoin_materiaux bm
             JOIN sinistre s ON s.id_sinistre = bm.id_sinistre
             JOIN categorie_besoin cb ON cb.id_categorie = bm.id_categorie
             LEFT JOIN don_materiaux dm ON dm.id_besoin = bm.id_besoin
             WHERE s.id_ville = ?
             GROUP BY bm.id_besoin, cb.nom, bm.nom_besoin, bm.quantite, bm.unite
             ORDER BY bm.id_besoin"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinsArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent, ba.montant_necessaire
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             WHERE s.id_ville = ?
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDonsArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent,
                    COALESCE(SUM(da.montant_donne), 0) AS montant_donne
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             LEFT JOIN don_argent da ON da.id_besoin_argent = ba.id_besoin_argent
             WHERE s.id_ville = ?
             GROUP BY ba.id_besoin_argent
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRestantArgentByVille($id_ville) {
        $stmt = $this->db->prepare(
            "SELECT ba.id_besoin_argent,
                    (ba.montant_necessaire - COALESCE(SUM(da.montant_donne), 0)) AS montant_restant
             FROM besoin_argent ba
             JOIN sinistre s ON s.id_sinistre = ba.id_sinistre
             LEFT JOIN don_argent da ON da.id_besoin_argent = ba.id_besoin_argent
             WHERE s.id_ville = ?
             GROUP BY ba.id_besoin_argent, ba.montant_necessaire
             ORDER BY ba.id_besoin_argent"
        );
        $stmt->execute([$id_ville]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
