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

Flight::route('GET /besoins/materiaux', [$controller, 'getBesoinsMateriaux']);
Flight::route('GET /besoin/@id/dons',   [$controller, 'getDonsMateriaux']);

Flight::route('GET /besoins/argent',    [$controller, 'getBesoinsArgent']);
Flight::route('GET /besoin-argent/@id/dons', [$controller, 'getDonsArgent']);

Flight::route('GET /categories',        [$controller, 'getCategoriesBesoin']);

Flight::route('GET /',                  [$controller, 'dashboard']);
Flight::route('GET /accueil',           [$controller, 'dashboard']);