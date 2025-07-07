<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/../db.php';

/**
 * Classe Client
 * Gère les opérations CRUD (Create, Read, Update, Delete) pour la table 'Client'.
 * Suit strictement la structure de la table Client de banque.sql.
 */
class Client {
    /**
     * Récupère tous les clients de la base de données.
     * @return array Un tableau associatif de tous les clients.
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM Client");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un client par son ID.
     * @param int $id L'ID du client à récupérer.
     * @return array|false Un tableau associatif du client ou false si non trouvé.
     */
    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Client WHERE id_client = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau client dans la base de données.
     * La colonne `date_inscription` est gérée automatiquement par la base de données (DEFAULT CURRENT_TIMESTAMP).
     * @param object $data Les données du client (nom, prenom, date_naissance, adresse, telephone, email, profession, revenu_mensuel).
     * @return int L'ID du client nouvellement créé.
     */
    public static function create($data) {
        $db = getDB();
        // error_log("DEBUG: Client Model - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG
        $stmt = $db->prepare("INSERT INTO Client (nom, prenom, date_naissance, adresse, telephone, email, profession, revenu_mensuel) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->nom,
            $data->prenom,
            $data->date_naissance,
            $data->adresse,
            $data->telephone,
            $data->email,
            $data->profession,
            $data->revenu_mensuel
        ]);
        // error_log("DEBUG: Client Model - Requête INSERT exécutée. Last ID: " . $db->lastInsertId()); // DEBUG
        return $db->lastInsertId();
    }

    /**
     * Met à jour un client existant.
     * @param int $id L'ID du client à mettre à jour.
     * @param object $data Les nouvelles données du client.
     */
    public static function update($id, $data) {
        $db = getDB();
        // error_log("DEBUG: Client Model - Appel de update pour ID: " . $id); // DEBUG
        // error_log("DEBUG: Client Model - Données pour la mise à jour: " . print_r($data, true)); // DEBUG

        // Préparer la requête de mise à jour avec toutes les colonnes pertinentes de votre schéma
        $stmt = $db->prepare("UPDATE Client SET nom = ?, prenom = ?, date_naissance = ?, adresse = ?, telephone = ?, email = ?, profession = ?, revenu_mensuel = ? WHERE id_client = ?");
        $stmt->execute([
            $data->nom,
            $data->prenom,
            $data->date_naissance,
            $data->adresse,
            $data->telephone,
            $data->email,
            $data->profession,
            $data->revenu_mensuel,
            $id
        ]);
        // error_log("DEBUG: Client Model - Requête UPDATE exécutée."); // DEBUG
    }

    /**
     * Supprime un client par son ID.
     * @param int $id L'ID du client à supprimer.
     */
    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM Client WHERE id_client = ?");
        $stmt->execute([$id]);
    }
}
