<?php 
// Suppression de l'inclusion inutile de FondController.php ici
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: 0;
      padding: 1rem;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
      pointer-events: none;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      padding: 2.5rem;
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 420px;
      text-align: center;
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.3);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 35px 70px rgba(0, 0, 0, 0.25);
    }

    .login-header {
      margin-bottom: 2rem;
    }

    .admin-icon {
      width: 60px;
      height: 60px;
      margin: 0 auto 1rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    h1 {
      font-size: 2rem;
      background: linear-gradient(135deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.5rem;
      font-weight: 700;
    }

    .subtitle {
      color: #64748b;
      font-size: 0.9rem;
      margin-bottom: 2rem;
    }

    .login-info {
      background: linear-gradient(135deg, #f8fafc, #e2e8f0);
      padding: 1.25rem;
      border-radius: 12px;
      margin-bottom: 2rem;
      font-size: 0.875rem;
      color: #475569;
      text-align: left;
      border: 1px solid rgba(148, 163, 184, 0.2);
      position: relative;
      overflow: hidden;
    }

    .login-info::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .login-info h4 {
      margin: 0 0 0.75rem 0;
      font-size: 1rem;
      color: #1e293b;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .login-info h4::before {
      content: '🔑';
      font-size: 0.875rem;
    }

    .login-info div {
      margin-bottom: 0.5rem;
      padding: 0.5rem;
      background: rgba(255, 255, 255, 0.6);
      border-radius: 6px;
      border-left: 3px solid #667eea;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .input-group {
      position: relative;
    }

    .input-group::before {
      content: '';
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      width: 20px;
      height: 20px;
      background-size: contain;
      background-repeat: no-repeat;
      z-index: 1;
      opacity: 0.5;
    }

    .input-group:nth-child(1)::before {
      background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>');
    }

    .input-group:nth-child(2)::before {
      background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>');
    }

    input {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
    }

    input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    input::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    button {
      width: 100%;
      padding: 1rem;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: #ffffff;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    button:hover::before {
      left: 100%;
    }

    button:active {
      transform: translateY(0);
    }

    .message {
      margin-top: 1.5rem;
      padding: 1rem;
      border-radius: 12px;
      font-size: 0.875rem;
      text-align: center;
      font-weight: 500;
      opacity: 0;
      transform: translateY(10px);
      transition: all 0.3s ease;
    }

    .message.show {
      opacity: 1;
      transform: translateY(0);
    }

    .error {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #dc2626;
      border: 1px solid #fca5a5;
    }

    .success {
      background: linear-gradient(135deg, #dcfce7, #bbf7d0);
      color: #15803d;
      border: 1px solid #86efac;
    }

    .loading {
      position: relative;
      pointer-events: none;
    }

    .loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid #ffffff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: translate(-50%, -50%) rotate(0deg); }
      100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .floating-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      overflow: hidden;
    }

    .shape {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
    }

    .shape:nth-child(1) {
      width: 80px;
      height: 80px;
      top: 10%;
      left: 10%;
      animation-delay: 0s;
    }

    .shape:nth-child(2) {
      width: 60px;
      height: 60px;
      top: 20%;
      right: 10%;
      animation-delay: 2s;
    }

    .shape:nth-child(3) {
      width: 40px;
      height: 40px;
      bottom: 20%;
      left: 20%;
      animation-delay: 4s;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-20px); }
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 2rem;
        margin: 1rem;
      }
      h1 {
        font-size: 1.75rem;
      }
      .admin-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
      }
    }
  </style>
</head>
<body>
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
  </div>
  
  <div class="login-container">
    <div class="login-header">
      <div class="admin-icon">👤</div>
      <h1>Admin Panel</h1>
      <div class="subtitle">Connectez-vous pour accéder au tableau de bord</div>
    </div>
    
    <div class="login-info">
      <h4>Compte de test</h4>
      <div><strong>Nom :</strong> admin1</div>
      <div><strong>Mot de passe :</strong> admin123</div>
    </div>
    
    <form id="loginForm">
      <div class="input-group">
        <input type="text" id="nom" placeholder="Nom d'utilisateur" required>
      </div>
      <div class="input-group">
        <input type="password" id="mdp" placeholder="Mot de passe" required>
      </div>
      <button type="submit" id="submitBtn">Se connecter</button>
    </form>
    
    <div id="message" class="message"></div>
  </div>

  <script>
    const apiBase = "/ETU003192/t/final/ws";
    
    window.onload = function() {
      expireExistingSession();
    };
    
    function expireExistingSession() {
      ajax('POST', '/expire-session', null, function(response) {
        console.log('Session expirée:', response.message);
      });
    }
    
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const nom = document.getElementById('nom').value;
      const mdp = document.getElementById('mdp').value;
      const submitBtn = document.getElementById('submitBtn');
      
      if (!nom || !mdp) {
        showMessage('Veuillez remplir tous les champs', 'error');
        return;
      }
      
      // Animation de chargement
      submitBtn.classList.add('loading');
      submitBtn.textContent = '';
      
      const data = `nom=${encodeURIComponent(nom)}&mdp=${encodeURIComponent(mdp)}`;
      
      ajax('POST', '/login', data, function(response) {
        submitBtn.classList.remove('loading');
        submitBtn.textContent = 'Se connecter';
        
        if (response.success) {
          showMessage(`Connexion réussie! Bienvenue ${response.admin.nom}`, 'success');
          setTimeout(() => {
            window.location.href = 'ws/dashboard';
          }, 1000);
        } else {
          showMessage(response.error || 'Erreur de connexion', 'error');
        }
      });
    });
    
    function ajax(method, url, data, callback) {
      const xhr = new XMLHttpRequest();
      xhr.open(method, apiBase + url, true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
          try {
            const response = JSON.parse(xhr.responseText);
            callback(response);
          } catch (e) {
            callback({
              error: 'Erreur de communication'
            });
          }
        }
      };
      xhr.send(data);
    }
    
    function showMessage(text, type) {
      const messageDiv = document.getElementById('message');
      messageDiv.textContent = text;
      messageDiv.className = `message ${type}`;
      messageDiv.classList.add('show');
      
      setTimeout(() => {
        messageDiv.classList.remove('show');
      }, 5000);
    }
    
    // Effets d'animation sur les champs
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
      });
      
      input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
      });
    });
  </script>
</body>
</html>