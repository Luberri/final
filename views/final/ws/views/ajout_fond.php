<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajout de fond (admin)</title>
  <style>
    .container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      padding: 40px;
      max-width: 600px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .header {
      text-align: center;
      margin-bottom: 40px;
    }

    .main-title {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
      margin-bottom: 8px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .subtitle {
      font-size: 1.1rem;
      color: #718096;
      font-weight: 400;
    }

    .section {
      margin-bottom: 40px;
      padding: 30px;
      background: rgba(255, 255, 255, 0.7);
      border-radius: 16px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .section:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .icon {
      width: 24px;
      height: 24px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 14px;
      font-weight: bold;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: 500;
      color: #4a5568;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .form-input {
      width: 100%;
      padding: 16px 20px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
    }

    .form-input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      background: white;
    }

    .form-input::placeholder {
      color: #a0aec0;
    }

    .submit-btn {
      width: 100%;
      padding: 16px;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .submit-btn::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .submit-btn:active::after {
      width: 300px;
      height: 300px;
    }

    .message-container {
      margin-top: 16px;
      min-height: 20px;
    }

    .message {
      padding: 12px 16px;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 500;
      text-align: center;
      opacity: 0;
      transform: translateY(-10px);
      transition: all 0.3s ease;
    }

    .message.show {
      opacity: 1;
      transform: translateY(0);
    }

    .success {
      background: rgba(72, 187, 120, 0.1);
      color: #2f855a;
      border: 1px solid rgba(72, 187, 120, 0.2);
    }

    .error {
      background: rgba(245, 101, 101, 0.1);
      color: #c53030;
      border: 1px solid rgba(245, 101, 101, 0.2);
    }

    .return-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: #667eea;
      font-weight: 500;
      padding: 12px 24px;
      border-radius: 10px;
      background: rgba(102, 126, 234, 0.1);
      transition: all 0.3s ease;
      margin-top: 30px;
    }

    .return-link:hover {
      background: rgba(102, 126, 234, 0.2);
      transform: translateX(-5px);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    @media (max-width: 768px) {
      .container {
        padding: 20px;
        margin: 10px;
      }

      .main-title {
        font-size: 2rem;
      }

      .section {
        padding: 20px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .main-title {
        font-size: 1.8rem;
      }

      .section {
        padding: 16px;
      }

      .form-input {
        padding: 12px 16px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="section">
      <h2 class="section-title">
        <span class="icon">€</span>
        Ajout de fond à l'établissement
      </h2>
      <div class="form-group">
        <label class="form-label">Montant</label>
        <input type="number" id="fond-montant" class="form-input" placeholder="Entrez le montant" step="0.01" min="0">
      </div>
      <div class="form-group">
        <label class="form-label">Détail</label>
        <input type="text" id="fond-detail" class="form-input" placeholder="Description du fond">
      </div>
      <button class="submit-btn" onclick="ajouterFond()">Ajouter le fond</button>
      <div class="message-container">
        <div id="fond-success" class="message success"></div>
        <div id="fond-error" class="message error"></div>
      </div>
    </div>

    <div class="section">
      <h2 class="section-title">
        <span class="icon">%</span>
        Création d'un type de prêt
      </h2>
      <div class="form-group">
        <label class="form-label">Nom du type de prêt</label>
        <input type="text" id="pret-nom" class="form-input" placeholder="Ex: Prêt personnel">
      </div>
      <div class="form-group">
        <label class="form-label">Détail du type de prêt</label>
        <input type="text" id="pret-detail" class="form-input" placeholder="Description détaillée">
      </div>
      <div class="form-group">
        <label class="form-label">Taux d'intérêt (%)</label>
        <input type="number" id="pret-taux" class="form-input" placeholder="Ex: 3.5" step="0.01" min="0">
      </div>
      <button class="submit-btn" onclick="ajouterTypePret()">Créer le type de prêt</button>
      <div class="message-container">
        <div id="pret-success" class="message success"></div>
        <div id="pret-error" class="message error"></div>
      </div>
    </div>

    <a href="index.html" class="return-link">
      ← Retour à l'accueil
    </a>
  </div>

  <script>
    const apiBase = "/ETU003192/t/final/ws";

    function showMessage(element, message, isSuccess = true) {
      const messageEl = document.getElementById(element);
      messageEl.textContent = message;
      messageEl.classList.add('show');
      
      // Cacher l'autre message
      const otherEl = document.getElementById(element.includes('success') ? element.replace('success', 'error') : element.replace('error', 'success'));
      otherEl.classList.remove('show');
      
      // Auto-hide après 5 secondes
      setTimeout(() => {
        messageEl.classList.remove('show');
      }, 5000);
    }

    function ajouterFond() {
      const montant = document.getElementById('fond-montant').value;
      const detail = document.getElementById('fond-detail').value;
      
      if (!montant || !detail) {
        showMessage('fond-error', 'Veuillez remplir tous les champs', false);
        return;
      }

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
          showMessage('fond-error', 'Réponse non JSON: ' + text, false);
          return;
        }
        if(res.success) {
          showMessage('fond-success', 'Fond ajouté avec succès !', true);
          document.getElementById('fond-montant').value = '';
          document.getElementById('fond-detail').value = '';
        } else {
          showMessage('fond-error', res.error || 'Erreur lors de l\'ajout', false);
        }
      })
      .catch((err) => {
        showMessage('fond-error', 'Erreur réseau: ' + err.message, false);
      });
    }

    function ajouterTypePret() {
      const nom = document.getElementById('pret-nom').value;
      const detail = document.getElementById('pret-detail').value;
      const taux = document.getElementById('pret-taux').value;
      
      if (!nom || !detail || !taux) {
        showMessage('pret-error', 'Veuillez remplir tous les champs', false);
        return;
      }

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
          showMessage('pret-error', 'Réponse non JSON: ' + text, false);
          return;
        }
        if(res.success) {
          showMessage('pret-success', 'Type de prêt créé avec succès !', true);
          document.getElementById('pret-nom').value = '';
          document.getElementById('pret-detail').value = '';
          document.getElementById('pret-taux').value = '';
        } else {
          showMessage('pret-error', res.error || 'Erreur lors de la création', false);
        }
      })
      .catch((err) => {
        showMessage('pret-error', 'Erreur réseau: ' + err.message, false);
      });
    }
  </script>
</body>
</html>