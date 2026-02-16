<?php

require dirname(__DIR__, 2) . '/vendor/autoload.php';

Flight::register('db', 'PDO', ['mysql:host=localhost;dbname=bngrc', 'root', ''], function($pdo) {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

// Routes
Flight::route('GET /regions',           ['\app\controllers\BngrcController', 'getRegions']);
Flight::route('GET /villes',            ['\app\controllers\BngrcController', 'getVilles']);
Flight::route('GET /region/@id',        ['\app\controllers\BngrcController', 'getVillesByRegion']);
Flight::route('GET /sinistres',         ['\app\controllers\BngrcController', 'getSinistres']);
Flight::route('GET /ville/@id/sinistres',['\app\controllers\BngrcController', 'getSinistresByVille']);

Flight::route('GET /besoins/materiaux', ['\app\controllers\BngrcController', 'getBesoinsMateriaux']);
Flight::route('GET /besoin/@id/dons',   ['\app\controllers\BngrcController', 'getDonsMateriaux']);

Flight::route('GET /besoins/argent',    ['\app\controllers\BngrcController', 'getBesoinsArgent']);
Flight::route('GET /besoin-argent/@id/dons', ['\app\controllers\BngrcController', 'getDonsArgent']);

Flight::route('GET /categories',        ['\app\controllers\BngrcController', 'getCategoriesBesoin']);

Flight::route('GET /',                  ['\app\controllers\BngrcController', 'dashboard']);

// Lancement
Flight::start();