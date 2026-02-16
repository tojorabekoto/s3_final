<?php

// Routes
Flight::route('GET /regions',           ['app\controller\BngrcController', 'getRegions']);
Flight::route('GET /villes',            ['app\controller\BngrcController', 'getVilles']);
Flight::route('GET /region/@id',        ['app\controller\BngrcController', 'getVillesByRegion']);
Flight::route('GET /sinistres',         ['app\controller\BngrcController', 'getSinistres']);
Flight::route('GET /ville/@id/sinistres',['app\controller\BngrcController', 'getSinistresByVille']);

Flight::route('GET /besoins/materiaux', ['app\controller\BngrcController', 'getBesoinsMateriaux']);
Flight::route('GET /besoin/@id/dons',   ['app\controller\BngrcController', 'getDonsMateriaux']);

Flight::route('GET /besoins/argent',    ['app\controller\BngrcController', 'getBesoinsArgent']);
Flight::route('GET /besoin-argent/@id/dons', ['app\controller\BngrcController', 'getDonsArgent']);

Flight::route('GET /categories',        ['app\controller\BngrcController', 'getCategoriesBesoin']);

Flight::route('GET /',                  ['app\controller\BngrcController', 'dashboard']);
Flight::route('GET /accueil',           ['app\controller\BngrcController', 'dashboard']);