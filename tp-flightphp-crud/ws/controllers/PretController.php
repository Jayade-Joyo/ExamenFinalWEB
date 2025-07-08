<?php
// Inclure le modèle TypePret
require_once __DIR__ . '/../models/Pret.php';

/**
 * Classe TypePretController
 * Gère les requêtes HTTP pour les opérations sur les types de prêt.
 * Cette version suit strictement la structure de la table TypePret de banque.sql (sans date_creation).
 */
class PretController {
    public function getPretDetails($pretId) {
        try {
            $pret = $this->model->getPretWithDetails($pretId);
            Flight::json($pret);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getByClientId($clientId) {
        $prets = Pret::getByClientId($clientId);
        Flight::json($prets);
    }
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

    public function generatePDF($pretId) {
        try {
            // Récupérer les données du prêt
            $pret = $this->model->getPretWithDetails($pretId);
            
            // Récupérer les remboursements
            $remboursements = RemboursementModel::getByPretId($pretId);
            
            // Générer le PDF
            $pdf = new PDFGenerator();
            $pdfContent = $pdf->generatePretPDF($pret, $remboursements);
            
            // Retourner le PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="pret_'.$pretId.'.pdf"');
            echo $pdfContent;
            exit;
            
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

//     public static function simulerRemboursement($id_pret) {
//     try {
//         $resultat = Pret::genererEcheancier($id_pret); // ou autre fonction
//         Flight::json($resultat);
//     } catch (Exception $e) {
//         Flight::halt(500, json_encode(['error' => $e->getMessage()]));
//     }
// }

    public static function simulerRemboursement($id_pret) {
        $pret = Pret::getById($id_pret); // Assurez-vous que cette méthode existe et fonctionne

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

        if ($nb_mois <= 0) {
            Flight::halt(400, json_encode(['message' => 'La période du prêt est invalide']));
            return;
        }

        // Formule annuité constante
        $annuite = round($capital * ($taux_mensuel / (1 - pow(1 + $taux_mensuel, -$nb_mois))), 2);
        $base = $capital;
        $remboursements = [];

        for ($i = 0; $i < $nb_mois; $i++) {
            $interet = round($base * $taux_mensuel, 2);
            $amortissement = round($annuite - $interet, 2);
            $val_fin = round($base - $amortissement, 2);

            $date_echeance = clone $date_debut;
            $date_echeance->modify("+$i months");

            $mois = (int)$date_echeance->format('n');
            $annee = (int)$date_echeance->format('Y');

            $remboursements[] = [
                'mois' => $mois,
                'annee' => $annee,
                'base' => $base,
                'interet' => $interet,
                'amortissement' => $amortissement,
                'annuite' => $annuite,
                'val_fin' => $val_fin,
                'date_echeance' => $date_echeance->format('Y-m-d')
            ];

            $base = $val_fin;
        }

        Flight::json([
            'pret' => $pret,
            'remboursements' => $remboursements
        ]);
    }


    public static function genererEcheancierConstante($id_pret) {
        $pret = Pret::getById($id_pret); // Assurez-vous que la méthode existe

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

        // Formule annuité constante : a = C × [i / (1 - (1 + i)^-n)]
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
                'id_statut' => 1, // Paiement prévu
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

        Flight::json(['message' => 'Échéancier mensuel généré avec succès']);
    }


}