<?php
require_once __DIR__ . '/../models/EtablissementFinancier.php';
require_once __DIR__ . '/../helpers/Utils.php'; // Si vous avez des fonctions utilitaires, sinon vous pouvez le supprimer

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
        $id = EtablissementFinancier::create($data);
        Flight::json(['message' => 'Établissement financier ajouté', 'id' => $id]);
    }

  
    public static function update($id) {
        $data = Flight::request()->data;
        error_log("DEBUG: EtablissementFinancierController - Reçu pour update ID: " . $id);
        error_log("DEBUG: EtablissementFinancierController - Données reçues: " . print_r($data, true)); // Ceci affichera l'objet $data

        EtablissementFinancier::update($id, $data);
        Flight::json(['message' => 'Établissement financier modifié']);
    }

    public static function delete($id) {
        EtablissementFinancier::delete($id);
        Flight::json(['message' => 'Établissement financier supprimé']);
    }

    public static function addFunds($id) {
        $data = Flight::request()->data;
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