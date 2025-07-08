<?php
// Inclure le contrôleur TypePret
require_once __DIR__ . '/../controllers/PretController.php';
Flight::route('GET /pret', ['PretController', 'getAll']);
Flight::route('GET /pret/@id', ['PretController', 'getById']);
Flight::route('POST /pret', ['PretController', 'create']);
Flight::route('PUT /pret/@id', ['PretController', 'update']);
Flight::route('DELETE /pret/@id', ['PretController', 'delete']);
Flight::route('GET /prets/@id/details', ['PretController', 'getPretDetails']);

Flight::route('GET /prets/@id/pdf', ['PretController', 'generatePDF']);