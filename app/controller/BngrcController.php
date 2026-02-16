<?php

namespace app\controller;

use Flight;
use app\model\BngrcModel;

class BngrcController
{
    private $model;

    public function __construct()
    {
        // Instanciation du modèle une seule fois par requête
        $this->model = new BngrcModel(Flight::db());
    }

    // ────────────────────────────────────────────────
    // Régions
    // ────────────────────────────────────────────────
    public function getRegions()
    {
        $regions = $this->model->getRegions();
        Flight::render('regions', ['regions' => $regions]);
    }

    // ────────────────────────────────────────────────
    // Villes
    // ────────────────────────────────────────────────
    public function getVilles()
    {
        $villes = $this->model->getVilles();
        Flight::render('villes', ['villes' => $villes]);
    }

    public function getVillesByRegion($id_region)
    {
        $id_region = (int)$id_region;
        $villes = $this->model->getVillesByRegion($id_region);
        Flight::render('villes', [
            'villes' => $villes,
            'id_region' => $id_region
        ]);
    }

    // ────────────────────────────────────────────────
    // Sinistres
    // ────────────────────────────────────────────────
    public function getSinistres()
    {
        $sinistres = $this->model->getSinistres();
        Flight::render('sinistres', ['sinistres' => $sinistres]);
    }

    public function getSinistresByVille($id_ville)
    {
        $id_ville = (int)$id_ville;
        $sinistres = $this->model->getSinistresByVille($id_ville);
        Flight::render('sinistres', [
            'sinistres' => $sinistres,
            'id_ville' => $id_ville
        ]);
    }

    // ────────────────────────────────────────────────
    // Besoins Matériaux
    // ────────────────────────────────────────────────
    public function getBesoinsMateriaux()
    {
        $besoins = $this->model->getBesoinMateriaux();
        Flight::render('besoins_materiaux', ['besoins' => $besoins]);
    }

    public function getDonsMateriaux($id_besoin)
    {
        $id_besoin = (int)$id_besoin;
        $dons = $this->model->getDonMateriauxByBesoin($id_besoin);
        Flight::render('dons_materiaux', [
            'dons' => $dons,
            'id_besoin' => $id_besoin
        ]);
    }

    // ────────────────────────────────────────────────
    // Besoins Argent
    // ────────────────────────────────────────────────
    public function getBesoinsArgent()
    {
        $besoins = $this->model->getBesoinArgent();
        Flight::render('besoins_argent', ['besoins' => $besoins]);
    }

    public function getDonsArgent($id_besoin_argent)
    {
        $id_besoin_argent = (int)$id_besoin_argent;
        $dons = $this->model->getDonArgentByBesoin($id_besoin_argent);
        Flight::render('dons_argent', [
            'dons' => $dons,
            'id_besoin_argent' => $id_besoin_argent
        ]);
    }

    // ────────────────────────────────────────────────
    // Catégories de besoins
    // ────────────────────────────────────────────────
    public function getCategoriesBesoin()
    {
        $categories = $this->model->getCategoriesBesoin();
        Flight::render('categories_besoin', ['categories' => $categories]);
    }

    // ────────────────────────────────────────────────
    // Bonus : page d'accueil / dashboard (exemple)
    // ────────────────────────────────────────────────
    public function dashboard()
    {
        $data = [
            'nb_regions'         => count($this->model->getRegions()),
            'nb_villes'          => count($this->model->getVilles()),
            'nb_sinistres'       => count($this->model->getSinistres()),
            'nb_besoins_mat'     => count($this->model->getBesoinMateriaux()),
            'nb_besoins_argent'  => count($this->model->getBesoinArgent()),
            'categories'         => $this->model->getCategoriesBesoin()
        ];

        Flight::render('accueil', $data);
    }
}