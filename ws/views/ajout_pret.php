<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un prêt</title>
    <style>
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            max-width: 700px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h2 {
            font-size: 2rem;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 600;
            position: relative;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.9rem;
            color: #34495e;
            font-weight: 600;
            margin-bottom: 0.5rem;
            letter-spacing: 0.5px;
        }

        select, 
        input[type="number"], 
        input[type="text"] {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fff;
            color: #2c3e50;
        }

        select:focus, 
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        select:hover, 
        input:hover {
            border-color: #bdc3c7;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .checkbox-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
        }

        .checkbox-container label {
            margin-bottom: 0;
            cursor: pointer;
            user-select: none;
        }

        .assurance-group {
            display: none;
            margin-top: 1rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .assurance-group.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .taux-display {
            margin-top: 0.75rem;
            padding: 0.75rem;
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-radius: 6px;
            font-weight: 600;
            color: #1565c0;
            font-size: 0.9rem;
            border-left: 4px solid #1565c0;
        }

        .button-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        button {
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        button:hover::before {
            left: 100%;
        }

        .btn-simulate {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .btn-simulate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .message-container {
            margin-top: 1.5rem;
        }

        .message {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            margin-bottom: 0.5rem;
            display: none;
        }

        .message.show {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .simulation-result {
            margin-top: 2rem;
        }

        .simulation-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ecf0f1;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .summary-table th,
        .summary-table td {
            padding: 0.875rem;
            text-align: center;
            font-size: 0.9rem;
            border-bottom: 1px solid #ecf0f1;
        }

        .summary-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-table td {
            font-weight: 500;
            color: #2c3e50;
        }

        .amortization-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 8px;
        }

        .amortization-table th,
        .amortization-table td {
            padding: 0.75rem;
            text-align: right;
            font-size: 0.85rem;
            border-bottom: 1px solid #ecf0f1;
        }

        .amortization-table th {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .amortization-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .amortization-table tbody tr:hover {
            background: #e9ecef;
            transition: background 0.2s ease;
        }

        .amortization-table td {
            font-weight: 500;
            color: #495057;
        }

        .amortization-table .month-cell {
            text-align: center;
            font-weight: 600;
            color: #667eea;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .button-group {
                grid-template-columns: 1fr;
            }

            .summary-table th,
            .summary-table td {
                padding: 0.5rem;
                font-size: 0.8rem;
            }

            .amortization-table th,
            .amortization-table td {
                padding: 0.5rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 0.5rem;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un prêt</h2>
        <form id="pretForm">
            <div class="form-group">
                <label for="client_id">Client</label>
                <select id="client_id" name="client_id" required></select>
            </div>

            <div class="form-group">
                <label for="montant">Montant</label>
                <input type="number" id="montant" name="montant" required min="1" step="0.01">
            </div>

            <div class="form-group">
                <label for="duree">Durée (mois)</label>
                <input type="number" id="duree" name="duree" required min="1">
            </div>

            <div class="form-group">
                <label for="type_remboursement_id">Type de remboursement</label>
                <select id="type_remboursement_id" name="type_remboursement_id" required></select>
            </div>

            <div class="form-group">
                <label for="type_pret_id">Type de prêt</label>
                <select id="type_pret_id" name="type_pret_id" required></select>
                <div id="taux-display" class="taux-display"></div>
            </div>

            <div class="form-group">
                <label for="delai_premier">Délai avant 1er remboursement (mois)</label>
                <input type="number" id="delai_premier" name="delai_premier" min="0" value="0">
            </div>

            <div class="form-group">
                <div class="checkbox-container">
                    <input type="checkbox" id="has_assurance">
                    <label for="has_assurance">Ajouter une assurance</label>
                </div>
                <div id="assurance-group" class="assurance-group">
                    <label for="assurance">Assurance (% par an)</label>
                    <input type="number" id="assurance" name="assurance" placeholder="Ex: 0.5 pour 0,5%" min="0" max="100" step="0.01" value="0">
                </div>
            </div>

            <div class="button-group">
                <button type="button" id="simulateur-btn" class="btn-simulate">Simuler</button>
                <button type="submit" class="btn-submit">Ajouter</button>
            </div>
        </form>

        <div class="message-container">
            <div id="message" class="message"></div>
            <div id="error-message" class="message error"></div>
        </div>
        <div id="simulation-result" class="simulation-result"></div>
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
            tauxDiv.style.display = 'block';
        } else {
            tauxDiv.style.display = 'none';
        }
    }

    // Gestion de l'affichage de l'assurance
    document.getElementById('has_assurance').addEventListener('change', function() {
        const assuranceGroup = document.getElementById('assurance-group');
        if (this.checked) {
            assuranceGroup.classList.add('show');
        } else {
            assuranceGroup.classList.remove('show');
        }
    });

    // Mettre à jour le taux affiché lors du changement de type de prêt
    document.getElementById('type_pret_id').addEventListener('change', function() {
        updateTauxDisplay(this.value);
    });

    // Fonction pour afficher les messages
    function showMessage(elementId, message, isError = false) {
        const element = document.getElementById(elementId);
        element.textContent = message;
        element.className = isError ? 'message error show' : 'message success show';
        setTimeout(() => {
            element.classList.remove('show');
        }, 5000);
    }

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
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(async res => {
            let text = await res.text();
            let dataRes;
            try {
                dataRes = JSON.parse(text);
            } catch (e) {
                showMessage('message', 'Réponse non JSON: ' + text, true);
                return;
            }
            if(dataRes.error) {
                showMessage('message', (dataRes.error || 'Erreur') + ' | Debug: ' + JSON.stringify(dataRes), true);
            } else {
                showMessage('message', (dataRes.message || 'Ajout réussi') + ' | Debug: ' + JSON.stringify(dataRes), false);
                if(dataRes.id) document.getElementById('pretForm').reset();
            }
        })
        .catch((err) => {
            showMessage('message', 'Erreur lors de l\'ajout: ' + err, true);
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
            capital, tauxMensuel, duree, mensualite, primeAssurance, coutTotalAssurance
        } = donnees;

        let html = `
            <h3 class="simulation-title">Simulation de prêt</h3>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Capital emprunté</th>
                        <th>Taux mensuel</th>
                        <th>Durée</th>
                        <th>Délai 1er remboursement</th>
                        <th>Mensualité</th>
                        <th>Coût assurance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${capital.toLocaleString('fr-FR')} Ar</td>
                        <td>${(tauxMensuel * 100).toFixed(2)} %</td>
                        <td>${duree} mois</td>
                        <td>${delaiPremier} mois</td>
                        <td>${mensualite.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                        <td>${coutTotalAssurance.toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</td>
                    </tr>
                </tbody>
            </table>
            <div class="table-container">
                <table class="amortization-table">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Capital restant</th>
                            <th>Intérêts</th>
                            <th>Amortissement</th>
                            <th>Mensualité</th>
                            ${primeAssurance > 0 ? '<th>Assurance</th><th>Total à payer</th>' : ''}
                        </tr>
                    </thead>
                    <tbody>
        `;
        tableau.forEach(ligne => {
            html += `<tr>
                <td class="month-cell">${ligne.mois}</td>
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
        document.getElementById('error-message').classList.remove('show');
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
            showMessage('error-message', 'Veuillez saisir un capital valide.', true);
            return;
        }
        if (isNaN(duree) || duree <= 0) {
            showMessage('error-message', 'Veuillez saisir une durée valide.', true);
            return;
        }
        if (!typePretId || typeof tauxMensuel !== 'number' || isNaN(tauxMensuel) || tauxMensuel < 0) {
            showMessage('error-message', 'Veuillez sélectionner un type de prêt.', true);
            return;
        }
        if (hasAssurance && (isNaN(assuranceAnnuel) || assuranceAnnuel < 0)) {
            showMessage('error-message', 'Veuillez saisir un taux d\'assurance valide.', true);
            return;
        }
        if (isNaN(delaiPremier) || delaiPremier < 0) {
            showMessage('error-message', 'Veuillez saisir un délai de 1er remboursement valide.', true);
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
            capital, tauxMensuel, duree, mensualite, primeAssurance, coutTotalAssurance
        }, tableau, delaiPremier);
    });
    </script>
</body>
</html>