<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajout d'un prêt</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 400px; margin: 40px auto; background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        h2 { text-align: center; }
        label { display: block; margin-top: 16px; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; border-radius: 4px; border: 1px solid #ccc; }
        button { margin-top: 20px; width: 100%; padding: 10px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { margin-top: 16px; text-align: center; }
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

            <label>
                <input type="checkbox" id="has_assurance" onchange="document.getElementById('assurance-group').style.display = this.checked ? 'block' : 'none';">
                Ajouter une assurance
            </label>
            <div id="assurance-group" style="display:none;">
                <input type="number" id="assurance" name="assurance" placeholder="Assurance (%)" min="0" max="100" step="0.01" value="0">
            </div>

            <button type="submit">Ajouter</button>
        </form>
        <div class="message" id="message"></div>
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

    // Charger la liste des types de prêt
    fetch(window.apiBase + '/type_prets')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('type_pret_id');
            data.forEach(e => {
                const option = document.createElement('option');
                option.value = e.id;
                option.textContent = `${e.nom} (${e.taux}%)`;
                select.appendChild(option);
            });
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
        fetch(window.apiBase + '/prets', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.error) {
                document.getElementById('message').textContent = data.error;
                document.getElementById('message').style.color = 'red';
            } else {
                document.getElementById('message').textContent = data.message || 'Erreur';
                document.getElementById('message').style.color = 'green';
                if(data.id) this.reset();
            }
        })
        .catch(() => {
            document.getElementById('message').textContent = 'Erreur lors de l\'ajout';
            document.getElementById('message').style.color = 'red';
        });
    });
    </script>
</body>
</html>
