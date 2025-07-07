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
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO EtablissementFinancier (nom_etablissement, adresse, telephone, email, date_creation, solde) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data->nom_etablissement,
                $data->adresse,
                $data->telephone,
                $data->email,
                $data->date_creation,
                $data->solde
            ]);
            $id_etablissement = $db->lastInsertId();

            if (!empty($data->montant_depot) && $data->montant_depot > 0) {
                $stmt = $db->prepare("INSERT INTO OperationFonds (id_etablissement, id_type_operation, montant, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $id_etablissement,
                    1, // Assuming id_type_operation = 1 for 'DEPOT' from TypeOperation
                    $data->montant_depot,
                    'Dépôt initial'
                ]);

                $stmt = $db->prepare("UPDATE EtablissementFinancier SET solde = solde + ? WHERE id_etablissement = ?");
                $stmt->execute([$data->montant_depot, $id_etablissement]);
            }

            $db->commit();
            return $id_etablissement;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function update($id, $data) {
        $db = getDB();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("UPDATE EtablissementFinancier SET nom_etablissement = ?, adresse = ?, telephone = ?, email = ?, date_creation = ?, solde = ? WHERE id_etablissement = ?");
            $stmt->execute([
                $data->nom_etablissement,
                $data->adresse,
                $data->telephone,
                $data->email,
                $data->date_creation,
                $data->solde,
                $id
            ]);

            if (!empty($data->montant_depot) && $data->montant_depot > 0) {
                $stmt = $db->prepare("INSERT INTO OperationFonds (id_etablissement, id_type_operation, montant, description) VALUES (?, ?, ?, ?)");
                $stmt->execute([
                    $id,
                    1, // Assuming id_type_operation = 1 for 'DEPOT'
                    $data->montant_depot,
                    'Dépôt supplémentaire'
                ]);

                $stmt = $db->prepare("UPDATE EtablissementFinancier SET solde = solde + ? WHERE id_etablissement = ?");
                $stmt->execute([$data->montant_depot, $id]);
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function delete($id) {
        $db = getDB();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("DELETE FROM OperationFonds WHERE id_etablissement = ?");
            $stmt->execute([$id]);

            $stmt = $db->prepare("DELETE FROM EtablissementFinancier WHERE id_etablissement = ?");
            $stmt->execute([$id]);

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}