<?php
// Inclure le modèle TypePret
require_once __DIR__ . '/../models/Pret.php';

/**
 * Classe TypePretController
 * Gère les requêtes HTTP pour les opérations sur les types de prêt.
 * Cette version suit strictement la structure de la table TypePret de banque.sql (sans date_creation).
 */
class PretController {
    /**
     * Récupère et renvoie tous les types de prêt au format JSON.
     */
    public static function getAll() {
        $db = getDB();
        // Requête avec jointures pour avoir les noms complets
        $stmt = $db->query("
            SELECT p.*, 
                   c.nom as client_nom, c.prenom as client_prenom,
                   tp.nom_type as type_pret_nom,
                   e.nom_etablissement as etablissement_nom,
                   s.libelle as statut_libelle,
                   f.libelle as frequence_libelle
            FROM Pret p
            LEFT JOIN Client c ON p.id_client = c.id_client
            LEFT JOIN TypePret tp ON p.id_type_pret = tp.id_type_pret
            LEFT JOIN EtablissementFinancier e ON p.id_etablissement = e.id_etablissement
            LEFT JOIN StatutPret s ON p.id_statut = s.id_statut
            LEFT JOIN FrequencePaiement f ON p.id_frequence = f.id_frequence
        ");
        Flight::json($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Récupère et renvoie un type de prêt spécifique par son ID au format JSON.
     * @param int $id L'ID du type de prêt.
     */
    public static function getById($id) {
        $pret = Pret::getById($id);
        Flight::json($pret);
    }

    /**
     * Crée un nouveau type de prêt à partir des données de la requête.
     * Renvoie un message de succès et l'ID du nouvel élément au format JSON.
     */
    public static function create() {
        $data = Flight::request()->data->getData();
        error_log("DEBUG: PretController - Données reçues pour CREATE: " . print_r($data, true)); // Ligne de débogage
        $id = Pret::create($data);
        Flight::json(['message' => ' prêt ajouté', 'id' => $id]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        error_log("DEBUG: PretController - Données reçues pour UPDATE ID " . $id . ": " . print_r($data, true)); // Ligne de débogage
        $data->actif = isset($data->actif) ? filter_var($data->actif, FILTER_VALIDATE_BOOLEAN) : true;
        Pret::update($id, $data);
        Flight::json(['message' => 'Type de prêt modifié']);
    }
    /**
     * Supprime un type de prêt par son ID.
     * @param int $id L'ID du type de prêt à supprimer.
     * Renvoie un message de succès au format JSON.
     */
    public static function delete($id) {
        Pret::delete($id);
        Flight::json(['message' => 'Type de prêt supprimé']);
    }
}
