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
    // Insertion de don dans le stock global
    // ────────────────────────────────────────────────
    public function showInsertionDon()
    {
        $categories = $this->model->getCategoriesBesoin();
        $produits = $this->model->getAllProduits();
        
        // Grouper les produits par catégorie pour le JavaScript
        $produits_par_categorie = [];
        foreach ($produits as $produit) {
            $id_cat = $produit['id_categorie'];
            if (!isset($produits_par_categorie[$id_cat])) {
                $produits_par_categorie[$id_cat] = [];
            }
            $produits_par_categorie[$id_cat][] = $produit;
        }

        $data = [
            'categories' => $categories,
            'produits_par_categorie' => $produits_par_categorie,
            'message' => $_SESSION['message'] ?? null,
            'error' => $_SESSION['error'] ?? null
        ];
        unset($_SESSION['message'], $_SESSION['error']);

        Flight::render('insertion_don_v2', $data);
    }

    public function submitInsertionDon()
    {
        $error = null;
        $message = null;
        $successCount = 0;

        // Récupérer les dons en JSON
        $donsJson = $_POST['dons_json'] ?? '{}';
        $panier = json_decode($donsJson, true);

        if (!is_array($panier)) {
            $error = 'Données invalides.';
        } else {
            // Traiter les dons matériels
            if (isset($panier['materiaux']) && is_array($panier['materiaux'])) {
                foreach ($panier['materiaux'] as $don) {
                    $id_produit = !empty($don['id_produit']) ? (int)$don['id_produit'] : null;
                    $quantite = (float)($don['quantite'] ?? 0);

                    if ($quantite <= 0) continue;

                    // Si pas d'id_produit, c'est un nouveau produit → le créer
                    if (!$id_produit) {
                        $nom = trim($don['nom_produit'] ?? '');
                        $id_categorie = (int)($don['id_categorie'] ?? 0);
                        $unite = trim($don['unite'] ?? '');
                        $prix = (float)($don['prix_unitaire'] ?? 0);

                        if ($nom && $id_categorie > 0) {
                            $id_produit = $this->model->getOrCreateProduit($nom, $id_categorie, $unite, $prix);
                        }
                    }

                    if ($id_produit > 0) {
                        $this->model->insertDonStockMateriel($id_produit, $quantite);
                        $successCount++;
                    }
                }
            }

            // Traiter le don d'argent
            if (isset($panier['argent']) && $panier['argent'] > 0) {
                $this->model->insertDonStockArgent($panier['argent']);
                $successCount++;
            }

            if ($successCount > 0) {
                $message = "$successCount don(s) ajouté(s) au stock global avec succès.";
            } else {
                $error = 'Aucun don valide à enregistrer.';
            }
        }

        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['message'] = $message;
        $_SESSION['error'] = $error;
        Flight::redirect('/insertion_don');
    }

    // ────────────────────────────────────────────────
    // Insertion de besoin
    // ────────────────────────────────────────────────
    public function showInsertionBesoin()
    {
        $id_ville  = isset($_GET['id_ville']) ? (int)$_GET['id_ville'] : 0;
        $ville_nom = '';

        if ($id_ville > 0) {
            $villeRow  = $this->model->getVilleById($id_ville);
            $ville_nom = $villeRow['nom_ville'] ?? '';
        }

        $sinistres = ($id_ville > 0) ? $this->model->getSinistresByVille($id_ville) : [];

        $data = [
            'regions'         => $this->model->getRegions(),
            'villes'          => $this->model->getVilles(),
            'categories'      => $this->model->getCategoriesBesoin(),
            'sinistres'       => $sinistres,
            'id_ville'        => $id_ville,
            'ville_nom'       => $ville_nom,
            'message'         => $_SESSION['flash_message'] ?? null,
            'error'           => null,
            'type_besoin_sel' => '',
            'id_cat_sel'      => 0,
            'nom_besoin_sel'  => '',
            'quantite_sel'    => '',
            'unite_sel'       => '',
            'montant_sel'     => '',
            'id_sinistre_sel' => 0,
        ];
        unset($_SESSION['flash_message']);

        Flight::render('insertion_besoin', $data);
    }

    public function submitInsertionBesoin()
    {
        $error   = null;
        $message = null;

        $id_ville     = (int)($_POST['id_ville']     ?? 0);
        $type_besoin  = trim($_POST['type_besoin']   ?? '');
        $id_categorie = (int)($_POST['id_categorie'] ?? 0);
        $nom_besoin   = trim($_POST['nom_besoin']    ?? '');
        $unite        = trim($_POST['unite']         ?? '');

        $quantite_raw = isset($_POST['quantite']) ? trim($_POST['quantite']) : '';
        $montant_raw  = isset($_POST['montant'])  ? trim($_POST['montant'])  : '';
        $quantite     = ($quantite_raw !== '') ? (float)$quantite_raw : 0;
        $montant      = ($montant_raw  !== '') ? (float)$montant_raw  : 0;

        // ── Validation commune ─────────────────────────────────────
        if ($id_ville <= 0) {
            $error = 'Veuillez sélectionner une ville.';
        } elseif (empty($type_besoin)) {
            $error = 'Veuillez sélectionner un type de besoin.';
        }

        // ── Traitement selon le type ───────────────────────────────
        if (!$error) {
            if ($type_besoin === 'naturels' || $type_besoin === 'materiaux') {
                if ($id_categorie <= 0) {
                    $error = 'Veuillez sélectionner une catégorie.';
                } elseif (empty($nom_besoin)) {
                    $error = 'Veuillez saisir un nom de besoin.';
                } elseif ($quantite <= 0) {
                    $error = 'La quantité doit être supérieure à 0.';
                } else {
                    try {
                        $prix_unitaire = (float)($_POST['prix_unitaire'] ?? 0);
                        $this->model->insertBesoinMateriaux(
                            $id_ville,
                            $id_categorie,
                            $nom_besoin,
                            $quantite,
                            $unite,
                            $prix_unitaire
                        );
                        $message = 'Besoin "' . htmlspecialchars($nom_besoin) . '" enregistré avec succès !';
                    } catch (\RuntimeException $e) {
                        $error = $e->getMessage();
                    }
                }

            } elseif ($type_besoin === 'argent') {
                if ($montant <= 0) {
                    $error = 'Le montant doit être supérieur à 0.';
                } else {
                    $id_sinistre = (int)($_POST['id_sinistre'] ?? 0);

                    // Si non fourni, prendre automatiquement le 1er sinistre de la ville
                    if ($id_sinistre <= 0) {
                        $sinistres = $this->model->getSinistresByVille($id_ville);
                        if (empty($sinistres)) {
                            $error = 'Aucun sinistre trouvé pour cette ville. Créez d\'abord un sinistre.';
                        } else {
                            $id_sinistre = (int)$sinistres[0]['id_sinistre'];
                        }
                    }

                    if (!$error) {
                        $this->model->insertBesoinArgent($id_sinistre, $montant);
                        $message = 'Besoin en argent de ' . number_format($montant, 2, ',', ' ') . ' Ar enregistré avec succès !';
                    }
                }

            } else {
                $error = 'Type de besoin invalide.';
            }
        }

        // ── Succès → redirection vers ville_detail ─────────────────
        if ($message && $id_ville > 0) {
            $_SESSION['flash_message'] = $message;
            Flight::redirect('/ville-detail/' . $id_ville);
            return;
        }

        // ── Erreur → ré-afficher le formulaire ────────────────────
        $ville_nom = '';
        if ($id_ville > 0) {
            $villeRow  = $this->model->getVilleById($id_ville);
            $ville_nom = $villeRow['nom_ville'] ?? '';
        }

        $sinistres       = ($id_ville > 0) ? $this->model->getSinistresByVille($id_ville) : [];
        $id_sinistre_sel = (int)($_POST['id_sinistre'] ?? 0);

        $data = [
            'regions'         => $this->model->getRegions(),
            'villes'          => $this->model->getVilles(),
            'categories'      => $this->model->getCategoriesBesoin(),
            'sinistres'       => $sinistres,
            'id_ville'        => $id_ville,
            'ville_nom'       => $ville_nom,
            'message'         => $message,
            'error'           => $error,
            'type_besoin_sel' => $type_besoin,
            'id_cat_sel'      => $id_categorie,
            'nom_besoin_sel'  => $nom_besoin,
            'quantite_sel'    => $quantite ?: '',
            'unite_sel'       => $unite,
            'montant_sel'     => $montant  ?: '',
            'id_sinistre_sel' => $id_sinistre_sel,
        ];

        Flight::render('insertion_besoin', $data);
    }

    public function getSinistresByVilleJson($id_ville)
    {
        $id_ville  = (int)$id_ville;
        $sinistres = $this->model->getSinistresByVille($id_ville);
        Flight::json($sinistres);
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
            'stock_materiel' => $this->model->getStockMateriel(),
            'stock_argent' => $this->model->getStockArgent(),
            'total_stock_argent' => $this->model->getTotalStockArgent(),
            'message' => null,
            'error' => null
        ];

        Flight::render('attribution_new', $data);
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
            $id_stock = (int)($_POST['id_stock'] ?? 0);
            $id_besoin = (int)($_POST['id_besoin'] ?? 0);
            
            if ($id_stock <= 0 || $id_besoin <= 0) {
                $error = 'Veuillez selectionner un stock et un besoin.';
            } else {
                $restant = $this->model->getRestantMateriauxByBesoin($id_besoin);
                if ($quantite > $restant) {
                    $error = 'Quantite superieure au restant disponible du besoin.';
                } else {
                    // Diminuer le stock
                    if ($this->model->diminuerStockMateriel($id_stock, $quantite)) {
                        // Créer l'attribution
                        $this->model->insertDonMateriaux($id_besoin, $quantite);
                        $message = "Attribution reussie: $quantite unites attribuees.";
                    } else {
                        $error = 'Stock insuffisant pour cette quantite.';
                    }
                }
            }
        } elseif ($type === 'argent') {
            $id_besoin_argent = (int)($_POST['id_besoin_argent'] ?? 0);
            
            if ($id_besoin_argent <= 0) {
                $error = 'Veuillez selectionner un besoin.';
            } else {
                // Vérifier le total disponible
                $totalDispo = $this->model->getTotalStockArgent();
                if ($quantite > $totalDispo) {
                    $error = 'Stock argent insuffisant. Disponible: ' . number_format($totalDispo, 0, ',', ' ') . ' Ar';
                } else {
                    $restant = $this->model->getRestantArgentByBesoin($id_besoin_argent);
                    if ($quantite > $restant) {
                        $error = 'Montant superieur au restant disponible du besoin.';
                    } else {
                        // Diminuer le stock argent global (pioche dans les entrées les plus anciennes)
                        if ($this->model->diminuerStockArgentGlobal($quantite)) {
                            // Créer l'attribution
                            $this->model->insertDonArgent($id_besoin_argent, $quantite);
                            $message = "Attribution reussie: ".number_format($quantite, 0, ',', ' ')." Ar attribues.";
                        } else {
                            $error = 'Stock insuffisant pour ce montant.';
                        }
                    }
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
            'stock_materiel' => $this->model->getStockMateriel(),
            'stock_argent' => $this->model->getStockArgent(),
            'total_stock_argent' => $this->model->getTotalStockArgent(),
            'message' => $message,
            'error' => $error
        ];

        Flight::render('attribution_new', $data);
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
            'villes'             => $this->model->getVilles(),
            'achats_par_ville'   => $this->model->getTotalAchatsParVille()
        ];

        Flight::render('accueil', $data);
    }

    // ────────────────────────────────────────────────
    // Page Dons : Donner pour un besoin spécifique
    // ────────────────────────────────────────────────
    public function showDons()
    {
        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'message' => $_SESSION['flash_message'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_error']);

        Flight::render('dons', $data);
    }

    public function submitDons()
    {
        $id_ville = (int)($_POST['id_ville'] ?? 0);
        $error = null;
        $message = null;

        if ($id_ville <= 0) {
            $_SESSION['flash_error'] = 'Veuillez sélectionner une ville.';
            Flight::redirect('/dons');
            return;
        }

        $sinistre = $this->model->getSinistreByVille($id_ville);
        if (!$sinistre) {
            $_SESSION['flash_error'] = 'Aucun sinistre trouvé pour cette ville.';
            Flight::redirect('/dons');
            return;
        }

        $id_sinistre = $sinistre['id_sinistre'];
        $dons_json = $_POST['dons_json'] ?? '{}';
        $dons = json_decode($dons_json, true);

        if (!is_array($dons) || empty($dons)) {
            $_SESSION['flash_error'] = 'Aucun don à enregistrer.';
            Flight::redirect('/dons');
            return;
        }

        $successCount = 0;

        foreach ($dons as $don) {
            $type = $don['type'] ?? '';
            
            if ($type === 'materiaux') {
                $id_besoin = (int)($don['id_besoin'] ?? 0);
                $quantite = (float)($don['quantite'] ?? 0);
                
                if ($id_besoin > 0 && $quantite > 0) {
                    $besoin = $this->model->getBesoinById($id_besoin);
                    if ($besoin) {
                        $this->model->insertDonMateriaux($id_besoin, $quantite);
                        $this->model->insertHistoriqueDonMateriaux(
                            $id_sinistre, $id_ville, $id_besoin, $quantite,
                            $besoin['unite'] ?? '', $don['remarque'] ?? null
                        );
                        $successCount++;
                    }
                }
            } elseif ($type === 'argent') {
                $id_besoin_argent = (int)($don['id_besoin_argent'] ?? 0);
                $montant = (float)($don['montant'] ?? 0);
                
                if ($id_besoin_argent > 0 && $montant > 0) {
                    $this->model->insertDonArgent($id_besoin_argent, $montant);
                    $this->model->ajouterInventaireArgent($id_sinistre, $montant);
                    $this->model->insertHistoriqueDonArgent(
                        $id_sinistre, $id_ville, $montant, $don['remarque'] ?? null
                    );
                    $successCount++;
                }
            }
        }

        if ($successCount > 0) {
            $_SESSION['flash_message'] = "$successCount don(s) enregistré(s) avec succès !";
        } else {
            $_SESSION['flash_error'] = 'Aucun don valide à enregistrer.';
        }

        Flight::redirect('/dons');
    }

    // ────────────────────────────────────────────────
    // Page Achat : Acheter des matériaux avec l'argent
    // ────────────────────────────────────────────────
    public function showAchat()
    {
        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'message' => $_SESSION['flash_message'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
            'show_success_modal' => false,
            'achats_details' => [],
            'prix_total' => 0,
            'id_ville' => 0
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_error']);

        Flight::render('achat', $data);
    }

    public function submitAchat()
    {
        $id_ville = (int)($_POST['id_ville'] ?? 0);
        $error = null;
        $message = null;
        $show_success_modal = false;
        $achats_details = [];
        $prix_total = 0;

        if ($id_ville <= 0) {
            $error = 'Veuillez sélectionner une ville.';
        } else {
            $sinistre = $this->model->getSinistreByVille($id_ville);
            if (!$sinistre) {
                $error = 'Aucun sinistre trouvé pour cette ville.';
            } else {
                $id_sinistre = $sinistre['id_sinistre'];
                $materiaux_selectionnes = $_POST['materiaux'] ?? [];

                if (empty($materiaux_selectionnes)) {
                    $error = 'Veuillez sélectionner au moins un matériau.';
                } else {
                    // Calcul du coût total
                    foreach ($materiaux_selectionnes as $id_besoin => $quantite) {
                        $quantite = (float)$quantite;
                        if ($quantite > 0) {
                            $besoin = $this->model->getBesoinById($id_besoin);
                            if ($besoin) {
                                $prix_total += $quantite * ($besoin['prix_unitaire'] ?: 0);
                            }
                        }
                    }

                    // Vérifier argent disponible
                    $inventaire_argent = $this->model->getInventaireArgentBySinistre($id_sinistre);
                    $argent_disponible = $inventaire_argent ? (float)$inventaire_argent['montant_disponible'] : 0;

                    if ($argent_disponible < $prix_total) {
                        $error = 'Argent insuffisant. Disponible: ' . number_format($argent_disponible, 0, ',', ' ') . ' Ar, Coût: ' . number_format($prix_total, 0, ',', ' ') . ' Ar';
                    }

                    if (!$error) {
                        foreach ($materiaux_selectionnes as $id_besoin => $quantite) {
                            $quantite = (float)$quantite;
                            if ($quantite > 0) {
                                $besoin = $this->model->getBesoinById($id_besoin);
                                if ($besoin) {
                                    $prix_unitaire = $besoin['prix_unitaire'] ?: 0;
                                    $prix_item = $quantite * $prix_unitaire;

                                    $this->model->insertAchatMateriaux($id_sinistre, $id_besoin, $quantite, $prix_unitaire, $prix_item);
                                    
                                    $quantite_avant = $besoin['quantite'];
                                    $quantite_apres = $quantite_avant - $quantite;
                                    $this->model->reduceBesoinQuantite($id_besoin, $quantite);

                                    $this->model->insertHistoriqueUsedArgent(
                                        $id_sinistre, $id_besoin, $prix_item, $quantite,
                                        'Achat de ' . $besoin['nom_besoin']
                                    );

                                    $achats_details[] = [
                                        'nom_besoin' => $besoin['nom_besoin'],
                                        'categorie' => $besoin['categorie'],
                                        'unite' => $besoin['unite'],
                                        'quantite_achetee' => $quantite,
                                        'quantite_avant' => $quantite_avant,
                                        'quantite_apres' => $quantite_apres,
                                        'prix_unitaire' => $prix_unitaire,
                                        'prix_total' => $prix_item
                                    ];
                                }
                            }
                        }

                        $this->model->updateInventaireArgentApresAchat($id_sinistre, $prix_total);
                        $message = 'Achat enregistré avec succès !';
                        $show_success_modal = true;
                    }
                }
            }
        }

        $data = [
            'regions' => $this->model->getRegions(),
            'villes' => $this->model->getVilles(),
            'message' => $message,
            'error' => $error,
            'show_success_modal' => $show_success_modal,
            'achats_details' => $achats_details,
            'prix_total' => $prix_total,
            'id_ville' => $id_ville
        ];

        Flight::render('achat', $data);
    }

    // ────────────────────────────────────────────────
    // API JSON endpoints for AJAX
    // ────────────────────────────────────────────────
    public function apiGetVillesByRegion($id_region)
    {
        $villes = $this->model->getVillesByRegion((int)$id_region);
        Flight::json($villes);
    }

    public function apiGetBesoinsByVille($id_ville)
    {
        $id_ville = (int)$id_ville;
        $materiaux = $this->model->getBesoinsMateriauxByVilleAvecPrix($id_ville);
        $argent = $this->model->getBesoinsArgentByVille($id_ville);
        Flight::json(['materiaux' => $materiaux, 'argent' => $argent]);
    }

    public function apiGetInventaireByVille($id_ville)
    {
        $id_ville = (int)$id_ville;
        $sinistre = $this->model->getSinistreByVille($id_ville);

        if (!$sinistre) {
            Flight::json(['materiaux' => [], 'argent' => null], 404);
            return;
        }

        $id_sinistre = $sinistre['id_sinistre'];
        $materiaux = $this->model->getInventaireMateriauxBySinistre($id_sinistre);
        $argent = $this->model->getInventaireArgentBySinistre($id_sinistre);

        Flight::json(['materiaux' => $materiaux, 'argent' => $argent]);
    }

    public function apiGetProduitsByCategorie($id_categorie)
    {
        $id_categorie = (int)$id_categorie;
        $produits = $this->model->getProduitsParCategorie($id_categorie);
        Flight::json($produits);
    }

    // ────────────────────────────────────────────────
    // Récapitulation
    // ────────────────────────────────────────────────
    public function showRecap()
    {
        $data = $this->model->getRecapData();
        Flight::render('recap', $data);
    }

    public function apiGetRecapData()
    {
        $data = $this->model->getRecapData();
        Flight::json($data);
    }

    // ════════════════════════════════════════════════
    // V3 — Vente de dons
    // ════════════════════════════════════════════════

    public function showVente()
    {
        $data = [
            'stock_vendable' => $this->model->getStockVendable(),
            'pourcentage' => (float)($this->model->getConfigValue('pourcentage_reduction_vente') ?? 30),
            'historique_ventes' => $this->model->getHistoriqueVentes(),
            'message' => $_SESSION['flash_message'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
            'show_success_modal' => false,
            'vente_result' => null,
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_error']);
        Flight::render('vente', $data);
    }

    public function submitVente()
    {
        $id_produit = (int)($_POST['id_produit'] ?? 0);
        $quantite = (float)($_POST['quantite'] ?? 0);

        $error = null;
        $vente_result = null;
        $show_success_modal = false;

        if ($id_produit <= 0 || $quantite <= 0) {
            $error = 'Veuillez remplir tous les champs correctement.';
        } else {
            $result = $this->model->processVente($id_produit, $quantite);
            if ($result['success']) {
                $show_success_modal = true;
                $vente_result = $result;
            } else {
                $error = $result['error'];
            }
        }

        $data = [
            'stock_vendable' => $this->model->getStockVendable(),
            'pourcentage' => (float)($this->model->getConfigValue('pourcentage_reduction_vente') ?? 30),
            'historique_ventes' => $this->model->getHistoriqueVentes(),
            'message' => $show_success_modal ? 'Vente effectuée avec succès !' : null,
            'error' => $error,
            'show_success_modal' => $show_success_modal,
            'vente_result' => $vente_result,
        ];
        Flight::render('vente', $data);
    }

    public function apiGetStockVendable()
    {
        $stock = $this->model->getStockVendable();
        $pourcentage = (float)($this->model->getConfigValue('pourcentage_reduction_vente') ?? 30);
        Flight::json(['stock' => $stock, 'pourcentage' => $pourcentage]);
    }

    // ════════════════════════════════════════════════
    // V3 — Configuration
    // ════════════════════════════════════════════════

    public function showConfig()
    {
        $data = [
            'configs' => $this->model->getAllConfig(),
            'message' => $_SESSION['flash_message'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
        ];
        unset($_SESSION['flash_message'], $_SESSION['flash_error']);
        Flight::render('config', $data);
    }

    public function submitConfig()
    {
        $cle = $_POST['cle'] ?? '';
        $valeur = $_POST['valeur'] ?? '';

        if (empty($cle) || $valeur === '') {
            Flight::json(['success' => false, 'error' => 'Paramètres invalides.']);
            return;
        }

        $this->model->setConfigValue($cle, $valeur);
        Flight::json(['success' => true, 'message' => 'Configuration mise à jour.']);
    }

    // ════════════════════════════════════════════════
    // V3 — Réinitialisation
    // ════════════════════════════════════════════════

    public function submitReset()
    {
        $result = $this->model->resetDatabase();
        Flight::json($result);
    }
}