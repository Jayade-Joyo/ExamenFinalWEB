<?php
require_once __DIR__ . '/../models/Remboursement.php';
require_once __DIR__ . '/../helpers/Utils.php';

class RemboursementController {
    public static function getAll() {
        $remboursements = Remboursement::getAll();
        Flight::json($remboursements);
    }

    public static function getById($id) {
        $remboursement = Remboursement::getById($id);
        Flight::json($remboursement);
    }

    public static function getByPretId($pretId) {
        $remboursements = Remboursement::getByPretId($pretId);
        Flight::json($remboursements);
    }

    public static function getByMoisAnnee($mois, $annee) {
        $remboursements = Remboursement::getByMoisAnnee($mois, $annee);
        Flight::json($remboursements);
    }

    public static function create() {
        $data = Flight::request()->data;
        
        // Validation des données
        if (!isset($data->id_pret) || !isset($data->base) || !isset($data->interet) || 
            !isset($data->amortissement) || !isset($data->annuite) || !isset($data->val_fin) ||
            !isset($data->date_echeance)) {
            Flight::halt(400, json_encode(['message' => 'Données incomplètes']));
            return;
        }

        // Formatage des dates si nécessaire
        $data->date_echeance = Utils::formatDate($data->date_echeance);
        
        $id = Remboursement::create($data);
        Flight::json([
            'message' => 'Remboursement enregistré avec succès',
            'id_remboursement' => $id
        ]);
    }

    public static function update($id) {
        $data = Flight::request()->data;
        
        if (!isset($data->id_statut) || !isset($data->base) || !isset($data->interet) || 
            !isset($data->amortissement) || !isset($data->annuite) || !isset($data->val_fin)) {
            Flight::halt(400, json_encode(['message' => 'Données incomplètes']));
            return;
        }

        Remboursement::update($id, $data);
        Flight::json(['message' => 'Remboursement mis à jour']);
    }

    public static function updateStatut($id) {
        $data = Flight::request()->data;
        
        if (!isset($data->id_statut)) {
            Flight::halt(400, json_encode(['message' => 'Statut manquant']));
            return;
        }

        Remboursement::updateStatut($id, $data->id_statut);
        Flight::json(['message' => 'Statut du remboursement mis à jour']);
    }

    public static function delete($id) {
        Remboursement::delete($id);
        Flight::json(['message' => 'Remboursement supprimé']);
    }

    public static function getByPeriode($mois_debut, $annee_debut, $mois_fin, $annee_fin) {
        // Validation des paramètres
        if (!is_numeric($mois_debut) || !is_numeric($annee_debut) || 
            !is_numeric($mois_fin) || !is_numeric($annee_fin)) {
            Flight::halt(400, json_encode(['message' => 'Paramètres invalides']));
            return;
        }
        
        // Validation des mois (1-12)
        if ($mois_debut < 1 || $mois_debut > 12 || $mois_fin < 1 || $mois_fin > 12) {
            Flight::halt(400, json_encode(['message' => 'Mois invalide (doit être entre 1 et 12)']));
            return;
        }
        
        $remboursements = Remboursement::getByPeriode($mois_debut, $annee_debut, $mois_fin, $annee_fin);
        Flight::json($remboursements);
    }

    

}