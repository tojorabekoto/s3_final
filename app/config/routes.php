<?php

use app\controller\BngrcController;

// Create controller instance once
$controller = new BngrcController();

// Routes
Flight::route('GET /regions',           [$controller, 'getRegions']);
Flight::route('GET /villes',            [$controller, 'getVilles']);
Flight::route('GET /region/@id',        [$controller, 'getVillesByRegion']);
Flight::route('GET /sinistres',         [$controller, 'getSinistres']);
Flight::route('GET /ville/@id/sinistres',[$controller, 'getSinistresByVille']);
Flight::route('GET /ville-detail/@id',  [$controller, 'villeDetail']);

Flight::route('GET /besoins/materiaux', [$controller, 'getBesoinsMateriaux']);
Flight::route('GET /besoin/@id/dons',   [$controller, 'getDonsMateriaux']);

Flight::route('GET /besoins/argent',    [$controller, 'getBesoinsArgent']);
Flight::route('GET /besoin-argent/@id/dons', [$controller, 'getDonsArgent']);

Flight::route('GET /categories',        [$controller, 'getCategoriesBesoin']);

// API JSON — sinistres d'une ville (utilisé par le formulaire insertion_besoin)
Flight::route('GET /api/sinistres-ville/@id', [$controller, 'getSinistresByVilleJson']);

// API JSON — pour AJAX (dons + achat)
Flight::route('GET /api/villes/@id',            [$controller, 'apiGetVillesByRegion']);
Flight::route('GET /api/besoins-by-ville/@id',  [$controller, 'apiGetBesoinsByVille']);
Flight::route('GET /api/inventaire/@id',        [$controller, 'apiGetInventaireByVille']);

// Routes pour insertion de besoins
Flight::route('GET /insertion_besoin',  [$controller, 'showInsertionBesoin']);
Flight::route('POST /insertion_besoin', [$controller, 'submitInsertionBesoin']);

Flight::route('GET /insertion_don',     [$controller, 'showInsertionDon']);
Flight::route('POST /insertion_don',    [$controller, 'submitInsertionDon']);

// Routes pour achat de matériaux
Flight::route('GET /achat',             [$controller, 'showAchat']);
Flight::route('POST /achat',            [$controller, 'submitAchat']);

Flight::route('GET /attribution',       [$controller, 'showAttribution']);
Flight::route('POST /attribution',      [$controller, 'submitAttribution']);

// Récapitulation
Flight::route('GET /recap',             [$controller, 'showRecap']);
Flight::route('GET /api/recap',         [$controller, 'apiGetRecapData']);

// V3 — Vente de dons
Flight::route('GET /vente',             [$controller, 'showVente']);
Flight::route('POST /vente',            [$controller, 'submitVente']);
Flight::route('GET /api/stock-vendable',[$controller, 'apiGetStockVendable']);

// V3 — Configuration
Flight::route('GET /config',            [$controller, 'showConfig']);
Flight::route('POST /api/config',       [$controller, 'submitConfig']);

// V3 — Réinitialisation
Flight::route('POST /api/reset',        [$controller, 'submitReset']);

Flight::route('GET /',                  [$controller, 'dashboard']);
Flight::route('GET /accueil',           [$controller, 'dashboard']);