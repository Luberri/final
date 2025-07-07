<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajout de fond (admin)</title>
  <style>
    body { font-family: sans-serif; padding: 20px; }
    input, button { margin: 5px; padding: 5px; }
    #fond-success, #pret-success { color: green; }
    #fond-error, #pret-error { color: red; }
  </style>
</head>
<body>
  <h1>Ajout de fond à l'établissement</h1>
  <input type="number" id="fond-montant" placeholder="Montant" step="0.01" min="0">
  <input type="text" id="fond-detail" placeholder="Détail">
  <button onclick="ajouterFond()">Ajouter le fond</button>
  <span id="fond-success"></span>
  <span id="fond-error"></span>
  <br><br>

  <h2>Création d'un type de prêt</h2>
  <input type="text" id="pret-nom" placeholder="Nom du type de prêt">
  <input type="text" id="pret-detail" placeholder="Détail du type de prêt">
  <input type="number" id="pret-taux" placeholder="Taux (%)" step="0.01" min="0">
  <button onclick="ajouterTypePret()">Créer le type de prêt</button>
  <span id="pret-success"></span>
  <span id="pret-error"></span>
  <br><br>
  <a href="index.html">Retour à l'accueil</a>
  <script>
    window.apiBase = window.apiBase || "http://localhost/final/ws";
    window.ajouterFond = function() {
      const montant = document.getElementById('fond-montant').value;
      const detail = document.getElementById('fond-detail').value;
      fetch(window.apiBase + '/fonds', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ montant, detail })
      })
      .then(r => r.json())
      .then(res => {
        if(res.success) {
          document.getElementById('fond-success').textContent = 'Fond ajouté !';
          document.getElementById('fond-error').textContent = '';
          document.getElementById('fond-montant').value = '';
          document.getElementById('fond-detail').value = '';
        } else {
          document.getElementById('fond-error').textContent = res.error || 'Erreur.';
          document.getElementById('fond-success').textContent = '';
        }
      })
      .catch(() => {
        document.getElementById('fond-error').textContent = 'Erreur réseau.';
        document.getElementById('fond-success').textContent = '';
      });
    }

    window.ajouterTypePret = function() {
      const nom = document.getElementById('pret-nom').value;
      const detail = document.getElementById('pret-detail').value;
      const taux = document.getElementById('pret-taux').value;
      fetch(window.apiBase + '/typeprets', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nom, detail, taux })
      })
      .then(r => r.json())
      .then(res => {
        if(res.success) {
          document.getElementById('pret-success').textContent = 'Type de prêt créé !';
          document.getElementById('pret-error').textContent = '';
          document.getElementById('pret-nom').value = '';
          document.getElementById('pret-detail').value = '';
          document.getElementById('pret-taux').value = '';
        } else {
          document.getElementById('pret-error').textContent = res.error || 'Erreur.';
          document.getElementById('pret-success').textContent = '';
        }
      })
      .catch(() => {
        document.getElementById('pret-error').textContent = 'Erreur réseau.';
        document.getElementById('pret-success').textContent = '';
      });
    }
  </script>
</body>
</html>
