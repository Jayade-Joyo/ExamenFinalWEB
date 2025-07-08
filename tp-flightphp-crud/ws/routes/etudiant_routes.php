<?php
require_once __DIR__ . '/../controllers/EtudiantController.php';

Flight::route('GET /etudiants', ['EtudiantController', 'getAll']);
Flight::route('GET /etudiants/@id', ['EtudiantController', 'getById']);
Flight::route('POST /etudiants', ['EtudiantController', 'create']);
Flight::route('PUT /etudiants/@id', ['EtudiantController', 'update']);
Flight::route('DELETE /etudiants/@id', ['EtudiantController', 'delete']);
Flight::route('GET /etudiants/age/@age', ['EtudiantController', 'getByAge']);
Flight::route('GET /etudiants/age', ['EtudiantController', 'getByAgeIntervalle']);
