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
}
?>
