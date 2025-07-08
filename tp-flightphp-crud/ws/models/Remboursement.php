<?php
require_once __DIR__ . '/../db.php';

class Remboursement {
    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM remboursement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Remboursement WHERE id_remboursement = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByPretId($pretId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Remboursement WHERE id_pret = ? ORDER BY date_echeance");
        $stmt->execute([$pretId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public static function create($data) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Remboursement 
                            (id_pret, id_statut, base, interet, amortissement, annuite, val_fin, 
                             date_echeance, mois, annee)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data->id_pret,
            $data->id_statut ?? 1, // Valeur par défaut
            $data->base,
            $data->interet,
            $data->amortissement,
            $data->annuite,
            $data->val_fin,
            $data->date_echeance,
            $data->mois,
            $data->annee
        ]);
        
        return $db->lastInsertId();
    }

    public static function update($id, $data) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE Remboursement SET 
                            id_statut = ?,
                            base = ?,
                            interet = ?,
                            amortissement = ?,
                            annuite = ?,
                            val_fin = ?,
                            date_echeance = ?,
                            mois = ?,
                            annee = ?
                            WHERE id_remboursement = ?");
        
        $stmt->execute([
            $data->id_statut,
            $data->base,
            $data->interet,
            $data->amortissement,
            $data->annuite,
            $data->val_fin,
            $data->date_echeance,
            $data->mois,
            $data->annee,
            $id
        ]);
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM Remboursement WHERE id_remboursement = ?");
        $stmt->execute([$id]);
    }

    public static function getByMoisAnnee($mois, $annee) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM Remboursement WHERE mois = ? AND annee = ?");
        $stmt->execute([$mois, $annee]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatut($id, $statut) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE Remboursement SET id_statut = ? WHERE id_remboursement = ?");
        $stmt->execute([$statut, $id]);
    }

    public static function getByPeriode($mois_debut, $annee_debut, $mois_fin, $annee_fin) {
        $db = getDB();
        
        // Construire la requête SQL pour filtrer par période
        $sql = "SELECT * FROM remboursement 
                WHERE (annee > ? OR (annee = ? AND mois >= ?))
                AND (annee < ? OR (annee = ? AND mois <= ?))
                ORDER BY annee, mois, date_echeance";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $annee_debut, $annee_debut, $mois_debut,  // Condition début
            $annee_fin, $annee_fin, $mois_fin         // Condition fin
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getInteretsParMois() {
        $db = getDB();
        $sql = "SELECT 
                    mois, 
                    annee, 
                    SUM(interet) as total_interets,
                    COUNT(*) as nombre_remboursements
                FROM remboursement 
                GROUP BY annee, mois 
                ORDER BY annee, mois";
        
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getInteretsParMoisPeriode($mois_debut, $annee_debut, $mois_fin, $annee_fin) {
        $db = getDB();
        $sql = "SELECT 
                    mois, 
                    annee, 
                    SUM(interet) as total_interets,
                    COUNT(*) as nombre_remboursements
                FROM remboursement 
                WHERE (annee > ? OR (annee = ? AND mois >= ?))
                AND (annee < ? OR (annee = ? AND mois <= ?))
                GROUP BY annee, mois 
                ORDER BY annee, mois";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $annee_debut, $annee_debut, $mois_debut,
            $annee_fin, $annee_fin, $mois_fin
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

}