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

    public function villeDetail($id_ville)
    {
        $id_ville = (int)$id_ville;
        $ville = $this->model->getVilleById($id_ville);

        $data = [
            'ville' => $ville,
            'besoins_materiaux' => $this->model->getBesoinsMateriauxByVille($id_ville),
            'dons_materiaux' => $this->model->getDonsMateriauxByVille($id_ville),
            'restant_materiaux' => $this->model->getRestantMateriauxByVille($id_ville),
            'besoins_argent' => $this->model->getBesoinsArgentByVille($id_ville),
            'dons_argent' => $this->model->getDonsArgentByVille($id_ville),
            'restant_argent' => $this->model->getRestantArgentByVille($id_ville)
        ];

        Flight::render('ville_detail', $data);
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
    // Insertion de don
    // ────────────────────────────────────────────────
    public function showInsertionDon()
    {
        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'besoins_materiaux' => $this->model->getBesoinsMateriauxForForm(),
            'besoins_argent' => $this->model->getBesoinsArgentForForm(),
            'message' => null,
            'error' => null
        ];

        Flight::render('insertion_don', $data);
    }

    public function submitInsertionDon()
    {
        $error = null;
        $message = null;
        $successCount = 0;

        // Récupérer les dons en JSON
        $donsJson = $_POST['dons_json'] ?? '[]';
        $dons = json_decode($donsJson, true);

        if (!is_array($dons) || empty($dons)) {
            $error = 'Aucun don à enregistrer.';
        } else {
            foreach ($dons as $don) {
                $type = $don['type'] ?? '';
                $quantite = isset($don['quantite']) ? (float)$don['quantite'] : 0;

                if ($quantite <= 0) {
                    continue;
                }

                if ($type === 'naturels' || $type === 'materiaux') {
                    $id_besoin = (int)($don['besoin']['id_besoin'] ?? 0);
                    if ($id_besoin > 0) {
                        $this->model->insertDonMateriaux($id_besoin, $quantite);
                        $successCount++;
                    }
                } elseif ($type === 'argent') {
                    $id_besoin_argent = (int)($don['besoin']['id_besoin_argent'] ?? 0);
                    if ($id_besoin_argent > 0) {
                        $this->model->insertDonArgent($id_besoin_argent, $quantite);
                        $successCount++;
                    }
                }
            }

            if ($successCount > 0) {
                $message = "$successCount don(s) enregistre(s) avec succes.";
            } else {
                $error = 'Erreur lors de l\'enregistrement des dons.';
            }
        }

        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'besoins_materiaux' => $this->model->getBesoinsMateriauxForForm(),
            'besoins_argent' => $this->model->getBesoinsArgentForForm(),
            'message' => $message,
            'error' => $error
        ];

        Flight::render('insertion_don', $data);
    }

    // ────────────────────────────────────────────────
    // Attribution de don
    // ────────────────────────────────────────────────
    public function showAttribution()
    {
        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'besoins_materiaux' => $this->model->getBesoinsMateriauxForForm(),
            'besoins_argent' => $this->model->getBesoinsArgentForForm(),
            'restant_materiaux' => [],
            'restant_argent' => [],
            'message' => null,
            'error' => null
        ];

        Flight::render('attribution', $data);
    }

    public function submitAttribution()
    {
        $type = $_POST['type_don'] ?? '';
        $quantite = isset($_POST['quantite']) ? (float)$_POST['quantite'] : 0;
        $error = null;
        $message = null;

        if ($quantite <= 0) {
            $error = 'La quantite doit etre superieure a 0.';
        } elseif ($type === 'naturels' || $type === 'materiaux') {
            $id_besoin = (int)($_POST['id_besoin'] ?? 0);
            if ($id_besoin <= 0) {
                $error = 'Veuillez selectionner un besoin materiel.';
            } else {
                $restant = $this->model->getRestantMateriauxByBesoin($id_besoin);
                if ($quantite > $restant) {
                    $error = 'Quantite superieure au restant disponible.';
                } else {
                    $this->model->insertDonMateriaux($id_besoin, $quantite);
                    $typeLabel = $type === 'naturels' ? 'naturelle' : 'materielle';
                    $message = "Attribution $typeLabel enregistree avec succes.";
                }
            }
        } elseif ($type === 'argent') {
            $id_besoin_argent = (int)($_POST['id_besoin_argent'] ?? 0);
            if ($id_besoin_argent <= 0) {
                $error = 'Veuillez selectionner un besoin en argent.';
            } else {
                $restant = $this->model->getRestantArgentByBesoin($id_besoin_argent);
                if ($quantite > $restant) {
                    $error = 'Montant superieur au restant disponible.';
                } else {
                    $this->model->insertDonArgent($id_besoin_argent, $quantite);
                    $message = 'Attribution en argent enregistree avec succes.';
                }
            }
        } else {
            $error = 'Type de don invalide.';
        }

        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'besoins_materiaux' => $this->model->getBesoinsMateriauxForForm(),
            'besoins_argent' => $this->model->getBesoinsArgentForForm(),
            'restant_materiaux' => [],
            'restant_argent' => [],
            'message' => $message,
            'error' => $error
        ];

        Flight::render('attribution', $data);
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
            'categories'         => $this->model->getCategoriesBesoin(),
            'villes'             => $this->model->getVilles()
        ];

        Flight::render('accueil', $data);
    }
}