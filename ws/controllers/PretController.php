<?php


require_once 'models/PretModel.php';

class PretController
{
    private $model;

    public function __construct()
    {
        $this->model = new PretModel();
    }

    /**
     * Affiche la page de liste des prêts
     */
    public function afficherListePrets()
    {
        Flight::render('prets', [
            'title' => 'Liste des Prêts - EF Dashboard'
        ]);
    }

    /**
     * API pour récupérer tous les prêts
     */
    public function getAllPrets()
    {
        try {
            $prets = $this->model->getAllPrets();
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
    public function getDetailsPret()
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

            $pret = $this->model->getPretById($id);
            if (!$pret) {
                Flight::json([
                    'success' => false,
                    'error' => 'Prêt non trouvé'
                ], 404);
                return;
            }

            $tableauAmortissement = $this->model->getTableauAmortissement($id);
            $statistiques = $this->model->getStatistiquesPret($id);

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
    public function genererPDF()
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

            $pret = $this->model->getPretById($id);
            if (!$pret) {
                Flight::json([
                    'success' => false,
                    'error' => 'Prêt non trouvé'
                ], 404);
                return;
            }

            $tableauAmortissement = $this->model->getTableauAmortissement($id);

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
                $pdf->Cell(30, 8, 'Date', 1, 1, 'C');

                // Lignes du tableau
                $pdf->SetFont('Arial', '', 9);
                foreach ($tableauAmortissement as $echeance) {
                    $pdf->Cell(20, 6, $echeance['numero_mois'], 1, 0, 'C');
                    $pdf->Cell(40, 6, number_format($echeance['capital_restant'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(35, 6, number_format($echeance['interets_mois'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(40, 6, number_format($echeance['mensualite_constante'] - $echeance['interets_mois'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(35, 6, number_format($echeance['mensualite_constante'], 0, ',', ' '), 1, 0, 'R');
                    $pdf->Cell(30, 6, date('d/m/Y', strtotime($echeance['date_mois'])), 1, 1, 'C');
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

    /**
     * Génère le contenu HTML pour le PDF
     */
    private function genererHTMLPourPDF($pret, $tableauAmortissement, $statistiques)
    {
        $html = '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Contrat de Prêt #' . $pret['id'] . '</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .section { margin-bottom: 20px; }
                .section h3 { background-color: #f0f0f0; padding: 10px; margin: 0 0 10px 0; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .amount { font-weight: bold; color: #2c5aa0; }
                .print-button { margin: 20px 0; text-align: center; }
                @media print { .print-button { display: none; } }
            </style>
            <script>
                window.onload = function() {
                    // Lancer automatiquement l\'impression/téléchargement PDF après 500ms
                    setTimeout(function() {
                        window.print();
                    }, 500);
                };
            </script>
        </head>
        <body>
            <div class="header">
                <h1>ÉTABLISSEMENT FINANCIER</h1>
                <h2>Contrat de Prêt #' . $pret['id'] . '</h2>
                <p>Date: ' . date('d/m/Y') . '</p>
            </div>

            <div class="print-button">
                <button onclick="window.print()" style="background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Imprimer / Sauvegarder en PDF
                </button>
                <button onclick="window.close()" style="background-color: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                    Fermer
                </button>
            </div>

            <div class="section">
                <h3>Informations du Client</h3>
                <table>
                    <tr><td><strong>Nom:</strong></td><td>' . $pret['client_nom'] . ' ' . $pret['client_prenom'] . '</td></tr>
                    <tr><td><strong>Email:</strong></td><td>' . $pret['client_mail'] . '</td></tr>
                    <tr><td><strong>Date de naissance:</strong></td><td>' . date('d/m/Y', strtotime($pret['date_naissance'])) . '</td></tr>
                    <tr><td><strong>Profession:</strong></td><td>' . ($pret['profession'] ?? 'Non renseignée') . '</td></tr>
                    <tr><td><strong>Revenu mensuel:</strong></td><td class="amount">' . number_format($pret['revenu_mensuel'] ?? 0, 2, ',', ' ') . ' Ar</td></tr>
                </table>
            </div>

            <div class="section">
                <h3>Détails du Prêt</h3>
                <table>
                    <tr><td><strong>Type de prêt:</strong></td><td>' . $pret['type_pret'] . '</td></tr>
                    <tr><td><strong>Montant emprunté:</strong></td><td class="amount">' . number_format($pret['montant'], 2, ',', ' ') . ' Ar</td></tr>
                    <tr><td><strong>Taux d\'intérêt:</strong></td><td>' . $pret['taux'] . '%</td></tr>
                    <tr><td><strong>Durée:</strong></td><td>' . $pret['duree'] . ' mois</td></tr>
                    <tr><td><strong>Type de remboursement:</strong></td><td>' . $pret['type_remboursement'] . '</td></tr>
                </table>
            </div>

            <div class="section">
                <h3>Résumé Financier</h3>
                <table>
                    <tr><td><strong>Total des intérêts:</strong></td><td class="amount">' . number_format($statistiques['total_interets'], 2, ',', ' ') . ' Ar</td></tr>
                    <tr><td><strong>Nombre d\'échéances:</strong></td><td>' . $statistiques['nombre_echeances'] . '</td></tr>
                    <tr><td><strong>Première échéance:</strong></td><td>' . date('d/m/Y', strtotime($statistiques['premiere_echeance'])) . '</td></tr>
                    <tr><td><strong>Dernière échéance:</strong></td><td>' . date('d/m/Y', strtotime($statistiques['derniere_echeance'])) . '</td></tr>
                </table>
            </div>';

        if (!empty($tableauAmortissement)) {
            $html .= '<div class="section">
                <h3>Tableau d\'Amortissement</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Capital Restant</th>
                            <th>Intérêts</th>
                            <th>Mensualité</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($tableauAmortissement as $echeance) {
                $html .= '<tr>
                    <td>' . $echeance['numero_mois'] . '</td>
                    <td class="amount">' . number_format($echeance['capital_restant'], 2, ',', ' ') . ' Ar</td>
                    <td class="amount">' . number_format($echeance['interets_mois'], 2, ',', ' ') . ' Ar</td>
                    <td class="amount">' . number_format($echeance['mensualite_constante'], 2, ',', ' ') . ' Ar</td>
                    <td>' . date('d/m/Y', strtotime($echeance['date_mois'])) . '</td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        $html .= '<div class="section" style="margin-top: 50px; border-top: 1px solid #333; padding-top: 20px;">
                <p><strong>Signatures:</strong></p>
                <table style="border: none;">
                    <tr style="border: none;">
                        <td style="border: none; width: 50%; text-align: center;">
                            <br><br><br>
                            _________________________<br>
                            Signature du Client
                        </td>
                        <td style="border: none; width: 50%; text-align: center;">
                            <br><br><br>
                            _________________________<br>
                            Signature de l\'EF
                        </td>
                    </tr>
                </table>
            </div>

        </body>
        </html>';

        return $html;
    }
}
