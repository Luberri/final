<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparaison des Simulations</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 1rem;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        h1 {
            font-size: 2rem;
            color: #1a202c;
            text-align: center;
            margin-bottom: 2rem;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            font-size: 0.875rem;
        }

        .comparison-table th,
        .comparison-table td {
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            text-align: left;
        }

        .comparison-table th {
            background-color: #edf2f7;
            font-weight: 600;
            color: #2d3748;
            position: sticky;
            top: 0;
        }

        .comparison-table tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        .metric-label {
            font-weight: 600;
            color: #4a5568;
            background-color: #f1f5f9 !important;
        }

        .best-value {
            background-color: #d4edda !important;
            color: #155724;
            font-weight: bold;
        }

        .worst-value {
            background-color: #f8d7da !important;
            color: #721c24;
        }

        .summary-section {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3182ce;
        }

        .summary-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .summary-item {
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            background-color: white;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .summary-label {
            font-weight: 500;
            color: #4a5568;
        }

        .summary-value {
            font-weight: bold;
            color: #2d3748;
        }

        .chart-container {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .back-button {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #6b7280;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 1rem;
            transition: background-color 0.2s;
        }

        .back-button:hover {
            background-color: #4b5563;
            color: white;
            text-decoration: none;
        }

        .simulation-header {
            text-align: center;
            font-weight: bold;
            color: #3182ce;
            background-color: #ebf4ff !important;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .comparison-table {
                font-size: 0.75rem;
            }

            .comparison-table th,
            .comparison-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <a href="javascript:history.back()" class="back-button">← Retour</a>
        
        <h1>Comparaison des Simulations</h1>

        <?php
        // Récupérer les données des simulations depuis POST
        $simulations = [];
        $count = $_POST['count'] ?? 0;

        for ($i = 0; $i < $count; $i++) {
            $simulation = [];
            foreach ($_POST as $key => $value) {
                if (strpos($key, "simulation_{$i}_") === 0) {
                    $actualKey = str_replace("simulation_{$i}_", "", $key);
                    $simulation[$actualKey] = $value;
                }
            }
            if (!empty($simulation)) {
                $simulations[] = $simulation;
            }
        }

        if (empty($simulations)) {
            echo '<p>Aucune simulation à comparer.</p>';
            exit;
        }

        // Fonction pour formater les montants
        function formatMontant($montant) {
            return number_format($montant, 2, ',', ' ') . ' Ar';
        }

        // Fonction pour trouver la meilleure valeur (plus petite pour les coûts)
        function findBestValue($values, $lowerIsBetter = true) {
            if ($lowerIsBetter) {
                return min($values);
            } else {
                return max($values);
            }
        }

        // Calculer les métriques pour chaque simulation
        $metrics = [];
        foreach ($simulations as $index => $sim) {
            $coutTotal = $sim['mensualite'] * $sim['duree'] + $sim['cout_total_assurance'];
            $metrics[$index] = [
                'cout_total' => $coutTotal,
                'cout_credit' => $coutTotal - $sim['montant'],
                'taux_effectif' => (($coutTotal - $sim['montant']) / $sim['montant']) * 100,
                'mensualite_totale' => $sim['mensualite'] + ($sim['cout_total_assurance'] / ($sim['duree'] + $sim['delai_premier']))
            ];
        }
        ?>

        <!-- Tableau de comparaison -->
        <div style="overflow-x: auto;">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="metric-label">Critères</th>
                        <?php foreach ($simulations as $index => $sim): ?>
                            <th class="simulation-header">
                                Simulation #<?= htmlspecialchars($sim['id']) ?><br>
                                <small><?= htmlspecialchars($sim['client_nom'] . ' ' . $sim['client_prenom']) ?></small>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Informations de base -->
                    <tr>
                        <td class="metric-label">Montant emprunté</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= formatMontant($sim['montant']) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Durée</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= htmlspecialchars($sim['duree']) ?> mois</td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Type de prêt</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= htmlspecialchars($sim['type_pret_nom']) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Taux mensuel</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= number_format($sim['taux_mensuel'] * 100, 2) ?>%</td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Coûts -->
                    <tr>
                        <td class="metric-label">Mensualité</td>
                        <?php 
                        $mensualites = array_column($simulations, 'mensualite');
                        $bestMensualite = findBestValue($mensualites, true);
                        foreach ($simulations as $sim): 
                            $iseBest = ($sim['mensualite'] == $bestMensualite);
                        ?>
                            <td class="<?= $iseBest ? 'best-value' : '' ?>">
                                <?= formatMontant($sim['mensualite']) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Total des intérêts</td>
                        <?php 
                        $totalInterets = array_column($simulations, 'total_interets');
                        $bestInterets = findBestValue($totalInterets, true);
                        foreach ($simulations as $sim): 
                            $isBest = ($sim['total_interets'] == $bestInterets);
                        ?>
                            <td class="<?= $isBest ? 'best-value' : '' ?>">
                                <?= formatMontant($sim['total_interets']) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Coût assurance</td>
                        <?php 
                        $coutAssurances = array_column($simulations, 'cout_total_assurance');
                        $bestAssurance = findBestValue($coutAssurances, true);
                        foreach ($simulations as $sim): 
                            $isBest = ($sim['cout_total_assurance'] == $bestAssurance);
                        ?>
                            <td class="<?= $isBest ? 'best-value' : '' ?>">
                                <?= formatMontant($sim['cout_total_assurance']) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Coût total du crédit</td>
                        <?php 
                        $coutsTotaux = array_column($metrics, 'cout_credit');
                        $bestCoutTotal = findBestValue($coutsTotaux, true);
                        foreach ($simulations as $index => $sim): 
                            $isBest = ($metrics[$index]['cout_credit'] == $bestCoutTotal);
                        ?>
                            <td class="<?= $isBest ? 'best-value' : '' ?>">
                                <?= formatMontant($metrics[$index]['cout_credit']) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Montant total à rembourser</td>
                        <?php 
                        $montantsTotaux = array_column($metrics, 'cout_total');
                        $bestMontantTotal = findBestValue($montantsTotaux, true);
                        foreach ($simulations as $index => $sim): 
                            $isBest = ($metrics[$index]['cout_total'] == $bestMontantTotal);
                        ?>
                            <td class="<?= $isBest ? 'best-value' : '' ?>">
                                <?= formatMontant($metrics[$index]['cout_total']) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Taux effectif global</td>
                        <?php 
                        $tauxEffectifs = array_column($metrics, 'taux_effectif');
                        $bestTauxEffectif = findBestValue($tauxEffectifs, true);
                        foreach ($simulations as $index => $sim): 
                            $isBest = ($metrics[$index]['taux_effectif'] == $bestTauxEffectif);
                        ?>
                            <td class="<?= $isBest ? 'best-value' : '' ?>">
                                <?= number_format($metrics[$index]['taux_effectif'], 2) ?>%
                            </td>
                        <?php endforeach; ?>
                    </tr>

                    <!-- Délais -->
                    <tr>
                        <td class="metric-label">Délai avant 1er remboursement</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= htmlspecialchars($sim['delai_premier']) ?> mois</td>
                        <?php endforeach; ?>
                    </tr>
                    
                    <tr>
                        <td class="metric-label">Date de simulation</td>
                        <?php foreach ($simulations as $sim): ?>
                            <td><?= date('d/m/Y H:i', strtotime($sim['date_simulation'])) ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Résumé et recommandations -->
        <div class="summary-section">
            <div class="summary-title">📊 Résumé et Recommandations</div>
            
            <?php
            // Trouver la meilleure simulation globalement
            $bestSimIndex = 0;
            $bestScore = PHP_INT_MAX;
            
            foreach ($simulations as $index => $sim) {
                // Score basé sur le coût total (plus bas = mieux)
                $score = $metrics[$index]['cout_total'];
                if ($score < $bestScore) {
                    $bestScore = $score;
                    $bestSimIndex = $index;
                }
            }
            
            $bestSim = $simulations[$bestSimIndex];
            ?>
            
            <div class="summary-item">
                <span class="summary-label">🏆 Simulation la plus avantageuse :</span>
                <span class="summary-value">
                    Simulation #<?= htmlspecialchars($bestSim['id']) ?> 
                    (<?= htmlspecialchars($bestSim['client_nom'] . ' ' . $bestSim['client_prenom']) ?>)
                </span>
            </div>
            
            <div class="summary-item">
                <span class="summary-label">💰 Économie par rapport à la plus chère :</span>
                <span class="summary-value">
                    <?= formatMontant(max($montantsTotaux) - $bestScore) ?>
                </span>
            </div>
            
            <div class="summary-item">
                <span class="summary-label">📈 Écart de taux effectif :</span>
                <span class="summary-value">
                    <?= number_format(max($tauxEffectifs) - min($tauxEffectifs), 2) ?> points
                </span>
            </div>
            
            <div class="summary-item">
                <span class="summary-label">⏱️ Différence de mensualité :</span>
                <span class="summary-value">
                    <?= formatMontant(max($mensualites) - min($mensualites)) ?>
                </span>
            </div>
        </div>

        <!-- Graphique de comparaison -->
        <div class="chart-container">
            <h3>Comparaison visuelle des coûts</h3>
            <canvas id="comparisonChart" width="400" height="200"></canvas>
        </div>

        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?= $_SERVER['HTTP_REFERER'] ?? '/final/ws/views/ajout_pret.php' ?>" class="back-button">
                ← Retour aux simulations
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Données pour le graphique
        const simulations = <?= json_encode($simulations) ?>;
        const metrics = <?= json_encode($metrics) ?>;
        
        // Créer le graphique de comparaison
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        
        const labels = simulations.map(sim => `Simulation #${sim.id}`);
        const montantEmprunte = simulations.map(sim => parseFloat(sim.montant));
        const totalInterets = simulations.map(sim => parseFloat(sim.total_interets));
        const coutAssurance = simulations.map(sim => parseFloat(sim.cout_total_assurance));
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Montant emprunté',
                        data: montantEmprunte,
                        backgroundColor: 'rgba(59, 130, 246, 0.6)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total des intérêts',
                        data: totalInterets,
                        backgroundColor: 'rgba(239, 68, 68, 0.6)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Coût assurance',
                        data: coutAssurance,
                        backgroundColor: 'rgba(34, 197, 94, 0.6)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Comparaison des coûts par simulation'
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        stacked: false
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' Ar';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
