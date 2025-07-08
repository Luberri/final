<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Fonds Disponibles' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-stats {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .table-responsive {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .filter-card {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .positive {
            color: #28a745;
            font-weight: bold;
        }

        .negative {
            color: #dc3545;
            font-weight: bold;
        }

        .neutral {
            color: #6c757d;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4"><i class="fas fa-piggy-bank me-2"></i>Fonds Disponibles EF</h2>

                <!-- Filtres -->
                <div class="card filter-card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-filter me-2"></i>Filtres de Période</h5>
                        <form id="filtreForm" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Année début</label>
                                <select name="annee_debut" class="form-select" id="anneeDebut">
                                    <option value="">-- Toutes --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mois début</label>
                                <select name="mois_debut" class="form-select">
                                    <option value="">-- Tous --</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Année fin</label>
                                <select name="annee_fin" class="form-select" id="anneeFin">
                                    <option value="">-- Toutes --</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Mois fin</label>
                                <select name="mois_fin" class="form-select">
                                    <option value="">-- Tous --</option>
                                    <option value="1">Janvier</option>
                                    <option value="2">Février</option>
                                    <option value="3">Mars</option>
                                    <option value="4">Avril</option>
                                    <option value="5">Mai</option>
                                    <option value="6">Juin</option>
                                    <option value="7">Juillet</option>
                                    <option value="8">Août</option>
                                    <option value="9">Septembre</option>
                                    <option value="10">Octobre</option>
                                    <option value="11">Novembre</option>
                                    <option value="12">Décembre</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-search me-2"></i>Filtrer
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="row mb-4" id="statistiques">
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-coins fa-2x mb-2"></i>
                                <h3 id="totalFondsInitiaux">0 Ar</h3>
                                <p class="mb-0">Total Fonds Initiaux</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                <h3 id="totalPrete">0 Ar</h3>
                                <p class="mb-0">Total Prêté</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-arrow-circle-down fa-2x mb-2"></i>
                                <h3 id="totalRembourse">0 Ar</h3>
                                <p class="mb-0">Total Remboursé</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-piggy-bank fa-2x mb-2"></i>
                                <h3 id="montantDisponible">0 Ar</h3>
                                <p class="mb-0">Montant Disponible</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Chargement des données...</p>
                </div>

                <!-- Tableau des fonds disponibles -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Évolution des Fonds Disponibles par Mois</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Période</th>
                                        <th>Fonds Initiaux</th>
                                        <th>Montant Prêté</th>
                                        <th>Montant Remboursé</th>
                                        <th>Montant Disponible</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody id="corpsTableau">
                                    <!-- Les données seront chargées ici -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Graphique -->
                <div class="card mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Évolution des Fonds Disponibles</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Type de graphique:</label>
                                <select id="typeGraphique" class="form-select" onchange="changerTypeGraphique()">
                                    <option value="line">Courbe</option>
                                    <option value="bar">Barres</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div>
                                    <button class="btn btn-primary" onclick="actualiserGraphique()">
                                        <i class="fas fa-refresh me-2"></i>Actualiser
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="position: relative; height: 400px;">
                            <canvas id="graphiqueFonds"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let currentData = [];
        const apiBase = "./";
        let chartInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            chargerAnnees();
            chargerDonnees();
            chargerStatistiques();
        });

        // Gestionnaire de soumission du formulaire
        document.getElementById('filtreForm').addEventListener('submit', function(e) {
            e.preventDefault();
            chargerDonnees();
        });

        function chargerAnnees() {
            fetch(`${apiBase}api/fonds-disponibles/annees`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const selectDebut = document.getElementById('anneeDebut');
                        const selectFin = document.getElementById('anneeFin');

                        selectDebut.innerHTML = '<option value="">-- Toutes --</option>';
                        selectFin.innerHTML = '<option value="">-- Toutes --</option>';

                        data.data.forEach(annee => {
                            selectDebut.innerHTML += `<option value="${annee}">${annee}</option>`;
                            selectFin.innerHTML += `<option value="${annee}">${annee}</option>`;
                        });
                    }
                })
                .catch(error => console.error('Erreur chargement années:', error));
        }

        function chargerDonnees() {
            const formData = new FormData(document.getElementById('filtreForm'));
            const params = new URLSearchParams(formData);

            showLoading(true);

            fetch(`${apiBase}api/fonds-disponibles?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        currentData = data.data;
                        afficherTableau(data.data);
                        creerGraphique(data.data);
                    } else {
                        alert('Erreur lors du chargement des données: ' + (data.error || 'Erreur inconnue'));
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion: ' + error.message);
                })
                .finally(() => {
                    showLoading(false);
                });
        }

        function chargerStatistiques() {
            fetch(`${apiBase}api/fonds-disponibles/statistiques`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const stats = data.data;
                        document.getElementById('totalFondsInitiaux').textContent =
                            formatMontant(stats.total_fonds_initiaux || 0);
                        document.getElementById('totalPrete').textContent =
                            formatMontant(stats.total_prete || 0);
                        document.getElementById('totalRembourse').textContent =
                            formatMontant(stats.total_rembourse || 0);
                        document.getElementById('montantDisponible').textContent =
                            formatMontant(stats.montant_total_disponible || 0);
                    }
                })
                .catch(error => console.error('Erreur stats:', error));
        }

        function afficherTableau(donnees) {
            const tbody = document.getElementById('corpsTableau');
            tbody.innerHTML = '';

            donnees.forEach(row => {
                const tr = document.createElement('tr');
                const nomsMois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ];

                const montantDisponible = parseFloat(row.montant_disponible);
                let statutClass = 'neutral';
                let statutText = 'Neutre';

                if (montantDisponible > 0) {
                    statutClass = 'positive';
                    statutText = 'Disponible';
                } else if (montantDisponible < 0) {
                    statutClass = 'negative';
                    statutText = 'Déficit';
                }

                tr.innerHTML = `
                    <td><span class="badge bg-primary">${nomsMois[row.mois]} ${row.annee}</span></td>
                    <td><strong>${formatMontant(row.total_fonds_initiaux)}</strong></td>
                    <td><strong>${formatMontant(row.total_prete)}</strong></td>
                    <td><strong>${formatMontant(row.total_rembourse)}</strong></td>
                    <td><strong class="${statutClass}">${formatMontant(row.montant_disponible)}</strong></td>
                    <td><span class="badge bg-${montantDisponible >= 0 ? 'success' : 'danger'}">${statutText}</span></td>
                `;
                tbody.appendChild(tr);
            });

            if (donnees.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune donnée trouvée pour cette période</td></tr>';
            }
        }

        function creerGraphique(donnees) {
            const labels = [];
            const dataFonds = [];
            const dataPrete = [];
            const dataRembourse = [];
            const dataDisponible = [];

            const nomsMois = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'
            ];

            const donneesTriees = [...donnees].sort((a, b) => {
                if (a.annee === b.annee) {
                    return a.mois - b.mois;
                }
                return a.annee - b.annee;
            });

            donneesTriees.forEach(row => {
                labels.push(`${nomsMois[row.mois]} ${row.annee}`);
                dataFonds.push(parseFloat(row.total_fonds_initiaux));
                dataPrete.push(parseFloat(row.total_prete));
                dataRembourse.push(parseFloat(row.total_rembourse));
                dataDisponible.push(parseFloat(row.montant_disponible));
            });

            const ctx = document.getElementById('graphiqueFonds').getContext('2d');

            if (chartInstance) {
                chartInstance.destroy();
            }

            const typeGraphique = document.getElementById('typeGraphique').value;

            chartInstance = new Chart(ctx, {
                type: typeGraphique,
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Fonds Initiaux (Ar)',
                            data: dataFonds,
                            borderColor: 'rgb(40, 167, 69)',
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Montant Prêté (Ar)',
                            data: dataPrete,
                            borderColor: 'rgb(255, 193, 7)',
                            backgroundColor: 'rgba(255, 193, 7, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Montant Remboursé (Ar)',
                            data: dataRembourse,
                            borderColor: 'rgb(23, 162, 184)',
                            backgroundColor: 'rgba(23, 162, 184, 0.2)',
                            borderWidth: 2
                        },
                        {
                            label: 'Montant Disponible (Ar)',
                            data: dataDisponible,
                            borderColor: 'rgb(220, 53, 69)',
                            backgroundColor: 'rgba(220, 53, 69, 0.2)',
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Évolution des Fonds Disponibles par Mois'
                        },
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatMontant(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2
            }).format(montant) + ' Ar';
        }

        function resetFilters() {
            document.getElementById('filtreForm').reset();
            chargerDonnees();
        }

        function changerTypeGraphique() {
            if (currentData.length > 0) {
                creerGraphique(currentData);
            }
        }

        function actualiserGraphique() {
            if (currentData.length > 0) {
                creerGraphique(currentData);
            } else {
                chargerDonnees();
            }
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }
    </script>
</body>

</html>