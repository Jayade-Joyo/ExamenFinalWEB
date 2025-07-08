<?php
require_once __DIR__ . '/../controllers/RemboursementController.php';
require_once __DIR__ . '/../controllers/ClientController.php';
require_once __DIR__ . '/../controllers/PretController.php';

// Routes existantes
Flight::route('GET /remboursements', ['RemboursementController', 'getAll']);
Flight::route('GET /remboursements/@id', ['RemboursementController', 'getById']);
Flight::route('GET /prets/@pretId/remboursements', ['RemboursementController', 'getByPretId']);
Flight::route('GET /remboursements/@mois/@annee', ['RemboursementController', 'getByMoisAnnee']);
Flight::route('POST /remboursements', ['RemboursementController', 'create']);
Flight::route('PUT /remboursements/@id', ['RemboursementController', 'update']);
Flight::route('PATCH /remboursements/@id/statut', ['RemboursementController', 'updateStatut']);
Flight::route('DELETE /remboursements/@id', ['RemboursementController', 'delete']);

// Ajouter cette route pour le filtrage par période
Flight::route('GET /remboursements/periode/@mois_debut/@annee_debut/@mois_fin/@annee_fin', ['RemboursementController', 'getByPeriode']);

// Route pour obtenir les données agrégées pour le graphique
Flight::route('GET /remboursements/graphique/interets', ['RemboursementController', 'getInteretsGraphique']);
Flight::route('GET /remboursements/graphique/interets/periode/@mois_debut/@annee_debut/@mois_fin/@annee_fin', ['RemboursementController', 'getInteretsGraphiquePeriode']);


// Nouvelles routes nécessaires
Flight::route('GET /clients', ['ClientController', 'getAll']);
Flight::route('GET /clients/@clientId/prets', ['PretController', 'getByClientId']);


