<?php
require_once __DIR__ . '/../db.php';

class EtablissementFinancier {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM EtablissementFinancier");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM EtablissementFinancier WHERE id_etablissement = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        error_log("DEBUG: EtablissementFinancier Model - Données reçues pour CREATE: " . print_r($data, true)); // DEBUG
        $stmt = $db->prepare("INSERT INTO EtablissementFinancier (nom_etablissement, adresse, telephone, email, date_creation, solde) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data->nom_etablissement, $data->adresse, $data->telephone, $data->email, $data->date_creation, $data->solde]);
        error_log("DEBUG: EtablissementFinancier Model - Requête INSERT exécutée. Last ID: " . $db->lastInsertId()); // DEBUG
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        error_log("DEBUG: EtablissementFinancier Model - Appel de update pour ID: " . $id); // DEBUG
        error_log("DEBUG: EtablissementFinancier Model - Données pour la mise à jour: " . print_r($data, true)); // DEBUG

        // Vérification explicite des propriétés
        error_log("DEBUG: EtablissementFinancier Model - data->nom_etablissement: " . (isset($data->nom_etablissement) ? $data->nom_etablissement : 'NON DÉFINI')); // DEBUG
        error_log("DEBUG: EtablissementFinancier Model - data->solde: " . (isset($data->solde) ? $data->solde : 'NON DÉFINI')); // DEBUG
        error_log("DEBUG: EtablissementFinancier Model - data->date_creation: " . (isset($data->date_creation) ? $data->date_creation : 'NON DÉFINI')); // DEBUG


        // Correction: Assurez-vous que date_creation est incluse dans l'UPDATE
        $stmt = $db->prepare("UPDATE EtablissementFinancier SET nom_etablissement = ?, adresse = ?, telephone = ?, email = ?, date_creation = ?, solde = ? WHERE id_etablissement = ?");
        $stmt->execute([
            $data->nom_etablissement,
            $data->adresse,
            $data->telephone,
            $data->email,
            $data->date_creation, // Ajouté
            $data->solde,
            $id
        ]);

        error_log("DEBUG: EtablissementFinancier Model - Requête UPDATE exécutée."); // DEBUG
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM EtablissementFinancier WHERE id_etablissement = ?");
        $stmt->execute([$id]);
    }

    public static function addFunds($id_etablissement, $montant, $description = null) {
        $db = getDB();
        try {
            $db->beginTransaction();

            // Mettre à jour le solde de l'établissement financier
            $stmt = $db->prepare("UPDATE EtablissementFinancier SET solde = solde + ? WHERE id_etablissement = ?");
            $stmt->execute([$montant, $id_etablissement]);

            // Enregistrer l'opération dans OperationFonds (ou HistoriqueMouvement selon votre schéma)
            // Récupérer l'ID du type d'opération 'DEPOT'
            $stmtType = $db->prepare("SELECT id_type_operation FROM TypeOperation WHERE code_type = 'DEPOT'");
            $stmtType->execute();
            $typeOperation = $stmtType->fetch(PDO::FETCH_ASSOC);

            if ($typeOperation) {
                $id_type_operation = $typeOperation['id_type_operation'];
                // Assurez-vous que le nom de table est correct, ici j'utilise 'OperationFonds'
                // Si vous avez renommé la table ou utilisez 'HistoriqueMouvement', adaptez ici.
                $stmtOperation = $db->prepare("INSERT INTO HistoriqueMouvement (id_etablissement, id_type_operation, montant, description) VALUES (?, ?, ?, ?)"); // Correction: Utilisation de HistoriqueMouvement
                $stmtOperation->execute([$id_etablissement, $id_type_operation, $montant, $description]);
            } else {
                throw new Exception("Type d'opération 'DEPOT' non trouvé.");
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("Erreur lors de l'ajout de fonds: " . $e->getMessage());
            return false;
        }
    }
}
