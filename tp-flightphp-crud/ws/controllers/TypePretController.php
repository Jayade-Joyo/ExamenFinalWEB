<?php
// Inclure le modèle TypePret
require_once __DIR__ . '/../models/TypePret.php';

/**
 * Classe TypePretController
 * Gère les requêtes HTTP pour les opérations sur les types de prêt.
 * Cette version suit strictement la structure de la table TypePret de banque.sql (sans date_creation).
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
        error_log("DEBUG: TypePretController - Données reçues pour CREATE: " . print_r($data, true)); // Ligne de débogage
        $data->actif = isset($data->actif) ? filter_var($data->actif, FILTER_VALIDATE_BOOLEAN) : true;
        $id = TypePret::create($data);
        Flight::json(['message' => 'Type de prêt ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        error_log("DEBUG: TypePretController - Données reçues pour UPDATE ID " . $id . ": " . print_r($data, true)); // Ligne de débogage
        $data->actif = isset($data->actif) ? filter_var($data->actif, FILTER_VALIDATE_BOOLEAN) : true;
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
