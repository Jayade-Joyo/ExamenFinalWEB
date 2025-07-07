<?php
// Inclure le modèle Client
require_once __DIR__ . '/../models/Client.php';
require_once __DIR__ . '/../helpers/Utils.php'; //

/**
 * Classe ClientController
 * Gère les requêtes HTTP pour les opérations sur les clients.
 */
class ClientController {
    /**
     * Récupère et renvoie tous les clients au format JSON.
     */
    public static function getAll() {
        $clients = Client::getAll();
        Flight::json($clients);
    }

    /**
     * Récupère et renvoie un client spécifique par son ID au format JSON.
     * @param int $id L'ID du client.
     */
    public static function getById($id) {
        $client = Client::getById($id);
        Flight::json($client);
    }

    /**
     * Crée un nouveau client à partir des données de la requête.
     * Renvoie un message de succès et l'ID du nouvel élément au format JSON.
     */
    public static function create() {
        $data = Flight::request()->data;
        // error_log("DEBUG: ClientController - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG

        // Assurer que les champs obligatoires sont présents
        if (!isset($data->nom) || empty($data->nom) || !isset($data->prenom) || empty($data->prenom)) {
            // error_log("ERROR: ClientController - Nom ou Prénom manquant lors de la création."); // DEBUG
            Flight::json(['error' => 'Nom et Prénom sont obligatoires.'], 400);
            return;
        }

        $id = Client::create($data);
        Flight::json(['message' => 'Client ajouté', 'id' => $id]);
    }

    /**
     * Met à jour un client existant à partir des données de la requête.
     * @param int $id L'ID du client à mettre à jour.
     * Renvoie un message de succès au format JSON.
     */
    public static function update($id) {
        // Pour les requêtes PUT avec application/x-www-form-urlencoded,
        // Flight::request()->data ne se remplit pas toujours automatiquement.
        // Il est plus fiable de lire directement le flux d'entrée.
        parse_str(file_get_contents("php://input"), $put_vars);
        $data = (object) $put_vars; // Convertir le tableau en objet pour un accès cohérent

        // error_log("DEBUG: ClientController - Données reçues pour UPDATE ID " . $id . " (via php://input): " . print_r($data, true)); // DEBUG

        // Assurer que les champs obligatoires sont présents
        if (!isset($data->nom) || empty($data->nom) || !isset($data->prenom) || empty($data->prenom)) {
            // error_log("ERROR: ClientController - Nom ou Prénom manquant lors de la mise à jour pour ID: " . $id); // DEBUG
            Flight::json(['error' => 'Nom et Prénom sont obligatoires.'], 400);
            return;
        }

        Client::update($id, $data);
        Flight::json(['message' => 'Client modifié']);
    }

    /**
     * Supprime un client par son ID.
     * @param int $id L'ID du client à supprimer.
     * Renvoie un message de succès au format JSON.
     */
    public static function delete($id) {
        Client::delete($id);
        Flight::json(['message' => 'Client supprimé']);
    }
}
