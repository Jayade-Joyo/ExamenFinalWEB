<?php
// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/../db.php';

/**
 * Classe TypePret
 * Gère les opérations CRUD (Create, Read, Update, Delete) pour la table 'TypePret'.
 * Cette version suit strictement la structure de la table TypePret de banque.sql (sans date_creation).
 */
class Pret {
    // public function getPretWithDetails($pretId) {
    //     // Requête pour obtenir tous les détails du prêt
    //     $stmt = $this->db->prepare("
    //         SELECT p.*, 
    //                c.nom AS client_nom, c.prenom AS client_prenom,
    //                t.libelle AS type_pret,
    //                s.libelle AS statut,
    //                f.libelle AS frequence_paiement,
    //                e.nom AS etablissement
    //         FROM Pret p
    //         JOIN Client c ON p.id_client = c.id_client
    //         JOIN TypePret t ON p.id_type_pret = t.id_type_pret
    //         JOIN StatutPret s ON p.id_statut = s.id_statut
    //         JOIN FrequencePaiement f ON p.id_frequence = f.id_frequence
    //         JOIN EtablissementFinancier e ON p.id_etablissement = e.id_etablissement
    //         WHERE p.id_pret = ?
    //     ");
    //     $stmt->execute([$pretId]);
    //     $pret = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //     // Ajouter les remboursements associés
    //     $stmtRemb = $this->db->prepare("
    //         SELECT * FROM Remboursement 
    //         WHERE id_pret = ?
    //         ORDER BY date_echeance
    //     ");
    //     $stmtRemb->execute([$pretId]);
    //     $pret['remboursements'] = $stmtRemb->fetchAll(PDO::FETCH_ASSOC);
    
    //     return $pret;
    // }

    public function getPretWithDetails($pretId) {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.nom AS client_nom, c.prenom AS client_prenom,
                   t.libelle AS type_pret,
                   s.libelle AS statut,
                   f.libelle AS frequence_paiement,
                   e.nom AS etablissement
            FROM Pret p
            JOIN Client c ON p.id_client = c.id_client
            JOIN TypePret t ON p.id_type_pret = t.id_type_pret
            JOIN StatutPret s ON p.id_statut = s.id_statut
            JOIN FrequencePaiement f ON p.id_frequence = f.id_frequence
            JOIN EtablissementFinancier e ON p.id_etablissement = e.id_etablissement
            WHERE p.id_pret = ?
        ");
        $stmt->execute([$pretId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByClientId($clientId) {
        $db = getDB();
        $stmt = $db->prepare("
            SELECT p.*, t.nom_type as type_pret 
            FROM Pret p
            JOIN TypePret t ON p.id_type_pret = t.id_type_pret
            WHERE p.id_client = ?
            ORDER BY p.date_debut DESC
        ");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Récupère tous les types de prêt de la base de données.
     * @return array Un tableau associatif de tous les types de prêt.
     */
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM Pret");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un type de prêt par son ID.
     * @param int $id L'ID du type de prêt à récupérer.
     * @return array|false Un tableau associatif du type de prêt ou false si non trouvé.
     */
    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Pret WHERE id_pret = ?");
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
        error_log("DEBUG: Pret Model - Données pour CREATE dans le modèle: " . print_r($data, true)); // Ligne de débogage
        $stmt = $db->prepare("INSERT INTO Pret (id_client, id_type_pret,  id_etablissement, id_statut, id_frequence, montant, date_debut, date_fin, taux_applique, montant_restant, date_creation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->id_client,
            $data->id_type_pret,
            $data->id_etablissement,
            $data->id_statut,
            $data->id_frequence,
            $data->montant,
            $data->date_debut,
            $data->date_fin,
            $data->taux_applique,
            $data->montant_restant,
            $data->date_creation,
        ]);
        return $db->lastInsertId();
    }

public static function update($id, $data) {
    $db = getDB();
    $stmt = $db->prepare("UPDATE Pret SET 
        id_client = ?,
        id_type_pret = ?,
        id_etablissement = ?,
        id_statut = ?,
        id_frequence = ?,
        montant = ?,
        date_debut = ?,
        date_fin = ?,
        taux_applique = ?,
        montant_restant = ?
        WHERE id_pret = ?");
    $stmt->execute([
        $data->id_client,
        $data->id_type_pret,
        $data->id_etablissement,
        $data->id_statut,
        $data->id_frequence,
        $data->montant,
        $data->date_debut,
        $data->date_fin,
        $data->taux_applique,
        $data->montant_restant,
        $id
    ]);
}

public static function delete($id) {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM Pret WHERE id_pret = ?");
    $stmt->execute([$id]);
}
}