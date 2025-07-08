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

   
    public static function simulerEtEnregistrer($id_pret) {
    $pret = Pret::getById($id_pret);
    if (!$pret) {
        Flight::halt(404, json_encode(['message' => 'Prêt introuvable']));
        return;
    }

    $capital = $pret['montant'];
    $taux_annuel = $pret['taux_applique'] / 100;
    $taux_mensuel = $taux_annuel / 12;

    $date_debut = new DateTime($pret['date_debut']);
    $date_fin = new DateTime($pret['date_fin']);
    $diff = $date_debut->diff($date_fin);
    $nb_mois = $diff->y * 12 + $diff->m;

    $annuite = round($capital * ($taux_mensuel / (1 - pow(1 + $taux_mensuel, -$nb_mois))), 2);
    $base = $capital;

    for ($i = 0; $i < $nb_mois; $i++) {
        $interet = round($base * $taux_mensuel, 2);
        $amortissement = round($annuite - $interet, 2);
        $val_fin = round($base - $amortissement, 2);

        $date_echeance = clone $date_debut;
        $date_echeance->modify("+$i months");
        $mois = (int)$date_echeance->format('n');
        $annee = (int)$date_echeance->format('Y');

        Remboursement::create((object)[
            'id_pret' => $id_pret,
            'id_statut' => 1, // Statut initial
            'base' => $base,
            'interet' => $interet,
            'amortissement' => $amortissement,
            'annuite' => $annuite,
            'val_fin' => $val_fin,
            'date_echeance' => $date_echeance->format('Y-m-d'),
            'mois' => $mois,
            'annee' => $annee
        ]);

        $base = $val_fin;
    }

    Flight::json(['message' => 'Échéancier généré et enregistré avec succès']);
}



}