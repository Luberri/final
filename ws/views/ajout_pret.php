<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un prêt</title>
    <style>
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            max-width: 600px;
            width: 100%;
        }

        h2 {
            font-size: 1.5rem;
            color: #1a202c;
            text-align: center;
            margin-bottom: 1.5rem;
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

        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Ajouter un prêt</h2>
        <form id="pretForm">
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

    <script>
        window.apiBase = window.apiBase || "http://localhost/final/ws";

        // Charger la liste des clients
        fetch(window.apiBase + '/api/clients')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('client_id');
                data.forEach(e => {
                    const option = document.createElement('option');
                    option.value = e.id;
                    option.textContent = e.nom + ' ' + e.prenom;
                    select.appendChild(option);
                });
            });

        // Charger la liste des types de remboursement
        fetch(window.apiBase + '/api/type_remboursements')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('type_remboursement_id');
                data.forEach(e => {
                    const option = document.createElement('option');
                    option.value = e.id;
                    option.textContent = e.nom;
                    select.appendChild(option);
                });
            });

        // Stockage des taux par type de prêt
        let typePretTauxMap = {};

        // Charger la liste des types de prêt et remplir le mapping id => taux
        fetch(window.apiBase + '/api/type_prets')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('type_pret_id');
                data.forEach(e => {
                    const option = document.createElement('option');
                    option.value = e.id;
                    option.textContent = `${e.nom} (${e.taux}%)`;
                    select.appendChild(option);
                    typePretTauxMap[e.id] = parseFloat(e.taux) / 12 / 100;
                });
                if (select.value) updateTauxDisplay(select.value);
            });

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
                    }
                    if (dataRes.error) {
                        document.getElementById('message').textContent = (dataRes.error || 'Erreur') + ' | Debug: ' + JSON.stringify(dataRes);
                        document.getElementById('message').className = 'error';
                    } else {
                        document.getElementById('message').textContent = (dataRes.message || 'Ajout réussi') + ' | Debug: ' + JSON.stringify(dataRes);
                        document.getElementById('message').className = 'success';
                        if (dataRes.id) document.getElementById('pretForm').reset();
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
            const tauxMensuel = typePretTauxMap[typePretId];
            const hasAssurance = document.getElementById('has_assurance').checked;
            const assuranceAnnuel = hasAssurance ? parseFloat(document.getElementById('assurance').value.replace(',', '.')) / 100 : 0;
            const delaiPremier = parseInt(document.getElementById('delai_premier').value, 10) || 0;

            const resultDiv = document.getElementById('simulation-result');
            resultDiv.innerHTML = '';

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

            afficherSimulation(resultDiv, {
                capital,
                tauxMensuel,
                duree,
                mensualite,
                primeAssurance,
                coutTotalAssurance
            }, tableau, delaiPremier);
        });
    </script>
</body>

</html>