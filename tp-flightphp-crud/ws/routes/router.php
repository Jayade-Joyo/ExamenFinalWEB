<?php
// routes/router.php - Routage avec FlightPHP
require_once __DIR__ .'/../vendor/autoload.php'; // Charger FlightPHP
require_once __DIR__ . '/../controllers/PretController.php';

// Route pour afficher la page principale
Flight::route('GET /', function() {
    $controller = new PretController();
    $controller->index();
});

// Route pour les requêtes AJAX
Flight::route('POST /ajax', function() {
    $controller = new PretController();
    $controller->handleAjax();
});

// Redirection pour gérer les accès directs à gestionPret.php
Flight::route('GET /gestionPret.php', function() {
    Flight::redirect('/');
});

// Lancer l'application
Flight::start();
?>