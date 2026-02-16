<?php
require '../vendor/autoload.php';

Flight::route('/', function () {
    Flight::redirect('/login');
});

Flight::route('/login', function () {
    require  __DIR__ . '/../views/login.php';
});

Flight::start();
?>

