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

Flight::route('GET /insertion_don',     [$controller, 'showInsertionDon']);
Flight::route('POST /insertion_don',    [$controller, 'submitInsertionDon']);
Flight::route('GET /attribution',       [$controller, 'showAttribution']);
Flight::route('POST /attribution',      [$controller, 'submitAttribution']);

Flight::route('GET /',                  [$controller, 'dashboard']);
Flight::route('GET /accueil',           [$controller, 'dashboard']);

// API routes (JSON) for AJAX views
Flight::group('/api', function() use ($controller) {
	Flight::route('GET /regions',               [$controller, 'apiGetRegions']);
	Flight::route('GET /villes',                [$controller, 'apiGetVilles']);
	Flight::route('GET /villes/@id',            [$controller, 'apiGetVillesByRegion']);
	Flight::route('GET /besoins-by-ville/@id',  [$controller, 'apiGetBesoinsByVille']);
});