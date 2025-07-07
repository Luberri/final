<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .login-container {
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    input,
    button {
      margin: 10px 0;
      padding: 12px;
      width: 100%;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      margin-top: 10px;
      padding: 10px;
      background-color: #f8d7da;
      border-radius: 4px;
    }

    .success {
      color: green;
      margin-top: 10px;
      padding: 10px;
      background-color: #d4edda;
      border-radius: 4px;
    }

    .login-info {
      background-color: #e9ecef;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .login-info h4 {
      margin: 0 0 10px 0;
      color: #495057;
    }
  </style>
</head>

<body>

  <div class="login-container">
    <h1>Login Admin</h1>

    <div class="login-info">
      <h4>Compte de test :</h4>
      <div><strong>Nom:</strong> admin1</div>
      <div><strong>Mot de passe:</strong> admin123</div>
    </div>

    <form id="loginForm">
      <input type="text" id="nom" placeholder="Nom d'utilisateur" required>
      <input type="password" id="mdp" placeholder="Mot de passe" required>
      <button type="submit">Se connecter</button>
    </form>

    <div id="message"></div>
  </div>

  <script>
    const apiBase = "http://localhost/final/ws";

    // Dès que la page de connexion se charge, expirer toute session existante
    window.onload = function() {
      expireExistingSession();
    };

    function expireExistingSession() {
      // Forcer l'expiration de toute session existante
      ajax('POST', '/expire-session', null, function(response) {
        console.log('Session expirée:', response.message);
      });
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const nom = document.getElementById('nom').value;
      const mdp = document.getElementById('mdp').value;

      if (!nom || !mdp) {
        showMessage('Veuillez remplir tous les champs', 'error');
        return;
      }

      const data = `nom=${encodeURIComponent(nom)}&mdp=${encodeURIComponent(mdp)}`;

      ajax('POST', '/login', data, function(response) {
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
      messageDiv.className = type;
    }
  </script>

</body>

</html>