<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
            margin: 0;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        button {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c82333;
        }

        .welcome {
            color: #28a745;
            margin: 0;
        }

        .menu {
            margin-top: 20px;
        }

        .menu-item {
            display: inline-block;
            margin: 10px;
            padding: 15px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .menu-item:hover {
            background-color: #0056b3;
        }

        .info {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1 class="welcome">Bienvenue dans le Dashboard Admin</h1>
        <div>
            <span id="adminName"></span>
            <button onclick="logout()">Se déconnecter</button>
        </div>
    </div>

    <div class="dashboard-container">
        <h2>Menu Principal</h2>

        <div class="menu">
            <a href="#" class="menu-item" onclick="loadEtudiants()">Gestion des Étudiants</a>
            <a href="prets" class="menu-item">Gestion des Prêts</a>
            <a href="#" class="menu-item" onclick="loadClients()">Gestion des Clients</a>
            <a href="interets" class="menu-item">Intérêts Gagnés</a>
            <a href="#" class="menu-item" onclick="loadStatistiques()">Statistiques</a>
        </div>

        <div class="info">
            <h3>Informations du système</h3>
            <div id="systemInfo">
                <p>✅ Système de login fonctionnel</p>
                <p>✅ Routes API configurées</p>
                <p>⏳ Gestion des étudiants (à implémenter)</p>
                <p>⏳ Gestion des prêts (à implémenter)</p>
            </div>
        </div>

        <div id="content">
            <h3>Tableau de bord</h3>
            <p>Sélectionnez une option dans le menu pour commencer.</p>
        </div>
    </div>

    <script>
        const apiBase = "http://localhost/final/ws";

        // Vérifier l'authentification au chargement
        window.onload = function() {
            checkAuth();
        };

        function checkAuth() {
            ajax('GET', '/check-auth', null, function(response) {
                if (response.authenticated) {
                    document.getElementById('adminName').textContent = `Connecté: ${response.admin.nom}`;
                } else {
                    alert('Vous devez vous connecter pour accéder à cette page.');
                    window.location.href = '../index.php';
                }
            });
        }

        function logout() {
            ajax('POST', '/logout', null, function(response) {
                window.location.href = '../index.php';
            });
        }

        function loadEtudiants() {
            ajax('GET', '/etudiants', null, function(response) {
                document.getElementById('content').innerHTML = `
            <h3>Gestion des Étudiants</h3>
            <pre>${JSON.stringify(response, null, 2)}</pre>
        `;
            });
        }

        function loadClients() {
            document.getElementById('content').innerHTML = `
        <h3>Gestion des Clients</h3>
        <p>Module en cours de développement...</p>
    `;
        }

        function loadStatistiques() {
            document.getElementById('content').innerHTML = `
        <h3>Statistiques</h3>
        <p>Module en cours de développement...</p>
    `;
        }

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
    </script>

</body>

</html>