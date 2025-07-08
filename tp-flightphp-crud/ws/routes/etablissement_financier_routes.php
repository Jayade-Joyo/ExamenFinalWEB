<?php
require_once __DIR__ . '/../controllers/EtablissementFinancierController.php';

// Routes CRUD pour les établissements financiers
Flight::route('GET /etablissements', ['EtablissementFinancierController', 'getAll']);
Flight::route('GET /etablissements/@id', ['EtablissementFinancierController', 'getById']);
Flight::route('POST /etablissements', ['EtablissementFinancierController', 'create']);
Flight::route('PUT /etablissements/@id', ['EtablissementFinancierController', 'update']);
Flight::route('DELETE /etablissements/@id', ['EtablissementFinancierController', 'delete']);

// Route pour ajouter des fonds à un établissement financier
Flight::route('POST /etablissements/@id/add_funds', ['EtablissementFinancierController', 'addFunds']);
