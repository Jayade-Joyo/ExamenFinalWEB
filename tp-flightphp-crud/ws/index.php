<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/etudiant_routes.php';
require 'routes/remboursement_routes.php';
require 'routes/etablissement_financier_routes.php';
require 'routes/type_pret_routes.php';
require 'routes/client_routes.php';
require 'routes/router.php';

Flight::start();