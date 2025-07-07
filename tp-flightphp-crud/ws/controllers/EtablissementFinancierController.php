<?php
require_once __DIR__ . '/../models/EtablissementFinancier.php';
require_once __DIR__ . '/../helpers/Utils.php'; 

class EtablissementFinancierController {
    public static function getAll() {
        $etablissements = EtablissementFinancier::getAll();
        Flight::json($etablissements);
    }

    public static function getById($id) {
        $etablissement = EtablissementFinancier::getById($id);
        Flight::json($etablissement);
    }

    public static function create() {
        $data = Flight::request()->data;
        error_log("DEBUG: EtablissementFinancierController - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG

        // Validation des champs obligatoires pour la création
        if (!isset($data->nom_etablissement) || empty($data->nom_etablissement)) {
            Flight::json(['error' => 'Le nom de l\'établissement est obligatoire.'], 400);
            return;
        }
        if (!isset($data->solde) || !is_numeric($data->solde)) {
            // Assurez-vous que le solde est défini et numérique, sinon définissez une valeur par défaut
            $data->solde = 0.00; // Ou une autre valeur par défaut appropriée
        }

        $id = EtablissementFinancier::create($data);
        Flight::json(['message' => 'Établissement financier ajouté', 'id' => $id]);
    }

    public static function update($id) {
        // Pour les requêtes PUT avec application/x-www-form-urlencoded,
        // Flight::request()->data ne se remplit pas toujours automatiquement.
        // Il est plus fiable de lire directement le flux d'entrée.
        parse_str(file_get_contents("php://input"), $put_vars);
        $data = (object) $put_vars; // Convertir le tableau en objet pour un accès cohérent

        error_log("DEBUG: EtablissementFinancierController - Données reçues pour UPDATE ID " . $id . " (via php://input): " . print_r($data, true)); // DEBUG

        // Validation des champs obligatoires pour la mise à jour
        if (!isset($data->nom_etablissement) || empty($data->nom_etablissement)) {
            error_log("ERROR: EtablissementFinancierController - Nom de l'établissement manquant lors de la mise à jour pour ID: " . $id); // DEBUG
            Flight::json(['error' => 'Le nom de l\'établissement est obligatoire.'], 400);
            return;
        }
        // Assurez-vous que le solde est numérique, sinon cast ou définissez une valeur par défaut
        if (!isset($data->solde) || !is_numeric($data->solde)) {
            error_log("WARNING: EtablissementFinancierController - Solde non numérique ou manquant pour ID: " . $id . ". Valeur reçue: " . (isset($data->solde) ? $data->solde : 'NON DÉFINI')); // DEBUG
            $data->solde = 0.00; // Valeur par défaut pour éviter NaN en DB
        }

        EtablissementFinancier::update($id, $data);
        Flight::json(['message' => 'Établissement financier modifié']);
    }

    public static function delete($id) {
        EtablissementFinancier::delete($id);
        Flight::json(['message' => 'Établissement financier supprimé']);
    }

    public static function addFunds($id) {
        $data = Flight::request()->data; // Pour POST, Flight::request()->data devrait fonctionner
        $montant = $data->montant;
        $description = $data->description ?? null; // Utiliser null si non fourni

        if (!isset($montant) || !is_numeric($montant) || $montant <= 0) {
            Flight::json(['error' => 'Montant invalide.'], 400);
            return;
        }

        if (EtablissementFinancier::addFunds($id, $montant, $description)) {
            Flight::json(['message' => 'Fonds ajoutés avec succès à l\'établissement financier.']);
        } else {
            Flight::json(['error' => 'Échec de l\'ajout de fonds.'], 500);
        }
    }
}
