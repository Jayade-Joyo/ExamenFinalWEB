<?php
require_once __DIR__.'/../../vendor/fpdf/fpdf.php';

class PDFGenerator extends FPDF {
    public function generatePretPDF($pret, $remboursements) {
        $this->AddPage();
        $this->SetFont('Arial', 'B', 16);
        
        // En-tête
        $this->Cell(0, 10, 'Détails du prêt #'.$pret['id_pret'], 0, 1);
        $this->Ln(10);
        
        // Informations client
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Client: '.$pret['client_prenom'].' '.$pret['client_nom'], 0, 1);
        $this->SetFont('Arial', '', 12);
        
        // Détails du prêt
        $this->Cell(50, 10, 'Montant total:', 0);
        $this->Cell(0, 10, number_format($pret['montant'], 2).' €', 0, 1);
        $this->Cell(50, 10, 'Taux appliqué:', 0);
        $this->Cell(0, 10, $pret['taux_applique'].'%', 0, 1);
        $this->Cell(50, 10, 'Période:', 0);
        $this->Cell(0, 10, 'Du '.$pret['date_debut'].' au '.$pret['date_fin'], 0, 1);
        $this->Cell(50, 10, 'Montant restant:', 0);
        $this->Cell(0, 10, number_format($pret['montant_restant'], 2).' €', 0, 1);
        $this->Ln(15);
        
        // Tableau des remboursements
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Plan de remboursement:', 0, 1);
        $this->SetFont('Arial', '', 10);
        
        // En-têtes du tableau
        $this->SetFillColor(200, 220, 255);
        $this->Cell(30, 10, 'Date', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Total', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Intérêts', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Capital', 1, 0, 'C', true);
        $this->Cell(40, 10, 'Capital restant', 1, 1, 'C', true);
        
        // Contenu du tableau
        $this->SetFillColor(255, 255, 255);
        foreach ($remboursements as $remb) {
            $this->Cell(30, 10, $remb['date_echeance'], 1);
            $this->Cell(40, 10, number_format($remb['annuite'], 2).' €', 1);
            $this->Cell(40, 10, number_format($remb['interet'], 2).' €', 1);
            $this->Cell(40, 10, number_format($remb['amortissement'], 2).' €', 1);
            $this->Cell(40, 10, number_format($remb['val_fin'], 2).' €', 1);
            $this->Ln();
        }
        
        // Pied de page
        $this->Ln(10);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Généré le '.date('d/m/Y à H:i'), 0, 0, 'R');
        
        return $this->Output('S'); // Retourne le PDF sous forme de chaîne
    }
}