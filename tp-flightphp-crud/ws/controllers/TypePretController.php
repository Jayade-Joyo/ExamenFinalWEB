<?php
// Inclure le modèle TypePret
require_once __DIR__ . '/../models/TypePret.php';
require_once __DIR__ . '/../helpers/Utils.php'; //

/**
 * Classe TypePretController
 * Gère les requêtes HTTP pour les opérations sur les types de prêt.
 */
class TypePretController {
    /**
     * Récupère et renvoie tous les types de prêt au format JSON.
     */
    public static function getAll() {
        $typesPret = TypePret::getAll();
        Flight::json($typesPret);
    }

    /**
     * Récupère et renvoie un type de prêt spécifique par son ID au format JSON.
     * @param int $id L'ID du type de prêt.
     */
    public static function getById($id) {
        $typePret = TypePret::getById($id);
        Flight::json($typePret);
    }

    /**
     * Crée un nouveau type de prêt à partir des données de la requête.
     * Renvoie un message de succès et l'ID du nouvel élément au format JSON.
     */
    public static function create() {
        $data = Flight::request()->data;
        error_log("DEBUG: TypePretController - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG

        // Assurer que 'actif' est un booléen et a une valeur par défaut si non fourni
        $data->actif = isset($data->actif) ? filter_var($data->actif, FILTER_VALIDATE_BOOLEAN) : true;

        // Validation des champs obligatoires
        if (!isset($data->nom_type) || empty($data->nom_type) || !isset($data->taux_interet) || !is_numeric($data->taux_interet) || !isset($data->duree_max) || !is_numeric($data->duree_max)) {
            error_log("ERROR: TypePretController - Champs obligatoires manquants ou invalides lors de la création."); // DEBUG
            Flight::json(['error' => 'Nom du Type, Taux d\'Intérêt et Durée Max sont obligatoires et valides.'], 400);
            return;
        }

        $id = TypePret::create($data);
        Flight::json(['message' => 'Type de prêt ajouté', 'id' => $id]);
    }

    /**
     * Met à jour un type de prêt existant à partir des données de la requête.
     * @param int $id L'ID du type de prêt à mettre à jour.
     * Renvoie un message de succès au format JSON.
     */
    public static function update($id) {
        // Pour les requêtes PUT avec application/x-www-form-urlencoded,
        // Flight::request()->data ne se remplit pas toujours automatiquement.
        // Il est plus fiable de lire directement le flux d'entrée.
        parse_str(file_get_contents("php://input"), $put_vars);
        $data = (object) $put_vars; // Convertir le tableau en objet pour un accès cohérent

        error_log("DEBUG: TypePretController - Données reçues pour UPDATE ID " . $id . " (via php://input): " . print_r($data, true)); // DEBUG

        // Assurer que 'actif' est un booléen et a une valeur par défaut si non fourni
        $data->actif = isset($data->actif) ? filter_var($data->actif, FILTER_VALIDATE_BOOLEAN) : true;

        // Validation des champs obligatoires
        if (!isset($data->nom_type) || empty($data->nom_type) || !isset($data->taux_interet) || !is_numeric($data->taux_interet) || !isset($data->duree_max) || !is_numeric($data->duree_max)) {
            error_log("ERROR: TypePretController - Champs obligatoires manquants ou invalides lors de la mise à jour pour ID: " . $id); // DEBUG
            Flight::json(['error' => 'Nom du Type, Taux d\'Intérêt et Durée Max sont obligatoires et valides.'], 400);
            return;
        }

        TypePret::update($id, $data);
        Flight::json(['message' => 'Type de prêt modifié']);
    }

    /**
     * Supprime un type de prêt par son ID.
     * @param int $id L'ID du type de prêt à supprimer.
     * Renvoie un message de succès au format JSON.
     */
    public static function delete($id) {
        TypePret::delete($id);
        Flight::json(['message' => 'Type de prêt supprimé']);
    }
}
