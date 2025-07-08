<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController
{
    private static function parseRequestData()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST') {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') !== false) {
                $rawData = file_get_contents("php://input");
                $data = json_decode($rawData, false);
                return $data ?: (object)[];
            } else {
                return (object) $_POST;
            }
        } else if ($method === 'PUT') {
            $rawData = file_get_contents("php://input");
            $data = [];
            parse_str($rawData, $data);
            return (object) $data;
        }
        return (object) [];
    }

    public static function create()
    {
        $data = self::parseRequestData();
        try {
            $id = Pret::create($data);
            Flight::json(['message' => 'Prêt ajouté', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de l\'ajout du prêt : ' . $e->getMessage()], 500);
        }
    }

    /**
     * Affiche la page de liste des prêts
     */
    public static function afficherListePrets()
    {
        Flight::render('prets', [
            'title' => 'Liste des Prêts - EF Dashboard'
        ]);
    }

    /**
     * API pour récupérer tous les prêts
     */
    public static function getAllPrets()
    {
        try {
            $prets = Pret::getAllWithDetails();
            Flight::json([
                'success' => true,
                'data' => $prets
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API pour récupérer les détails d'un prêt
     */
    public static function getDetailsPret()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                Flight::json([
                    'success' => false,
                    'error' => 'ID du prêt requis'
                ], 400);
                return;
            }

            $pret = Pret::getById($id);
            if (!$pret) {
                Flight::json([
                    'success' => false,
                    'error' => 'Prêt non trouvé'
                ], 404);
                return;
            }

            $tableauAmortissement = Pret::getTableauAmortissement($id);
            $statistiques = Pret::getStatistiquesPret($id);

            Flight::json([
                'success' => true,
                'data' => [
                    'pret' => $pret,
                    'tableau_amortissement' => $tableauAmortissement,
                    'statistiques' => $statistiques
                ]
            ]);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un PDF pour un prêt
     */
    public static function genererPDF()
    {
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                Flight::json([
                    'success' => false,
                    'error' => 'ID du prêt requis'
                ], 400);
                return;
            }

            $pret = Pret::getById($id);
            if (!$pret) {
                Flight::json([
                    'success' => false,
                    'error' => 'Prêt non trouvé'
                ], 404);
                return;
            }

            $tableauAmortissement = Pret::getTableauAmortissement($id);

            // Inclure FPDF
            require_once(__DIR__ . '/../../fpdf/fpdf.php');

            // Créer le PDF
            $pdf = new FPDF();
            $pdf->AddPage();

            // En-tête du document
            $pdf->SetFont('Arial', 'B', 20);
            $pdf->Cell(0, 15, 'CONTRAT DE PRET', 0, 1, 'C');
            $pdf->Ln(10);

            // Informations du prêt
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Informations du Pret', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell(50, 8, 'Numero de pret :', 0, 0);
            $pdf->Cell(0, 8, '#' . str_pad($pret['id'], 6, '0', STR_PAD_LEFT), 0, 1);

            $pdf->Cell(50, 8, 'Date de creation :', 0, 0);
            $pdf->Cell(0, 8, date('d/m/Y'), 0, 1);

            $pdf->Cell(50, 8, 'Montant :', 0, 0);
            $pdf->Cell(0, 8, number_format($pret['montant'], 2, ',', ' ') . ' Ar', 0, 1);

            $pdf->Cell(50, 8, 'Duree :', 0, 0);
            $pdf->Cell(0, 8, $pret['duree'] . ' mois', 0, 1);

            $pdf->Cell(50, 8, 'Taux d\'interet :', 0, 0);
            $pdf->Cell(0, 8, $pret['taux'] . '%', 0, 1);

            $pdf->Cell(50, 8, 'Type de pret :', 0, 0);
            $pdf->Cell(0, 8, $pret['type_pret'], 0, 1);

            $pdf->Ln(10);

            // Informations du client
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Informations du Client', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell(50, 8, 'Nom :', 0, 0);
            $pdf->Cell(0, 8, $pret['client_nom'] . ' ' . $pret['client_prenom'], 0, 1);

            $pdf->Cell(50, 8, 'Email :', 0, 0);
            $pdf->Cell(0, 8, $pret['client_mail'], 0, 1);

            $pdf->Ln(10);

            // Tableau d'amortissement
            if (!empty($tableauAmortissement)) {
                $pdf->SetFont('Arial', 'B', 14);
                $pdf->Cell(0, 10, 'Tableau d\'Amortissement', 0, 1);

                // En-têtes du tableau
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(20, 8, 'Mois', 1, 0, 'C');
                $pdf->Cell(40, 8, 'Capital Restant', 1, 0, 'C');
                $pdf->Cell(35, 8, 'Interets', 1, 0, 'C');
                $pdf->Cell(40, 8, 'Amortissement', 1, 0, 'C');
                $pdf->Cell(35, 8, 'Mensualite', 1, 0, 'C');
                $pdf->Cell(20, 8, 'Date', 1, 1, 'C');

                // Lignes du tableau
                $pdf->SetFont('Arial', '', 9);
                foreach ($tableauAmortissement as $echeance) {
                    $pdf->Cell(20, 6, $echeance['numero_mois'], 1, 0, 'C');
                    $pdf->Cell(40, 6, number_format($echeance['capital_restant'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(35, 6, number_format($echeance['interets_mois'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(40, 6, number_format($echeance['mensualite_constante'] - $echeance['interets_mois'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(35, 6, number_format($echeance['mensualite_constante'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(20, 6, date('m/y', strtotime($echeance['date_mois'])), 1, 1, 'C');
                }
                $pdf->Ln(10);
            }

            // Conditions générales
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'Conditions Generales', 0, 1);
            $pdf->SetFont('Arial', '', 10);
            $pdf->MultiCell(0, 5, "- Le remboursement s'effectue selon l'echeancier ci-dessus\n- Tout retard de paiement entrainera des penalites\n- En cas de defaut de paiement, l'etablissement se reserve le droit d'engager des poursuites");

            $pdf->Ln(15);

            // Signatures
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(95, 8, 'Signature du Client', 0, 0, 'C');
            $pdf->Cell(95, 8, 'Signature de l\'EF', 0, 1, 'C');
            $pdf->Ln(20);
            $pdf->Cell(95, 8, 'Date: _______________', 0, 0, 'C');
            $pdf->Cell(95, 8, 'Date: _______________', 0, 1, 'C');

            // Définir les en-têtes pour le téléchargement automatique
            $filename = 'Contrat_Pret_' . str_pad($pret['id'], 6, '0', STR_PAD_LEFT) . '.pdf';

            // Sortir le PDF directement en téléchargement
            $pdf->Output('D', $filename);
        } catch (Exception $e) {
            Flight::json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
