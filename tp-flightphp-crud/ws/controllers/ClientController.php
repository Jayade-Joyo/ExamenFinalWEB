<?php
// Inclure le modèle Client
require_once __DIR__ . '/../models/Client.php';

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

        // Assurer que les champs obligatoires sont présents
        if (!isset($data->nom) || empty($data->nom) || !isset($data->prenom) || empty($data->prenom)) {
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
        $data = Flight::request()->data;

        // Assurer que les champs obligatoires sont présents
        if (!isset($data->nom) || empty($data->nom) || !isset($data->prenom) || empty($data->prenom)) {
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
