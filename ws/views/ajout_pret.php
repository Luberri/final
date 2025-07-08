<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un prêt</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 1rem;
        }

        .main-container {
            display: flex;
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .form-container {
            flex: 1;
            max-width: 600px;
        }

        .simulations-container {
            flex: 1;
            max-width: 800px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            width: 100%;
        }

        h2 {
            font-size: 1.5rem;
            color: #1a202c;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        h3 {
            font-size: 1.25rem;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-size: 0.875rem;
            color: #2d3748;
            font-weight: 500;
        }

        select,
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        select:focus,
        input:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.2);
        }

        button {
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        button[type="button"] {
            background-color: #6b7280;
            color: #fff;
        }

        button[type="submit"] {
            background-color: #3182ce;
            color: #fff;
        }

        button:hover {
            filter: brightness(90%);
        }

        .button-group {
            display: flex;
            gap: 1rem;
        }

        .message {
            text-align: center;
            font-size: 0.875rem;
            margin-top: 1rem;
        }

        .success {
            color: #2f855a;
        }

        .error {
            color: #c53030;
        }

        #taux-display {
            margin-top: 0.5rem;
            font-weight: bold;
            color: #3182ce;
        }

        #assurance-group {
            margin-top: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            text-align: right;
            font-size: 0.875rem;
        }

        th {
            background-color: #edf2f7;
            font-weight: 600;
            color: #2d3748;
        }

        tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }

        .simulations-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .simulation-item {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fff;
            transition: box-shadow 0.2s;
        }

        .simulation-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .simulation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .simulation-id {
            background-color: #3182ce;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
        }

        .simulation-date {
            font-size: 0.75rem;
            color: #6b7280;
        }

        .simulation-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .detail-label {
            font-weight: 500;
            color: #4a5568;
        }

        .detail-value {
            color: #2d3748;
        }

        .simulation-actions {
            margin-top: 0.75rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .simulation-checkbox {
            margin-right: 0.5rem;
            transform: scale(1.2);
        }

        .compare-section {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem;
            border-top: 2px solid #e2e8f0;
            margin-top: 1rem;
            display: none;
        }

        .compare-button {
            width: 100%;
            background-color: #059669;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .compare-button:hover {
            background-color: #047857;
        }

        .compare-button:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }

        .selection-info {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 4px;
            cursor: pointer;
            border: none;
        }

        .btn-danger {
            background-color: #e53e3e;
            color: white;
        }

        .btn-info {
            background-color: #3182ce;
            color: white;
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 0.875rem;
            min-width: 120px;
        }

        .no-simulations {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
            font-style: italic;
        }

        .loading-simulations {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
            }

            .form-container,
            .simulations-container {
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }

            .button-group {
                flex-direction: column;
            }

            .simulation-details {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Formulaire d'ajout de prêt -->
        <div class="form-container">
            <div class="container">
                <h2>Ajouter un prêt</h2>
                <form id="pretForm">
                    <!-- ...existing form content... -->
                    <div>
                        <label for="client_id">Client</label>
                        <select id="client_id" name="client_id" required></select>
                    </div>

                    <div>
                        <label for="montant">Montant</label>
                        <input type="number" id="montant" name="montant" required min="1" step="0.01">
                    </div>

                    <div>
                        <label for="duree">Durée (mois)</label>
                        <input type="number" id="duree" name="duree" required min="1">
                    </div>

                    <div>
                        <label for="type_remboursement_id">Type de remboursement</label>
                        <select id="type_remboursement_id" name="type_remboursement_id" required></select>
                    </div>

                    <div>
                        <label for="type_pret_id">Type de prêt</label>
                        <select id="type_pret_id" name="type_pret_id" required></select>
                        <div id="taux-display"></div>
                    </div>

                    <div>
                        <label for="delai_premier">Délai avant 1er remboursement (mois)</label>
                        <input type="number" id="delai_premier" name="delai_premier" min="0" value="0">
                    </div>

                    <div>
                        <label>
                            <input type="checkbox" id="has_assurance" onchange="document.getElementById('assurance-group').style.display = this.checked ? 'block' : 'none';">
                            Ajouter une assurance
                        </label>
                        <div id="assurance-group" style="display:none;">
                            <label for="assurance">Assurance (% par an)</label>
                            <input type="number" id="assurance" name="assurance" placeholder="Ex: 0.5 pour 0,5%" min="0" max="100" step="0.01" value="0">
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="button" id="simulateur-btn">Simuler</button>
                        <button type="submit">Ajouter</button>
                    </div>
                </form>

                <div class="message">
                    <div id="message"></div>
                    <div id="error-message" class="error"></div>
                </div>
                <div id="simulation-result"></div>
            </div>
        </div>

        <!-- Liste des simulations -->
        <div class="simulations-container">
            <div class="container">
                <h3>Simulations récentes</h3>
                
                <!-- Filtres -->
                <div class="filters">
                    <select id="filter-client" class="filter-input">
                        <option value="">Tous les clients</option>
                    </select>
                    <input type="number" id="filter-montant-min" class="filter-input" placeholder="Montant min">
                    <input type="number" id="filter-montant-max" class="filter-input" placeholder="Montant max">
                    <button type="button" onclick="filtrerSimulations()" class="btn-sm btn-info">Filtrer</button>
                    <button type="button" onclick="resetFiltres()" class="btn-sm">Reset</button>
                </div>

                <!-- Liste des simulations -->
                <div id="simulations-loading" class="loading-simulations" style="display: none;">
                    Chargement des simulations...
                </div>
                
                <div class="simulations-list" id="simulations-list">
                    <!-- Les simulations seront chargées ici -->
                </div>

                <!-- Section de comparaison -->
                <div class="compare-section" id="compare-section">
                    <div class="selection-info" id="selection-info">
                        Aucune simulation sélectionnée
                    </div>
                    <button type="button" id="compare-button" class="compare-button" disabled onclick="comparerSimulations()">
                        Comparer les simulations sélectionnées
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.apiBase = window.apiBase || "http://localhost/final/ws";
        let typePretTauxMap = {};
        let allSimulations = [];
        let allClients = [];
        let selectedSimulations = [];

        // Charger toutes les données au démarrage
        document.addEventListener('DOMContentLoaded', function() {
            chargerClients();
            chargerTypesRemboursement();
            chargerTypesPret();
            chargerSimulations();
        });

        // Charger la liste des clients
        function chargerClients() {
            fetch(window.apiBase + '/api/clients')
                .then(res => res.json())
                .then(data => {
                    allClients = data;
                    const select = document.getElementById('client_id');
                    const filterSelect = document.getElementById('filter-client');
                    
                    data.forEach(client => {
                        // Formulaire
                        const option = document.createElement('option');
                        option.value = client.id;
                        option.textContent = client.nom + ' ' + client.prenom;
                        select.appendChild(option);

                        // Filtre
                        const filterOption = document.createElement('option');
                        filterOption.value = client.id;
                        filterOption.textContent = client.nom + ' ' + client.prenom;
                        filterSelect.appendChild(filterOption);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des clients:', error));
        }

        // Charger la liste des types de remboursement
        function chargerTypesRemboursement() {
            fetch(window.apiBase + '/api/type_remboursements')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('type_remboursement_id');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.nom;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Erreur lors du chargement des types de remboursement:', error));
        }

        // Charger la liste des types de prêt
        function chargerTypesPret() {
            fetch(window.apiBase + '/api/type_prets')
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('type_pret_id');
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = `${type.nom} (${type.taux}%)`;
                        select.appendChild(option);
                        typePretTauxMap[type.id] = parseFloat(type.taux) / 12 / 100;
                    });
                    if (select.value) updateTauxDisplay(select.value);
                })
                .catch(error => console.error('Erreur lors du chargement des types de prêt:', error));
        }

        // Charger les simulations
        function chargerSimulations() {
            document.getElementById('simulations-loading').style.display = 'block';
            
            fetch(window.apiBase + '/api/simulations')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allSimulations = data.data;
                        afficherSimulations(allSimulations);
                    } else {
                        console.error('Erreur:', data.error);
                        document.getElementById('simulations-list').innerHTML = 
                            '<div class="no-simulations">Erreur lors du chargement des simulations</div>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    document.getElementById('simulations-list').innerHTML = 
                        '<div class="no-simulations">Erreur de connexion</div>';
                })
                .finally(() => {
                    document.getElementById('simulations-loading').style.display = 'none';
                });
        }

        // Afficher les simulations
        function afficherSimulations(simulations) {
            const container = document.getElementById('simulations-list');
            
            if (simulations.length === 0) {
                container.innerHTML = '<div class="no-simulations">Aucune simulation trouvée</div>';
                return;
            }

            container.innerHTML = '';
            
            simulations.forEach(simulation => {
                const dateFormatee = new Date(simulation.date_simulation).toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const simulationElement = document.createElement('div');
                simulationElement.className = 'simulation-item';
                simulationElement.innerHTML = `
                    <div class="simulation-header">
                        <span class="simulation-id">#${simulation.id}</span>
                        <span class="simulation-date">${dateFormatee}</span>
                    </div>
                    <div class="simulation-details">
                        <span class="detail-label">Client:</span>
                        <span class="detail-value">${simulation.client_nom} ${simulation.client_prenom}</span>
                        
                        <span class="detail-label">Type de prêt:</span>
                        <span class="detail-value">${simulation.type_pret_nom}</span>
                        
                        <span class="detail-label">Montant:</span>
                        <span class="detail-value">${formatMontant(simulation.montant)}</span>
                        
                        <span class="detail-label">Durée:</span>
                        <span class="detail-value">${simulation.duree} mois</span>
                        
                        <span class="detail-label">Taux mensuel:</span>
                        <span class="detail-value">${(simulation.taux_mensuel * 100).toFixed(2)}%</span>
                        
                        <span class="detail-label">Mensualité:</span>
                        <span class="detail-value">${formatMontant(simulation.mensualite)}</span>
                        
                        <span class="detail-label">Total intérêts:</span>
                        <span class="detail-value">${formatMontant(simulation.total_interets)}</span>
                        
                        <span class="detail-label">Assurance:</span>
                        <span class="detail-value">${simulation.assurance}%</span>
                    </div>
                    <div class="simulation-actions">
                        <input type="checkbox" class="simulation-checkbox" 
                               id="checkbox-${simulation.id}" 
                               value="${simulation.id}" 
                               onchange="toggleSimulationSelection(${simulation.id})">
                        <label for="checkbox-${simulation.id}" style="margin-right: auto; font-size: 0.875rem;">Sélectionner</label>
                        <button class="btn-sm btn-info" onclick="voirDetailSimulation(${simulation.id})">Détails</button>
                        <button class="btn-sm btn-danger" onclick="supprimerSimulation(${simulation.id})">Supprimer</button>
                    </div>
                `;
                
                container.appendChild(simulationElement);
            });
        }

        // Filtrer les simulations
        function filtrerSimulations() {
            const clientId = document.getElementById('filter-client').value;
            const montantMin = parseFloat(document.getElementById('filter-montant-min').value) || 0;
            const montantMax = parseFloat(document.getElementById('filter-montant-max').value) || Infinity;

            const simulationsFiltrees = allSimulations.filter(simulation => {
                const matchClient = !clientId || simulation.client_id == clientId;
                const matchMontantMin = simulation.montant >= montantMin;
                const matchMontantMax = simulation.montant <= montantMax;
                
                return matchClient && matchMontantMin && matchMontantMax;
            });

            afficherSimulations(simulationsFiltrees);
        }

        // Reset des filtres
        function resetFiltres() {
            document.getElementById('filter-client').value = '';
            document.getElementById('filter-montant-min').value = '';
            document.getElementById('filter-montant-max').value = '';
            afficherSimulations(allSimulations);
        }

        // Gérer la sélection des simulations
        function toggleSimulationSelection(simulationId) {
            const checkbox = document.getElementById(`checkbox-${simulationId}`);
            const index = selectedSimulations.indexOf(simulationId);
            
            if (checkbox.checked && index === -1) {
                selectedSimulations.push(simulationId);
            } else if (!checkbox.checked && index !== -1) {
                selectedSimulations.splice(index, 1);
            }
            
            updateCompareSection();
        }

        // Mettre à jour la section de comparaison
        function updateCompareSection() {
            const compareSection = document.getElementById('compare-section');
            const selectionInfo = document.getElementById('selection-info');
            const compareButton = document.getElementById('compare-button');
            
            if (selectedSimulations.length === 0) {
                compareSection.style.display = 'none';
                selectionInfo.textContent = 'Aucune simulation sélectionnée';
                compareButton.disabled = true;
            } else {
                compareSection.style.display = 'block';
                if (selectedSimulations.length === 1) {
                    selectionInfo.textContent = '1 simulation sélectionnée';
                    compareButton.disabled = true;
                    compareButton.textContent = 'Sélectionnez au moins 2 simulations pour comparer';
                } else {
                    selectionInfo.textContent = `${selectedSimulations.length} simulations sélectionnées`;
                    compareButton.disabled = false;
                    compareButton.textContent = 'Comparer les simulations sélectionnées';
                }
            }
        }

        // Comparer les simulations sélectionnées
        function comparerSimulations() {
            if (selectedSimulations.length < 2) {
                alert('Veuillez sélectionner au moins 2 simulations pour effectuer une comparaison.');
                return;
            }
            
            // Préparer les données pour la page de comparaison
            const simulationsAComparer = selectedSimulations.map(id => {
                return allSimulations.find(sim => sim.id == id);
            }).filter(sim => sim !== undefined);
            
            // Créer une forme pour envoyer les données via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.apiBase + '/comparaison-simulations';
            form.style.display = 'none';
            
            // Ajouter les données des simulations
            simulationsAComparer.forEach((simulation, index) => {
                Object.keys(simulation).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `simulation_${index}_${key}`;
                    input.value = simulation[key];
                    form.appendChild(input);
                });
            });
            
            // Ajouter le nombre de simulations
            const countInput = document.createElement('input');
            countInput.type = 'hidden';
            countInput.name = 'count';
            countInput.value = simulationsAComparer.length;
            form.appendChild(countInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Voir détail d'une simulation
        function voirDetailSimulation(id) {
            const simulation = allSimulations.find(s => s.id == id);
            if (simulation) {
                alert(`Détails de la simulation #${id}:\n\n` +
                    `Client: ${simulation.client_nom} ${simulation.client_prenom}\n` +
                    `Email: ${simulation.client_mail}\n` +
                    `Type de prêt: ${simulation.type_pret_nom}\n` +
                    `Montant: ${formatMontant(simulation.montant)}\n` +
                    `Durée: ${simulation.duree} mois\n` +
                    `Taux mensuel: ${(simulation.taux_mensuel * 100).toFixed(2)}%\n` +
                    `Mensualité: ${formatMontant(simulation.mensualite)}\n` +
                    `Total intérêts: ${formatMontant(simulation.total_interets)}\n` +
                    `Coût assurance: ${formatMontant(simulation.cout_total_assurance)}\n` +
                    `Date: ${new Date(simulation.date_simulation).toLocaleString('fr-FR')}`);
            }
        }

        // Supprimer une simulation
        function supprimerSimulation(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette simulation ?')) {
                fetch(`${window.apiBase}/api/simulations?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        chargerSimulations(); // Recharger la liste
                        // Retirer la simulation de la liste sélectionnée si elle y était
                        const index = selectedSimulations.indexOf(id);
                        if (index !== -1) {
                            selectedSimulations.splice(index, 1);
                            updateCompareSection();
                        }
                        alert('Simulation supprimée avec succès');
                    } else {
                        alert('Erreur lors de la suppression: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion lors de la suppression');
                });
            }
        }

        // Utilitaire pour formater les montants
        function formatMontant(montant) {
            return new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2
            }).format(montant) + ' Ar';
        }

        // Met à jour l'affichage du taux d'intérêt mensuel
        function updateTauxDisplay(typePretId) {
            const tauxMensuel = typePretTauxMap[typePretId];
            const tauxDiv = document.getElementById('taux-display');
            if (typeof tauxMensuel === 'number' && !isNaN(tauxMensuel)) {
                tauxDiv.textContent = `Taux d'intérêt mensuel : ${(tauxMensuel * 100).toFixed(2)} %`;
            } else {
                tauxDiv.textContent = '';
            }
        }

        // Mettre à jour le taux affiché lors du changement de type de prêt
        document.getElementById('type_pret_id').addEventListener('change', function() {
            updateTauxDisplay(this.value);
        });

        // Soumission du formulaire
        document.getElementById('pretForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const hasAssurance = document.getElementById('has_assurance').checked;
            if (hasAssurance) {
                formData.set('assurance', document.getElementById('assurance').value || 0);
            } else {
                formData.set('assurance', 0);
            }
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            // Ajoute le nom du type de prêt pour status_pret
            const typePretSelect = document.getElementById('type_pret_id');
            data.nom_type_pret = typePretSelect.options[typePretSelect.selectedIndex].text.split(' (')[0];
            fetch(window.apiBase + '/api/prets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(async res => {
                    let text = await res.text();
                    let dataRes;
                    try {
                        dataRes = JSON.parse(text);
                    } catch (e) {
                        document.getElementById('message').textContent = 'Réponse non JSON: ' + text;
                        document.getElementById('message').className = 'error';
                        return;
                    }                        if (dataRes.error) {
                            document.getElementById('message').textContent = (dataRes.error || 'Erreur') + ' | Debug: ' + JSON.stringify(dataRes);
                            document.getElementById('message').className = 'error';
                        } else {
                            document.getElementById('message').textContent = (dataRes.message || 'Ajout réussi') + ' | Debug: ' + JSON.stringify(dataRes);
                            document.getElementById('message').className = 'success';
                            if (dataRes.id) {
                                document.getElementById('pretForm').reset();
                                chargerSimulations(); // Recharger les simulations après ajout d'un prêt
                            }
                        }
                })
                .catch((err) => {
                    document.getElementById('message').textContent = 'Erreur lors de l\'ajout: ' + err;
                    document.getElementById('message').className = 'error';
                });
        });

        // Utilitaires de calcul
        function calculerMensualite(capital, tauxMensuel, duree) {
            if (tauxMensuel === 0) return capital / duree;
            return capital * (tauxMensuel / (1 - Math.pow(1 + tauxMensuel, -duree)));
        }

        function calculerPrimeAssurance(capital, tauxAssuranceAnnuel) {
            return (capital * tauxAssuranceAnnuel) / 12;
        }

        function genererAmortissement(capital, tauxMensuel, duree, mensualite, primeAssurance, delaiPremier) {
            let tableau = [];
            let capitalRestant = capital;
            for (let mois = 1; mois <= delaiPremier; mois++) {
                let interets = capitalRestant * tauxMensuel;
                capitalRestant += interets;
                tableau.push({
                    mois,
                    capitalRestant: Math.max(capitalRestant, 0),
                    interets,
                    amortissement: 0,
                    mensualite: 0,
                    assurance: primeAssurance,
                    montantTotal: primeAssurance > 0 ? primeAssurance : 0
                });
            }
            for (let mois = delaiPremier + 1; mois <= duree + delaiPremier; mois++) {
                let interets = capitalRestant * tauxMensuel;
                let amortissement = mensualite - interets;
                let assurance = primeAssurance;
                let montantTotal = mensualite + (assurance || 0);
                tableau.push({
                    mois,
                    capitalRestant: Math.max(capitalRestant, 0),
                    interets,
                    amortissement,
                    mensualite,
                    assurance,
                    montantTotal
                });
                capitalRestant -= amortissement;
                if (capitalRestant < 0) capitalRestant = 0;
            }
            return tableau;
        }

        function afficherSimulation(resultDiv, donnees, tableau, delaiPremier) {
            const {
                capital,
                tauxMensuel,
                duree,
                mensualite,
                primeAssurance,
                coutTotalAssurance
            } = donnees;

            let html = `
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #2d3748; margin-bottom: 1rem;">Simulation de prêt</h3>
            <table>
                <tr>
                    <th>Capital emprunté</th>
                    <th>Taux d'intérêt mensuel</th>
                    <th>Durée</th>
                    <th>Délai avant 1er remboursement</th>
                    <th>Mensualité constante</th>
                    <th>Coût total de l'assurance</th>
                </tr>
                <tr>
                    <td>${capital.toLocaleString('fr-FR')} Ar</td>
                    <td>${(tauxMensuel * 100).toFixed(2)} %</td>
                    <td>${duree} mois</td>
                    <td>${delaiPremier} mois</td>
                    <td>${mensualite.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                    <td>${coutTotalAssurance.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                </tr>
            </table>
            <div style="overflow-x: auto; margin-top: 1rem;">
            <table>
                <thead>
                    <tr>
                        <th>Mois</th>
                        <th>Capital restant</th>
                        <th>Intérêts</th>
                        <th>Amortissement</th>
                        <th>Mensualité</th>
                        ${primeAssurance > 0 ? '<th>Assurance</th><th>Montant total payé</th>' : ''}
                    </tr>
                </thead>
                <tbody>
        `;
            tableau.forEach(ligne => {
                html += `<tr>
                <td>${ligne.mois}</td>
                <td>${ligne.capitalRestant.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                <td>${ligne.interets.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                <td>${ligne.amortissement.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                <td>${ligne.mensualite.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                ${primeAssurance > 0 ? `
                    <td>${ligne.assurance.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                    <td>${ligne.montantTotal.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                ` : ''}
            </tr>`;
            });
            html += `</tbody></table></div>`;
            resultDiv.innerHTML = html;
        }

        // Gestionnaire du bouton "Simuler"
        document.getElementById('simulateur-btn').addEventListener('click', function() {
            document.getElementById('error-message').textContent = '';
            const capital = parseFloat(document.getElementById('montant').value);
            const duree = parseInt(document.getElementById('duree').value, 10);
            const typePretId = document.getElementById('type_pret_id').value;
            const clientId = document.getElementById('client_id').value;
            const tauxMensuel = typePretTauxMap[typePretId];
            const hasAssurance = document.getElementById('has_assurance').checked;
            const assuranceAnnuel = hasAssurance ? parseFloat(document.getElementById('assurance').value.replace(',', '.')) / 100 : 0;
            const delaiPremier = parseInt(document.getElementById('delai_premier').value, 10) || 0;

            const resultDiv = document.getElementById('simulation-result');
            resultDiv.innerHTML = '';

            // Validation
            if (!clientId) {
                document.getElementById('error-message').textContent = 'Veuillez sélectionner un client.';
                return;
            }
            if (isNaN(capital) || capital <= 0) {
                document.getElementById('error-message').textContent = 'Veuillez saisir un capital valide.';
                return;
            }
            if (isNaN(duree) || duree <= 0) {
                document.getElementById('error-message').textContent = 'Veuillez saisir une durée valide.';
                return;
            }
            if (!typePretId || typeof tauxMensuel !== 'number' || isNaN(tauxMensuel) || tauxMensuel < 0) {
                document.getElementById('error-message').textContent = 'Veuillez sélectionner un type de prêt.';
                return;
            }
            if (hasAssurance && (isNaN(assuranceAnnuel) || assuranceAnnuel < 0)) {
                document.getElementById('error-message').textContent = 'Veuillez saisir un taux d\'assurance valide.';
                return;
            }
            if (isNaN(delaiPremier) || delaiPremier < 0) {
                document.getElementById('error-message').textContent = 'Veuillez saisir un délai de 1er remboursement valide.';
                return;
            }

            const mensualite = calculerMensualite(
                capital * Math.pow(1 + tauxMensuel, delaiPremier),
                tauxMensuel,
                duree
            );
            const primeAssurance = assuranceAnnuel > 0 ? calculerPrimeAssurance(capital, assuranceAnnuel) : 0;
            const coutTotalAssurance = primeAssurance * (duree + delaiPremier);

            const tableau = genererAmortissement(
                capital,
                tauxMensuel,
                duree,
                mensualite,
                primeAssurance,
                delaiPremier
            );

            // Calculer le total des intérêts
            const totalInterets = tableau.reduce((sum, ligne) => sum + ligne.interets, 0);

            // Afficher la simulation
            afficherSimulation(resultDiv, {
                capital,
                tauxMensuel,
                duree,
                mensualite,
                primeAssurance,
                coutTotalAssurance
            }, tableau, delaiPremier);

            // Sauvegarder la simulation dans la base de données
            const simulationData = {
                client_id: parseInt(clientId),
                montant: capital,
                duree: duree,
                type_pret_id: parseInt(typePretId),
                taux_mensuel: tauxMensuel,
                delai_premier: delaiPremier,
                assurance: assuranceAnnuel * 100, // Convertir en pourcentage
                mensualite: mensualite,
                total_interets: totalInterets,
                cout_total_assurance: coutTotalAssurance
            };

            // Envoyer à l'API
            fetch(window.apiBase + '/api/simulations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(simulationData)
                })
                .then(response => {
                    console.log('Statut de la réponse:', response.status);
                    console.log('URL appelée:', window.apiBase + '/api/simulations');

                    // Vérifier si la réponse est OK
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    return response.text(); // Récupérer en text d'abord pour déboguer
                })
                .then(text => {
                    console.log('Réponse brute:', text);

                    try {
                        const data = JSON.parse(text);
                        console.log('Données parsées:', data);

                        if (data.success) {
                            // Ajouter un message de succès à la simulation
                            const successMsg = document.createElement('div');
                            successMsg.style.cssText = 'background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 6px; margin-top: 1rem; text-align: center;';
                            successMsg.textContent = '✅ Simulation sauvegardée avec succès (ID: ' + data.simulation_id + ')';
                            resultDiv.appendChild(successMsg);
                            
                            // Recharger la liste des simulations
                            chargerSimulations();
                        } else {
                            console.error('Erreur lors de la sauvegarde:', data.error);
                            // Afficher l'erreur à l'utilisateur
                            const errorMsg = document.createElement('div');
                            errorMsg.style.cssText = 'background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 6px; margin-top: 1rem; text-align: center;';
                            errorMsg.textContent = '❌ Erreur: ' + data.error;
                            resultDiv.appendChild(errorMsg);
                        }
                    } catch (e) {
                        console.error('Erreur de parsing JSON:', e);
                        console.error('Texte reçu:', text);

                        // Afficher l'erreur de parsing à l'utilisateur
                        const errorMsg = document.createElement('div');
                        errorMsg.style.cssText = 'background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 6px; margin-top: 1rem; text-align: center;';
                        errorMsg.textContent = '❌ Erreur de format de réponse. Voir la console pour plus de détails.';
                        resultDiv.appendChild(errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Erreur réseau lors de la sauvegarde:', error);

                    // Afficher l'erreur réseau à l'utilisateur
                    const errorMsg = document.createElement('div');
                    errorMsg.style.cssText = 'background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 6px; margin-top: 1rem; text-align: center;';
                    errorMsg.textContent = '❌ Erreur réseau: ' + error.message;
                    resultDiv.appendChild(errorMsg);
                });
        });
    </script>
</body>

</html>