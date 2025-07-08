<?php
// models/GestionPret.php - Modèle pour la gestion des prêts
require_once __DIR__ . '/../db.php';

class GestionPret {
    private $db;
    
    public function __construct() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=banque", "root", "");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Erreur de connexion à la base de données : " . $e->getMessage());
            throw new Exception("Erreur de connexion à la base de données");
        }
    }
    
    public function calculerMontantRestant($montant, $taux_annuel, $taux_assurance, $date_debut, $date_fin) {
        try {
            $debut = new DateTime($date_debut);
            $fin = new DateTime($date_fin);
            $interval = $debut->diff($fin);
            $duree_mois = ($interval->y * 12) + $interval->m;
            
            if ($duree_mois <= 0) {
                return $montant;
            }
            
            $taux_mensuel = $taux_annuel / 100 / 12;
            
            if ($taux_mensuel == 0) {
                return $montant;
            }
            
            $annuite = $montant * $taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree_mois));
            $montant_interets = $annuite * $duree_mois - $montant;
            $duree_annees = $duree_mois / 12;
            $cout_assurance = $montant * ($taux_assurance / 100) * $duree_annees;
            $montant_total = $montant + $montant_interets + $cout_assurance;
            
            return round($montant_total, 2);
        } catch (Exception $e) {
            error_log("Erreur dans calculerMontantRestant : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function calculerDureeMois($date_debut, $date_fin) {
        try {
            $debut = new DateTime($date_debut);
            $fin = new DateTime($date_fin);
            $interval = $debut->diff($fin);
            return ($interval->y * 12) + $interval->m;
        } catch (Exception $e) {
            error_log("Erreur dans calculerDureeMois : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getTousLesPrets() {
        try {
            $sql = "SELECT 
                        p.id_pret,
                        CONCAT(c.nom, ' ', c.prenom) as client_nom,
                        tp.nom_type,
                        p.montant,
                        p.montant_restant,
                        p.date_debut,
                        p.date_fin,
                        p.taux_applique,
                        p.taux_Assurance,
                        sp.libelle as statut,
                        ef.nom_etablissement
                    FROM Pret p
                    JOIN Client c ON p.id_client = c.id_client
                    JOIN TypePret tp ON p.id_type_pret = tp.id_type_pret
                    JOIN StatutPret sp ON p.id_statut = sp.id_statut
                    JOIN EtablissementFinancier ef ON p.id_etablissement = ef.id_etablissement
                    ORDER BY p.date_creation DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Erreur dans getTousLesPrets : " . $e->getMessage());
            return [];
        }
    }
    
    public function creerPret($data) {
        try {
            if ($data['taux_applique'] <= 0) {
                throw new Exception("Le taux appliqué doit être supérieur à 0.");
            }
            if ($data['taux_Assurance'] < 0) {
                throw new Exception("Le taux d'assurance ne peut pas être négatif.");
            }
            $duree_mois = $this->calculerDureeMois($data['date_debut'], $data['date_fin']);
            if ($duree_mois <= 0) {
                throw new Exception("La date de fin doit être postérieure à la date de début.");
            }
            
            $montant_restant = $this->calculerMontantRestant(
                $data['montant'],
                $data['taux_applique'],
                $data['taux_Assurance'],
                $data['date_debut'],
                $data['date_fin']
            );
            
            $sql = "INSERT INTO Pret (id_client, id_type_pret, id_etablissement, id_statut, 
                                      montant, date_debut, date_fin, taux_applique, taux_Assurance, montant_restant)
                    VALUES (:id_client, :id_type_pret, :id_etablissement, :id_statut,
                            :montant, :date_debut, :date_fin, :taux_applique, :taux_Assurance, :montant_restant)";
            
            $stmt = $this->db->prepare($sql);
            $data['montant_restant'] = $montant_restant;
            
            return $stmt->execute($data);
        } catch (Exception $e) {
            error_log("Erreur dans creerPret : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function updateStatutPret($id_pret, $nouveau_statut) {
        try {
            $sql = "UPDATE Pret SET id_statut = :statut WHERE id_pret = :id_pret";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['statut' => $nouveau_statut, 'id_pret' => $id_pret]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateStatutPret : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function calculerTableauAmortissement($montant, $taux_annuel, $taux_assurance, $duree_mois) {
        try {
            $taux_mensuel = $taux_annuel / 100 / 12;
            $annuite = $montant * $taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree_mois));
            $cout_assurance_mensuel = ($montant * ($taux_assurance / 100)) / 12;
            
            $tableau = [];
            $capital_restant = $montant;
            
            for ($mois = 1; $mois <= $duree_mois; $mois++) {
                $interet = $capital_restant * $taux_mensuel;
                $amortissement = $annuite - $interet;
                $capital_restant -= $amortissement;
                
                $tableau[] = [
                    'mois' => $mois,
                    'base' => $capital_restant + $amortissement,
                    'interet' => round($interet, 2),
                    'amortissement' => round($amortissement, 2),
                    'annuite' => round($annuite + $cout_assurance_mensuel, 2),
                    'assurance' => round($cout_assurance_mensuel, 2),
                    'val_fin' => round($capital_restant, 2)
                ];
            }
            
            return $tableau;
        } catch (Exception $e) {
            error_log("Erreur dans calculerTableauAmortissement : " . $e->getMessage());
            throw $e;
        }
    }
    
    public function getClients() {
        try {
            $sql = "SELECT id_client, CONCAT(nom, ' ', prenom) as nom_complet FROM Client ORDER BY nom";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Erreur dans getClients : " . $e->getMessage());
            return [];
        }
    }
    
    public function getTypesPret() {
        try {
            $sql = "SELECT * FROM TypePret WHERE actif = TRUE AND taux_interet > 0 ORDER BY nom_type";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Erreur dans getTypesPret : " . $e->getMessage());
            return [];
        }
    }
    
    public function getEtablissements() {
        try {
            $sql = "SELECT * FROM EtablissementFinancier ORDER BY nom_etablissement";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Erreur dans getEtablissements : " . $e->getMessage());
            return [];
        }
    }
    
    public function getStatutsPret() {
        try {
            $sql = "SELECT * FROM StatutPret ORDER BY id_statut";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("Erreur dans getStatutsPret : " . $e->getMessage());
            return [];
        }
    }
    
    public function previsualiserPret($data) {
        try {
            $montant_restant = $this->calculerMontantRestant(
                $data['montant'],
                $data['taux_applique'],
                $data['taux_Assurance'],
                $data['date_debut'],
                $data['date_fin']
            );
            
            $duree_mois = $this->calculerDureeMois($data['date_debut'], $data['date_fin']);
            $interets_totaux = $montant_restant - $data['montant'];
            $cout_assurance = ($data['montant'] * ($data['taux_Assurance'] / 100) * ($duree_mois / 12));
            $mensualite = $duree_mois > 0 ? $montant_restant / $duree_mois : 0;
            
            return [
                'montant_initial' => $data['montant'],
                'montant_total' => $montant_restant,
                'interets_totaux' => $interets_totaux - $cout_assurance,
                'cout_assurance' => $cout_assurance,
                'duree_mois' => $duree_mois,
                'mensualite_moyenne' => round($mensualite, 2)
            ];
        } catch (Exception $e) {
            error_log("Erreur dans previsualiserPret : " . $e->getMessage());
            throw $e;
        }
    }
}
?>