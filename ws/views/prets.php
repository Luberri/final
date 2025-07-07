<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Liste des Prêts' ?></title>
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

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .modal-xl {
            max-width: 1200px;
        }

        .status-badge {
            font-size: 0.8em;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-hand-holding-usd me-2"></i>EF Dashboard</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="../dashboard">Dashboard</a>
                <a class="nav-link" href="../interets">Intérêts</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4"><i class="fas fa-list me-2"></i>Liste des Prêts</h2>

                <!-- Statistiques rapides -->
                <div class="row mb-4" id="statistiques">
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-list-ol fa-2x mb-2"></i>
                                <h3 id="totalPrets">0</h3>
                                <p class="mb-0">Total Prêts</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h3 id="montantTotal">0 Ar</h3>
                                <p class="mb-0">Montant Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-percentage fa-2x mb-2"></i>
                                <h3 id="tauxMoyen">0%</h3>
                                <p class="mb-0">Taux Moyen</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x mb-2"></i>
                                <h3 id="interetsTotal">0 Ar</h3>
                                <p class="mb-0">Intérêts Estimés</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Chargement des données...</p>
                </div>

                <!-- Tableau des prêts -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Prêts Actifs</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Type</th>
                                        <th>Montant</th>
                                        <th>Taux</th>
                                        <th>Durée</th>
                                        <th>Mensualité</th>
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
            </div>
        </div>
    </div>

    <!-- Modal pour les détails du prêt -->
    <div class="modal fade" id="modalDetails" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails du Prêt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentData = [];
        const apiBase = "./";

        // Charger les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            chargerPrets();
        });

        function chargerPrets() {
            showLoading(true);

            fetch(`${apiBase}api/prets`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        currentData = data.data;
                        afficherTableau(data.data);
                        calculerStatistiques(data.data);
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

        function afficherTableau(donnees) {
            const tbody = document.getElementById('corpsTableau');
            tbody.innerHTML = '';

            donnees.forEach(pret => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="badge bg-info">#${pret.id}</span></td>
                    <td>
                        <strong>${pret.client_nom} ${pret.client_prenom}</strong><br>
                        <small class="text-muted">${pret.client_mail}</small>
                    </td>
                    <td><span class="badge bg-secondary">${pret.type_pret}</span></td>
                    <td><strong>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(pret.montant)} Ar</strong></td>
                    <td><span class="badge bg-warning">${pret.taux}%</span></td>
                    <td>${pret.duree} mois</td>
                    <td><strong>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(pret.mensualite_constante)} Ar</strong></td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info" onclick="voirDetails(${pret.id})" title="Voir détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="genererPDF(${pret.id})" title="Générer PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            if (donnees.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Aucun prêt trouvé</td></tr>';
            }
        }

        function calculerStatistiques(donnees) {
            const totalPrets = donnees.length;
            const montantTotal = donnees.reduce((sum, pret) => sum + parseFloat(pret.montant), 0);
            const tauxMoyen = donnees.length > 0 ? donnees.reduce((sum, pret) => sum + parseFloat(pret.taux), 0) / donnees.length : 0;
            const interetsTotal = donnees.reduce((sum, pret) => sum + parseFloat(pret.total_interets_estime), 0);

            document.getElementById('totalPrets').textContent = totalPrets;
            document.getElementById('montantTotal').textContent = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2
            }).format(montantTotal) + ' Ar';
            document.getElementById('tauxMoyen').textContent = tauxMoyen.toFixed(2) + '%';
            document.getElementById('interetsTotal').textContent = new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2
            }).format(interetsTotal) + ' Ar';
        }

        function voirDetails(pretId) {
            fetch(`${apiBase}api/prets/details?id=${pretId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        afficherModalDetails(data.data);
                    } else {
                        alert('Erreur: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur lors du chargement des détails');
                });
        }

        function afficherModalDetails(details) {
            const modalContent = document.getElementById('modalContent');
            const pret = details.pret;
            const stats = details.statistiques;

            let html = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations Client</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Nom:</strong></td><td>${pret.client_nom} ${pret.client_prenom}</td></tr>
                            <tr><td><strong>Email:</strong></td><td>${pret.client_mail}</td></tr>
                            <tr><td><strong>Profession:</strong></td><td>${pret.profession || 'Non renseignée'}</td></tr>
                            <tr><td><strong>Revenu:</strong></td><td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(pret.revenu_mensuel || 0)} Ar</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails du Prêt</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Type:</strong></td><td>${pret.type_pret}</td></tr>
                            <tr><td><strong>Montant:</strong></td><td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(pret.montant)} Ar</td></tr>
                            <tr><td><strong>Taux:</strong></td><td>${pret.taux}%</td></tr>
                            <tr><td><strong>Durée:</strong></td><td>${pret.duree} mois</td></tr>
                            <tr><td><strong>Total intérêts:</strong></td><td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(stats.total_interets)} Ar</td></tr>
                        </table>
                    </div>
                </div>
            `;

            if (details.tableau_amortissement && details.tableau_amortissement.length > 0) {
                html += `
                    <div class="mt-3">
                        <h6>Tableau d'Amortissement (Aperçu - 5 premiers mois)</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Mois</th>
                                        <th>Capital Restant</th>
                                        <th>Intérêts</th>
                                        <th>Mensualité</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;

                details.tableau_amortissement.slice(0, 5).forEach(echeance => {
                    html += `
                        <tr>
                            <td>${echeance.numero_mois}</td>
                            <td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(echeance.capital_restant)} Ar</td>
                            <td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(echeance.interets_mois)} Ar</td>
                            <td>${new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2 }).format(echeance.mensualite_constante)} Ar</td>
                            <td>${new Date(echeance.date_mois).toLocaleDateString('fr-FR')}</td>
                        </tr>
                    `;
                });

                html += `
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted"><small>Affichage des 5 premiers mois seulement. Le PDF complet contient tous les détails.</small></p>
                    </div>
                `;
            }

            modalContent.innerHTML = html;

            const modal = new bootstrap.Modal(document.getElementById('modalDetails'));
            modal.show();
        }

        function genererPDF(pretId) {
            const url = `${apiBase}api/prets/pdf?id=${pretId}`;

            // Créer un lien temporaire pour télécharger directement
            const link = document.createElement('a');
            link.href = url;
            link.download = `pret_${pretId}.html`; // Le nom sera défini côté serveur
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }
    </script>
</body>

</html>