<?php
require_once __DIR__ . '/../controllers/EtablissementController.php';

Flight::route('GET /etablissements', ['EtablissementController', 'getAll']);
Flight::route('GET /etablissements/@id', ['EtablissementController', 'getById']);
Flight::route('POST /etablissements', ['EtablissementController', 'create']);
Flight::route('PUT /etablissements/@id', ['EtablissementController', 'update']);
Flight::route('DELETE /etablissements/@id', ['EtablissementController', 'delete']);