<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/../db.php';

/**
 * Classe TypePret
 * Gère les opérations CRUD (Create, Read, Update, Delete) pour la table 'TypePret'.
 * Cette version suit strictement la structure de la table TypePret de banque.sql (sans date_creation).
 */
class TypePret {
    /**
     * Récupère tous les types de prêt de la base de données.
     * @return array Un tableau associatif de tous les types de prêt.
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM TypePret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un type de prêt par son ID.
     * @param int $id L'ID du type de prêt à récupérer.
     * @return array|false Un tableau associatif du type de prêt ou false si non trouvé.
     */
    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM TypePret WHERE id_type_pret = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau type de prêt dans la base de données.
     * @param object $data Les données du type de prêt (nom_type, taux_interet, duree_max, description, montant_min, montant_max, actif).
     * @return int L'ID du type de prêt nouvellement créé.
     */
    public static function create($data) {
        $db = getDB();
        error_log("DEBUG: TypePret Model - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG
        $stmt = $db->prepare("INSERT INTO TypePret (nom_type, taux_interet, duree_max, description, montant_min, montant_max, actif) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->nom_type,
            $data->taux_interet,
            $data->duree_max,
            $data->description,
            $data->montant_min,
            $data->montant_max,
            $data->actif
        ]);
        error_log("DEBUG: TypePret Model - Requête INSERT exécutée. Last ID: " . $db->lastInsertId()); // DEBUG
        return $db->lastInsertId();
    }

    /**
     * Met à jour un type de prêt existant.
     * @param int $id L'ID du type de prêt à mettre à jour.
     * @param object $data Les nouvelles données du type de prêt.
     */
    public static function update($id, $data) {
        $db = getDB();
        error_log("DEBUG: TypePret Model - Appel de update pour ID: " . $id); // DEBUG
        error_log("DEBUG: TypePret Model - Données pour la mise à jour: " . print_r($data, true)); // DEBUG

        // Vérification explicite des propriétés
        error_log("DEBUG: TypePret Model - data->nom_type: " . (isset($data->nom_type) ? $data->nom_type : 'NON DÉFINI')); // DEBUG
        error_log("DEBUG: TypePret Model - data->taux_interet: " . (isset($data->taux_interet) ? $data->taux_interet : 'NON DÉFINI')); // DEBUG
        error_log("DEBUG: TypePret Model - data->duree_max: " . (isset($data->duree_max) ? $data->duree_max : 'NON DÉFINI')); // DEBUG


        // Préparer la requête de mise à jour avec toutes les colonnes pertinentes de votre schéma
        $stmt = $db->prepare("UPDATE TypePret SET nom_type = ?, taux_interet = ?, duree_max = ?, description = ?, montant_min = ?, montant_max = ?, actif = ? WHERE id_type_pret = ?");
        $stmt->execute([
            $data->nom_type,
            $data->taux_interet,
            $data->duree_max,
            $data->description,
            $data->montant_min,
            $data->montant_max,
            $data->actif,
            $id
        ]);
        error_log("DEBUG: TypePret Model - Requête UPDATE exécutée."); // DEBUG
    }

    /**
     * Supprime un type de prêt par son ID.
     * @param int $id L'ID du type de prêt à supprimer.
     */
    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM TypePret WHERE id_type_pret = ?");
        $stmt->execute([$id]);
    }
}
