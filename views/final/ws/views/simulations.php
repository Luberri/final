<?php
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Liste des Simulations' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-stats {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
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

        .status-badge {
            font-size: 0.8em;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4">
                    <i class="fas fa-calculator text-primary me-2"></i>
                    Liste des Simulations
                </h1>

                <!-- Statistiques -->
                <div class="row mb-4" id="statistiques">
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-calculator fa-2x mb-2"></i>
                                <h3 id="totalSimulations">0</h3>
                                <p class="mb-0">Total Simulations</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                <h3 id="montantMoyen">0 Ar</h3>
                                <p class="mb-0">Montant Moyen</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <h3 id="dureeMoyenne">0 mois</h3>
                                <p class="mb-0">Durée Moyenne</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card card-stats">
                            <div class="card-body text-center">
                                <i class="fas fa-percent fa-2x mb-2"></i>
                                <h3 id="tauxMoyen">0%</h3>
                                <p class="mb-0">Taux Moyen</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading -->
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Chargement des données...</p>
                </div>

                <!-- Tableau des simulations -->
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>Simulations Effectuées</h5>
                        <button class="btn btn-light btn-sm" onclick="chargerSimulations()">
                            <i class="fas fa-refresh me-2"></i>Actualiser
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Client</th>
                                        <th>Type de Prêt</th>
                                        <th>Montant</th>
                                        <th>Durée</th>
                                        <th>Taux</th>
                                        <th>Mensualité</th>
                                        <th>Date</th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentData = [];
        const apiBase = "./";

        // Charger les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            chargerSimulations();
        });

        function chargerSimulations() {
            showLoading(true);

            fetch(`${apiBase}api/simulations`)
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

            donnees.forEach(simulation => {
                const tr = document.createElement('tr');
                const dateFormatee = new Date(simulation.date_simulation).toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                tr.innerHTML = `
                    <td><span class="badge bg-info">#${simulation.id}</span></td>
                    <td>
                        <strong>${simulation.client_nom} ${simulation.client_prenom}</strong><br>
                        <small class="text-muted">${simulation.client_mail}</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${simulation.type_pret_nom}</span><br>
                        <small>${simulation.taux_annuel}% annuel</small>
                    </td>
                    <td><strong>${formatMontant(simulation.montant)}</strong></td>
                    <td>${simulation.duree} mois</td>
                    <td>${(simulation.taux_mensuel * 100).toFixed(2)}% /mois</td>
                    <td><strong>${formatMontant(simulation.mensualite)}</strong></td>
                    <td><small>${dateFormatee}</small></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="supprimerSimulation(${simulation.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            if (donnees.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">Aucune simulation trouvée</td></tr>';
            }
        }

        function calculerStatistiques(donnees) {
            const totalSimulations = donnees.length;
            
            if (totalSimulations === 0) {
                document.getElementById('totalSimulations').textContent = '0';
                document.getElementById('montantMoyen').textContent = '0 Ar';
                document.getElementById('dureeMoyenne').textContent = '0 mois';
                document.getElementById('tauxMoyen').textContent = '0%';
                return;
            }

            const montantMoyen = donnees.reduce((sum, sim) => sum + parseFloat(sim.montant), 0) / totalSimulations;
            const dureeMoyenne = donnees.reduce((sum, sim) => sum + parseFloat(sim.duree), 0) / totalSimulations;
            const tauxMoyen = donnees.reduce((sum, sim) => sum + (parseFloat(sim.taux_mensuel) * 100), 0) / totalSimulations;

            document.getElementById('totalSimulations').textContent = totalSimulations;
            document.getElementById('montantMoyen').textContent = formatMontant(montantMoyen);
            document.getElementById('dureeMoyenne').textContent = Math.round(dureeMoyenne) + ' mois';
            document.getElementById('tauxMoyen').textContent = tauxMoyen.toFixed(2) + '%';
        }

        function supprimerSimulation(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette simulation ?')) {
                fetch(`${apiBase}api/simulations?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        chargerSimulations(); // Recharger la liste
                    } else {
                        alert('Erreur lors de la suppression: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion');
                });
            }
        }

        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2
            }).format(montant) + ' Ar';
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }
    </script>
</body>

</html>