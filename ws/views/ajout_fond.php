<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajout de fond (admin)</title>
  <style>
    .container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding: 1.5rem;
      max-width: 500px;
      width: 100%;
    }
    h1, h2 {
      font-size: 1.5rem;
      color: #1a202c;
      text-align: center;
      margin-bottom: 1.5rem;
    }
    h2 {
      font-size: 1.25rem;
      margin-top: 2rem;
    }
    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }
    input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      font-size: 1rem;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    input:focus {
      outline: none;
      border-color: #3182ce;
      box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.2);
    }
    button {
      width: 100%;
      padding: 0.75rem;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      background-color: #3182ce;
      color: #fff;
      transition: background-color 0.2s;
    }
    button:hover {
      background-color: #2b6cb0;
    }
    .message {
      text-align: center;
      font-size: 0.875rem;
      margin-top: 0.5rem;
    }
    .success {
      color: #2f855a;
    }
    .error {
      color: #c53030;
    }
    .return-link {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: #3182ce;
      text-decoration: none;
      font-size: 0.875rem;
    }
    .return-link:hover {
      text-decoration: underline;
    }
    @media (max-width: 600px) {
      .container {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Ajout de fond à l'établissement</h1>
    <div class="form-group">
      <input type="number" id="fond-montant" placeholder="Montant" step="0.01" min="0">
      <input type="text" id="fond-detail" placeholder="Détail">
      <button onclick="ajouterFond()">Ajouter le fond</button>
      <div class="message">
        <span id="fond-success" class="success"></span>
        <span id="fond-error" class="error"></span>
      </div>
    </div>

    <h2>Création d'un type de prêt</h2>
    <div class="form-group">
      <input type="text" id="pret-nom" placeholder="Nom du type de prêt">
      <input type="text" id="pret-detail" placeholder="Détail du type de prêt">
      <input type="number" id="pret-taux" placeholder="Taux (%)" step="0.01" min="0">
      <button onclick="ajouterTypePret()">Créer le type de prêt</button>
      <div class="message">
        <span id="pret-success" class="success"></span>
        <span id="pret-error" class="error"></span>
      </div>
    </div>

    <a href="index.html" class="return-link">Retour à l'accueil</a>
  </div>

  <script>
    const apiBase = "http://localhost/final/ws";
    function ajouterFond() {
      const montant = document.getElementById('fond-montant').value;
      const detail = document.getElementById('fond-detail').value;
      fetch(apiBase + '/fonds', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ montant, detail })
      })
      .then(async r => {
        let text = await r.text();
        let res;
        try {
          res = JSON.parse(text);
        } catch (e) {
          document.getElementById('fond-error').textContent = 'Réponse non JSON: ' + text;
          document.getElementById('fond-success').textContent = '';
          return;
        }
        if(res.success) {
          document.getElementById('fond-success').textContent = 'Fond ajouté !';
          document.getElementById('fond-error').textContent = '';
          document.getElementById('fond-montant').value = '';
          document.getElementById('fond-detail').value = '';
        } else {
          document.getElementById('fond-error').textContent = (res.error || 'Erreur.') + ' | Debug: ' + JSON.stringify(res);
          document.getElementById('fond-success').textContent = '';
        }
      })
      .catch((err) => {
        document.getElementById('fond-error').textContent = 'Erreur réseau: ' + err;
        document.getElementById('fond-success').textContent = '';
      });
    }

    function ajouterTypePret() {
      const nom = document.getElementById('pret-nom').value;
      const detail = document.getElementById('pret-detail').value;
      const taux = document.getElementById('pret-taux').value;
      fetch(apiBase + '/typeprets', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom, detail, taux })
      })
      .then(async r => {
        let text = await r.text();
        let res;
        try {
          res = JSON.parse(text);
        } catch (e) {
          document.getElementById('pret-error').textContent = 'Réponse non JSON: ' + text;
          document.getElementById('pret-success').textContent = '';
          return;
        }
        if(res.success) {
          document.getElementById('pret-success').textContent = 'Type de prêt créé !';
          document.getElementById('pret-error').textContent = '';
          document.getElementById('pret-nom').value = '';
          document.getElementById('pret-detail').value = '';
          document.getElementById('pret-taux').value = '';
        } else {
          document.getElementById('pret-error').textContent = (res.error || 'Erreur.') + ' | Debug: ' + JSON.stringify(res);
          document.getElementById('pret-success').textContent = '';
        }
      })
      .catch((err) => {
        document.getElementById('pret-error').textContent = 'Erreur réseau: ' + err;
        document.getElementById('pret-success').textContent = '';
      });
    }
  </script>
</body>
</html>