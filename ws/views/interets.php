<!DOCTYPE html>
<html lang="fr">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?? 'Intérêts Gagnés' ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            <style>
                .card-stats {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }

                .table-responsive {
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                }

                .filter-card {
                    background: #f8f9fa;
                    border-left: 4px solid #007bff;
                }

                .loading {
                    display: none;
                    text-align: center;
                    padding: 20px;
                }
            </style>
        </head>

        <body class="bg-light">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                <div class="container">
                    <a class="navbar-brand" href="#"><i class="fas fa-chart-line me-2"></i>EF Dashboard</a>
                    <div class="navbar-nav ms-auto">
                        <a class="nav-link" href="../dashboard">Retour au Dashboard</a>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-4"><i class="fas fa-money-bill-trend-up me-2"></i>Intérêts Gagnés par l'EF</h2>

                        <!-- Filtres -->
                        <div class="card filter-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-filter me-2"></i>Filtres de Période</h5>
                                <form id="filtreForm" class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Année début</label>
                                        <select name="annee_debut" class="form-select" id="anneeDebut">
                                            <option value="">-- Toutes --</option>
                                            <!-- Les années seront chargées dynamiquement -->
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
                                            <!-- Les années seront chargées dynamiquement -->
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
                                        <button type="submit" class="btn btn-primary">
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
                                        <i class="fas fa-euro-sign fa-2x mb-2"></i>
                                        <h3 id="totalInterets">0 Ar</h3>
                                        <p class="mb-0">Total Intérêts</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body text-center">
                                        <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                        <h3 id="moyenneMensuelle">0 Ar</h3>
                                        <p class="mb-0">Moyenne Mensuelle</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body text-center">
                                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                                        <h3 id="nombreMois">0</h3>
                                        <p class="mb-0">Mois d'activité</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats">
                                    <div class="card-body text-center">
                                        <i class="fas fa-handshake fa-2x mb-2"></i>
                                        <h3>EF</h3>
                                        <p class="mb-0">Établissement</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div class="loading" id="loading">
                            <i class="fas fa-spinner fa-spin fa-2x"></i>
                            <p class="mt-2">Chargement des données...</p>
                        </div>

                        <!-- Tableau des intérêts -->
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Intérêts Gagnés par Mois</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Période</th>
                                                <th>Mois</th>
                                                <th>Année</th>
                                                <th>Intérêts</th>
                                                <th>Nb Prêts</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="corpsTableau">
                                            <!-- Les données seront chargées ici -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tableau des intérêts par type -->
                        <div class="card mt-4">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Intérêts par Type de Prêt</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Type de Prêt</th>
                                                <th>Taux</th>
                                                <th>Total Intérêts</th>
                                                <th>Nb Prêts</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableauParType">
                                            <!-- Les données seront chargées ici -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Graphique des intérêts mensuels -->
                        <div class="card mt-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Évolution des Intérêts Mensuels</h5>
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
                                    <canvas id="graphiqueInterets"></canvas>
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
                const apiBase = "./"; // Chemin relatif vers les APIs
                let chartInstance = null;

                // Charger les données au démarrage
                document.addEventListener('DOMContentLoaded', function() {
                    chargerAnnees();
                    chargerDonnees();
                    chargerStatistiques();
                    chargerInteretsParType();
                });

                // Gestionnaire de soumission du formulaire
                document.getElementById('filtreForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    chargerDonnees();
                    chargerInteretsParType();
                });

                function chargerAnnees() {
                    fetch(`${apiBase}api/interets/annees`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const selectDebut = document.getElementById('anneeDebut');
                                const selectFin = document.getElementById('anneeFin');

                                // Vider les options existantes (sauf la première)
                                selectDebut.innerHTML = '<option value="">-- Toutes --</option>';
                                selectFin.innerHTML = '<option value="">-- Toutes --</option>';

                                // Ajouter les années disponibles
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

                    // Debug: afficher les paramètres envoyés
                    console.log('Paramètres envoyés:', params.toString());

                    showLoading(true);

                    fetch(`${apiBase}api/interets?${params}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Données reçues:', data); // Debug
                            if (data.success) {
                                currentData = data.data;
                                afficherTableau(data.data);
                            } else {
                                console.error('Erreur:', data.error);
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
                    fetch(`${apiBase}api/interets/statistiques`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const stats = data.data;
                                document.getElementById('totalInterets').textContent =
                                    new Intl.NumberFormat('fr-FR', {
                                        minimumFractionDigits: 2
                                    }).format(stats.total_interets_global || 0) + ' Ar';
                                document.getElementById('moyenneMensuelle').textContent =
                                    new Intl.NumberFormat('fr-FR', {
                                        minimumFractionDigits: 2
                                    }).format(stats.moyenne_mensuelle || 0) + ' Ar';
                                document.getElementById('nombreMois').textContent = stats.nombre_mois || 0;
                            }
                        })
                        .catch(error => console.error('Erreur stats:', error));
                }

                function chargerInteretsParType() {
                    const formData = new FormData(document.getElementById('filtreForm'));
                    const params = new URLSearchParams(formData);

                    console.log('Paramètres pour interets par type:', params.toString());

                    fetch(`${apiBase}api/interets/par-type?${params}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Données par type reçues:', data);
                            if (data.success) {
                                afficherTableauParType(data.data);
                            }
                        })
                        .catch(error => console.error('Erreur par type:', error));
                }

                // Fonction principale pour créer le graphique
                function creerGraphique(donnees) {
                    const labels = [];
                    const dataPoints = [];
                    const nomsMois = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                        'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'
                    ];

                    // Trie les données par année puis mois
                    const donneesTriees = [...donnees].sort((a, b) => {
                        if (a.annee === b.annee) {
                            return a.mois - b.mois;
                        }
                        return a.annee - b.annee;
                    });

                    donneesTriees.forEach(row => {
                        labels.push(`${nomsMois[row.mois]} ${row.annee}`);
                        dataPoints.push(parseFloat(row.total_interets_mois));
                    });

                    const ctx = document.getElementById('graphiqueInterets').getContext('2d');

                    // Détruire le graphique existant s'il y en a un
                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    const typeGraphique = document.getElementById('typeGraphique').value;

                    chartInstance = new Chart(ctx, {
                        type: typeGraphique,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Intérêts Mensuels (Ar)',
                                data: dataPoints,
                                borderColor: typeGraphique === 'line' ? 'rgb(75, 192, 192)' : 'rgba(54, 162, 235, 1)',
                                backgroundColor: typeGraphique === 'line' ? 'rgba(75, 192, 192, 0.2)' : 'rgba(54, 162, 235, 0.2)',
                                borderWidth: typeGraphique === 'line' ? 2 : 1,
                                fill: typeGraphique === 'line' ? true : false,
                                tension: typeGraphique === 'line' ? 0.4 : 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Évolution des Intérêts Gagnés par Mois'
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
                                            return new Intl.NumberFormat('fr-FR', {
                                                minimumFractionDigits: 0,
                                                maximumFractionDigits: 0
                                        }).format(value) + ' Ar';
                                        }
                                    }
                                },
                                x: {
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            onHover: (event, elements) => {
                                event.native.target.style.cursor = elements.length > 0 ? 'pointer' : 'default';
                            },
                            onClick: (event, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const donnee = donneesTriees[index];
                                    voirDetails(donnee.annee, donnee.mois);
                                }
                            }
                        }
                    });
                }

                // Fonction pour changer le type de graphique
                function changerTypeGraphique() {
                    if (currentData.length > 0) {
                        creerGraphique(currentData);
                    }
                }

                // Fonction pour actualiser le graphique
                function actualiserGraphique() {
                    if (currentData.length > 0) {
                        creerGraphique(currentData);
                    } else {
                        chargerDonnees();
                    }
                }

                function afficherTableau(donnees) {
                    const tbody = document.getElementById('corpsTableau');
                    tbody.innerHTML = '';

                    donnees.forEach(row => {
                        const tr = document.createElement('tr');
                        const nomsMois = ['', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
                        ];

                        tr.innerHTML = `
                        <td><span class="badge bg-primary">${nomsMois[row.mois]} ${row.annee}</span></td>
                        <td>${row.mois}</td>
                        <td>${row.annee}</td>
                        <td><strong>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(row.total_interets_mois)} Ar</strong></td>
                        <td>${row.nombre_prets_actifs}</td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="voirDetails(${row.annee}, ${row.mois})">
                                <i class="fas fa-eye"></i> Détails
                            </button>
                        </td>
                    `;
                        tbody.appendChild(tr);
                    });

                    if (donnees.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucune donnée trouvée pour cette période</td></tr>';
                    }

                    // Mettre à jour le graphique avec les nouvelles données
                    creerGraphique(donnees);
                }

                function afficherTableauParType(donnees) {
                    const tbody = document.getElementById('tableauParType');
                    tbody.innerHTML = '';

                    donnees.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td><strong>${row.type_pret}</strong></td>
                        <td><span class="badge bg-info">${row.taux}%</span></td>
                        <td><strong>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(row.total_interets)} Ar</strong></td>
                        <td>${row.nombre_prets}</td>
                    `;
                        tbody.appendChild(tr);
                    });

                    if (donnees.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Aucune donnée trouvée</td></tr>';
                    }
                }

                function voirDetails(annee, mois) {
                    const params = new URLSearchParams({
                        annee_debut: annee,
                        mois_debut: mois,
                        annee_fin: annee,
                        mois_fin: mois
                    });

                    fetch(`${apiBase}api/interets/detail?${params}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                afficherModalDetails(data.data, `${mois}/${annee}`);
                            }
                        })
                        .catch(error => console.error('Erreur détails:', error));
                }

                function afficherModalDetails(details, periode) {
                    let modalHtml = `
                    <div class="modal fade" id="modalDetails" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Détails pour ${periode}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Type Prêt</th>
                                                <th>Montant Prêt</th>
                                                <th>Taux</th>
                                                <th>Intérêt</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                `;

                    details.forEach(detail => {
                        modalHtml += `
                        <tr>
                            <td>${detail.nom} ${detail.prenom}</td>
                            <td>Type de prêt</td>
                            <td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(detail.capital_emprunte)} Ar</td>
                            <td>${detail.taux_annuel}%</td>
                            <td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(detail.interets_mois)} Ar</td>
                        </tr>
                    `;
                    });

                    modalHtml += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                    // Supprimer le modal existant s'il y en a un
                    const existingModal = document.getElementById('modalDetails');
                    if (existingModal) {
                        existingModal.remove();
                    }

                    // Ajouter le nouveau modal
                    document.body.insertAdjacentHTML('beforeend', modalHtml);

                    // Afficher le modal
                    const modal = new bootstrap.Modal(document.getElementById('modalDetails'));
                    modal.show();
                }

                function resetFilters() {
                    document.getElementById('filtreForm').reset();
                    chargerDonnees();
                    chargerInteretsParType();
                }

                function showLoading(show) {
                    document.getElementById('loading').style.display = show ? 'block' : 'none';
                }
            </script>
        </body>

        </html>