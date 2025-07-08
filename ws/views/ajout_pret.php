<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout d'un prêt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Beau CSS pour le formulaire et la simulation */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #e0e7ff 0%, #f8fafc 100%);
            min-height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 480px;
            margin: 48px auto;
            background: #fff;
            padding: 32px 28px 28px 28px;
            border-radius: 18px;
            box-shadow: 0 6px 32px rgba(0, 123, 255, 0.08), 0 1.5px 6px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 24px;
            letter-spacing: 1px;
        }

        label {
            display: block;
            margin-top: 18px;
            font-weight: 500;
            color: #22223b;
            letter-spacing: 0.5px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #b6c4e0;
            background: #f6f8fa;
            font-size: 15px;
            transition: border 0.2s;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            border: 1.5px solid #2563eb;
            outline: none;
            background: #eef2fb;
        }

        button {
            margin-top: 22px;
            width: 100%;
            padding: 12px;
            background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37,99,235,0.08);
            transition: background 0.2s, transform 0.1s;
        }

        button:hover {
            background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 100%);
            transform: translateY(-2px) scale(1.01);
        }

        #taux-display {
            margin-top: 10px;
            font-weight: bold;
            color: #2563eb;
            font-size: 16px;
            letter-spacing: 0.5px;
            text-align: right;
        }

        #assurance-group {
            margin-top: 10px;
            padding: 10px 12px;
            background: #f1f5fb;
            border-radius: 6px;
            border: 1px solid #e0e7ff;
        }

        .message {
            margin-top: 18px;
            text-align: center;
            font-size: 15px;
        }

        #error-message.error, #simulation-result .error {
            color: #b91c1c;
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 5px;
            padding: 8px 0;
            margin: 12px 0;
            text-align: center;
            font-weight: 600;
        }

        #simulation-result {
            margin-top: 32px;
            animation: fadeIn 0.6s;
        }

        #simulation-result h3 {
            text-align: center;
            color: #2563eb;
            margin-bottom: 18px;
            font-size: 22px;
            letter-spacing: 1px;
        }

        #simulation-result table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
            background: #f8fafc;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(37,99,235,0.06);
        }

        #simulation-result th, #simulation-result td {
            border: 1px solid #e5e7eb;
            padding: 10px 8px;
            text-align: center;
            font-size: 15px;
        }

        #simulation-result th {
            background: linear-gradient(90deg, #2563eb 0%, #60a5fa 100%);
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        #simulation-result tr:nth-child(even) td {
            background: #f1f5fb;
        }

        #simulation-result tr:hover td {
            background: #e0e7ff;
            transition: background 0.2s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Ajouter un prêt</h2>
        <form id="pretForm">
            <label for="client_id">Client</label>
            <select id="client_id" name="client_id" required></select>

            <label for="montant">Montant</label>
            <input type="number" id="montant" name="montant" required min="1" step="0.01">

            <label for="duree">Durée (mois)</label>
            <input type="number" id="duree" name="duree" required min="1">

            <label for="type_remboursement_id">Type de remboursement</label>
            <select id="type_remboursement_id" name="type_remboursement_id" required></select>

            <label for="type_pret_id">Type de prêt</label>
            <select id="type_pret_id" name="type_pret_id" required></select>
            <div id="taux-display" style="margin-top:8px; font-weight:bold; color:#007bff;"></div>

            <label for="delai_premier">Délai avant 1er remboursement (mois)</label>
            <input type="number" id="delai_premier" name="delai_premier" min="0" value="0" style="margin-bottom:8px;">

            <label>
                <input type="checkbox" id="has_assurance" onchange="document.getElementById('assurance-group').style.display = this.checked ? 'block' : 'none';">
                Ajouter une assurance
            </label>
            <div id="assurance-group" style="display:none;">
                <label for="assurance">Assurance (% par an)</label>
                <input type="number" id="assurance" name="assurance" placeholder="Ex: 0.5 pour 0,5%" min="0" max="100" step="0.01" value="0">
            </div>

            <button type="button" id="simulateur-btn">Simuler</button>
            <button type="submit">Ajouter</button>
        </form>
        <div class="message" id="message"></div>
        <div id="error-message" class="error"></div>
        <div id="simulation-result"></div>
    </div>
    <script>
    window.apiBase = window.apiBase || "http://localhost/final/ws";

    // Charger la liste des clients
    fetch(window.apiBase + '/clients')
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
    fetch(window.apiBase + '/type_remboursements')
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

    // Stockage des taux par type de prêt (rempli dynamiquement)
    let typePretTauxMap = {};

    // Charger la liste des types de prêt et remplir le mapping id => taux
    fetch(window.apiBase + '/type_prets')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('type_pret_id');
            data.forEach(e => {
                const option = document.createElement('option');
                option.value = e.id;
                option.textContent = `${e.nom} (${e.taux}%)`;
                select.appendChild(option);
                // Stocker le taux mensuel (taux annuel / 12 / 100)
                typePretTauxMap[e.id] = parseFloat(e.taux) / 12 / 100;
            });
            // Afficher le taux du premier type de prêt si sélectionné
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
        // Ajoute l'assurance seulement si activée
        const hasAssurance = document.getElementById('has_assurance').checked;
        if (hasAssurance) {
            formData.set('assurance', document.getElementById('assurance').value || 0);
        } else {
            formData.set('assurance', 0);
        }
        // Correction : transformer FormData en objet simple pour POST JSON
        const data = {};
        formData.forEach((value, key) => {
            data[key] = value;
        });
        fetch(window.apiBase + '/prets', {
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
                document.getElementById('message').textContent = 'Réponse non JSON: ' + text;
                document.getElementById('message').style.color = 'red';
                return;
            }
            if(dataRes.error) {
                document.getElementById('message').textContent = (dataRes.error || 'Erreur') + ' | Debug: ' + JSON.stringify(dataRes);
                document.getElementById('message').style.color = 'red';
            } else {
                document.getElementById('message').textContent = (dataRes.message || 'Ajout réussi') + ' | Debug: ' + JSON.stringify(dataRes);
                document.getElementById('message').style.color = 'green';
                if(dataRes.id) document.getElementById('pretForm').reset();
            }
        })
        .catch((err) => {
            document.getElementById('message').textContent = 'Erreur lors de l\'ajout: ' + err;
            document.getElementById('message').style.color = 'red';
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
        // Ajout des mois de différé (pas de remboursement, mais intérêts courus)
        for (let mois = 1; mois <= delaiPremier; mois++) {
            let interets = capitalRestant * tauxMensuel;
            capitalRestant += interets; // Les intérêts s'ajoutent au capital (capitalisation)
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
        // Puis les remboursements classiques
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
            <h3>Simulation de prêt</h3>
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
            <br>
            <div style="overflow-x:auto">
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

        // Validation
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

        // Attention : la mensualité doit être calculée sur la durée réelle de remboursement (hors différé)
        const mensualite = calculerMensualite(
            capital * Math.pow(1 + tauxMensuel, delaiPremier), // capitalisé pendant le différé
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
