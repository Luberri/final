<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminPro Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;700&display=swap');

        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
            --glass: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard.collapsed {
            grid-template-columns: 80px 1fr;
        }

        .sidebar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            opacity: 0.1;
            z-index: 0;
        }

        .sidebar-content {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            border-bottom: 1px solid var(--glass-border);
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .collapsed .logo-text {
            opacity: 0;
            transform: translateX(-20px);
        }

        .nav-menu {
            flex: 1;
            padding: 1rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin: 0.25rem 1rem;
            border-radius: 12px;
            overflow: hidden;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .nav-item:hover::before,
        .nav-item.active::before {
            opacity: 0.1;
        }

        .nav-item:hover,
        .nav-item.active {
            color: white;
            transform: translateX(5px);
        }

        .nav-icon {
            font-size: 1.5rem;
            min-width: 24px;
            text-align: center;
        }

        .nav-text {
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .collapsed .nav-text {
            opacity: 0;
            transform: translateX(-20px);
        }

        .main-content {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .collapse-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .collapse-btn:hover {
            background: var(--glass);
        }

        .search-bar {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border-radius: 12px;
            color: var(--dark);
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            color: #6b7280;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notifications {
            position: relative;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 0.75rem;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notifications:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.05);
        }

        .logout-btn {
            background: linear-gradient(135deg, var(--error), #dc2626);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .content-area {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .welcome-section {
            margin-bottom: 2rem;
        }

        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            padding: 2rem;
            border-radius: 20px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            color: var(--success);
            margin-top: 0.5rem;
        }

        .content-grid {
            display: grid;
            gap: 2rem;
        }

        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .system-status {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 12px;
            border-left: 4px solid var(--success);
        }

        .status-item.warning {
            border-left-color: var(--warning);
        }

        .status-icon {
            font-size: 1.2rem;
        }

        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
        }

        .floating-action:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(99, 102, 241, 0.3);
            border-top: 2px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            color: var(--dark);
        }

        th,
        td {
            padding: 1rem 1.5rem;
            text-align: left;
            transition: all 0.3s ease;
        }

        th {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            border-bottom: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.8);
        }

        tr {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        tr:hover {
            background: rgba(99, 102, 241, 0.05);
            transform: translateX(5px);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--dark);
        }

        .action-btn.edit {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
        }

        .action-btn.delete {
            background: linear-gradient(135deg, var(--error) 0%, #dc2626 100%);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .action-btn.edit:hover {
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .action-btn.delete:hover {
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        tr:nth-child(even) {
            background: rgba(248, 250, 252, 0.5);
        }

        @media (max-width: 1024px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                transform: translateX(-100%);
                z-index: 1000;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .search-input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            table {
                font-size: 0.85rem;
            }

            th,
            td {
                padding: 0.75rem 1rem;
            }

            .table-actions {
                flex-direction: column;
                gap: 0.25rem;
            }

            .action-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard" id="dashboard">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <div class="logo">
                    <div class="logo-icon">⚡</div>
                    <span class="logo-text">AdminPro</span>
                </div>

                <nav class="nav-menu">
                    <a href="#" class="nav-item active" data-section="dashboard" onclick="window.location.href='?section=dashboard'; return false;">
                        <span class="nav-icon">🏠</span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <a href="#" class="nav-item" data-section="liste-pdf" onclick="window.location.href='?section=liste-pdf'; return false;">
                        <span class="nav-icon">💰</span>
                        <span class="nav-text">Liste Prêts PDF</span>
                    </a>
                    <a href="#" class="nav-item" data-section="clients" onclick="window.location.href='?section=clients'; return false;">
                        <span class="nav-icon">🏢</span>
                        <span class="nav-text">Clients</span>
                    </a>
                    <a href="#" class="nav-item" data-section="interest" onclick="window.location.href='?section=interest'; return false;">
                        <span class="nav-icon">📈</span>
                        <span class="nav-text">Intérêts</span>
                    </a>
                    <a href="#" class="nav-item" data-section="stats" onclick="window.location.href='?section=stats'; return false;">
                        <span class="nav-icon">📊</span>
                        <span class="nav-text">Statistiques</span>
                    </a>
                    <a href="#" class="nav-item" data-section="add-fund" onclick="window.location.href='?section=add-fund'; return false;">
                        <span class="nav-icon">💳</span>
                        <span class="nav-text">Ajouter Fond</span>
                    </a>
                    <a href="#" class="nav-item" data-section="add-loan" onclick="window.location.href='?section=add-loan'; return false;">
                        <span class="nav-icon">📋</span>
                        <span class="nav-text">Ajouter Prêt</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="header-left">
                    <button class="collapse-btn" onclick="toggleSidebar()">☰</button>
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" class="search-input" placeholder="Rechercher...">
                    </div>
                </div>

                <div class="user-profile">
                    <div class="notifications pulse">🔔</div>
                    <div class="user-avatar" id="userAvatar">A</div>
                    <span id="adminName" style="font-weight: 600; color: #374151;">Admin</span>
                    <button class="logout-btn" onclick="logout()">Déconnexion</button>
                </div>
            </header>

            <div class="content-area">
                <div class="welcome-section">
                    <h1 class="welcome-title">Bienvenue sur AdminPro</h1>
                    <p class="welcome-subtitle">Gérez votre système avec style et efficacité</p>
                </div>

                <div class="content-grid">
                    <div class="main-card" id="dynamicContent">
                        <?php
                        if (isset($_GET['section']) && $_GET['section'] === 'add-loan') {
                            include __DIR__ . '/ajout_pret.php';
                        } elseif (isset($_GET['section']) && $_GET['section'] === 'add-fund') {
                            include __DIR__ . '/ajout_fond.php';
                        } elseif (isset($_GET['section']) && $_GET['section'] === 'interest') {
                            include __DIR__ . '/interets.php';
                        } elseif (isset($_GET['section']) && $_GET['section'] === 'liste-pdf') {
                            include __DIR__ . '/prets.php';
                        } else {
                        ?>
                            <h2 class="card-title">Tableau de Bord Principal</h2>
                            <p>Bienvenue dans votre espace administrateur. Sélectionnez une section dans le menu pour commencer votre travail.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="floating-action" onclick="showQuickActions()">⚡</div>

    <script>
        const apiBase = "http://localhost/final/ws";
        let sidebarCollapsed = false;

        window.onload = function() {
            checkAuth();
            animateCounters();
        };

        function toggleSidebar() {
            sidebarCollapsed = !sidebarCollapsed;
            document.getElementById('dashboard').classList.toggle('collapsed', sidebarCollapsed);

            if (window.innerWidth <= 1024) {
                document.getElementById('sidebar').classList.toggle('mobile-open');
            }
        }

        function animateCounters() {
            const counters = document.querySelectorAll('.stat-value');
            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                let current = 0;
                const increment = target / 50;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.floor(current) + (counter.textContent.includes('€') ? 'k' : '');
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = counter.textContent.includes('€') ? '€12.5k' : target.toString();
                    }
                };

                setTimeout(updateCounter, Math.random() * 1000);
            });
        }

        function setActiveNavItem(section) {
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-section="${section}"]`).classList.add('active');
        }

        function updateContent(title, content) {
            document.getElementById('dynamicContent').innerHTML = `
                <h2 class="card-title">${title}</h2>
                ${content}
            `;
        }

        function checkAuth() {
            ajax('GET', '/check-auth', null, function(response) {
                if (response.authenticated) {
                    const adminName = response.admin.nom;
                    document.getElementById('adminName').textContent = adminName;
                    document.getElementById('userAvatar').textContent = adminName.charAt(0).toUpperCase();
                } else {
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
            setActiveNavItem('students');
            updateContent('Gestion des Étudiants', '<div class="loading"></div> Chargement des données...');

            ajax('GET', '/etudiants', null, function(response) {
                let tableRows = '';
                response.etudiants.forEach(student => {
                    tableRows += `
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.nom}</td>
                            <td>${student.email}</td>
                            <td>${student.statut}</td>
                            <td class="table-actions">
                                <button class="action-btn edit" onclick="editStudent(${student.id})" aria-label="Modifier l'étudiant ${student.nom}">Modifier</button>
                                <button class="action-btn delete" onclick="deleteStudent(${student.id})" aria-label="Supprimer l'étudiant ${student.nom}">Supprimer</button>
                            </td>
                        </tr>
                    `;
                });

                const tableContent = `
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Statut</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${tableRows}
                            </tbody>
                        </table>
                    </div>
                `;

                updateContent('Gestion des Étudiants', `
                    <div style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); padding: 1rem; border-radius: 12px; margin-bottom: 1rem; color: #059669;">
                        ✅ Données chargées avec succès!
                    </div>
                    ${tableContent}
                `);
            });
        }

        function editStudent(id) {
            alert(`Modifier l'étudiant avec ID: ${id}`);
            // Implement edit functionality with AJAX
        }

        function deleteStudent(id) {
            if (confirm(`Voulez-vous vraiment supprimer l'étudiant avec ID: ${id}?`)) {
                ajax('POST', `/etudiants/delete/${id}`, null, function(response) {
                    if (response.success) {
                        loadEtudiants();
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                });
            }
        }

        function loadPrets() {
            setActiveNavItem('loans');
            updateContent('Gestion des Prêts', '<div class="loading"></div> Chargement...');

            fetch('ajout_fond.php')
                .then(response => response.text())
                .then(html => updateContent('Gestion des Prêts', html))
                .catch(() => updateContent('Gestion des Prêts',
                    '<div style="background: linear-gradient(135deg, #fee2e2, transparent); padding: 1rem; border-radius: 12px; color: #dc2626);"><span style="color: #dc2626;">❌ Erreur de chargement</span></div>'
                ));
        }

        function loadClients() {
            setActiveNavItem('clients');
            updateContent('Gestion des Clients', '<h4>Module en développement...</h4><p>🚧 Bientôt disponible</p>');
        }

        function loadInterests() {
            setActiveNavItem('interest');
            updateContent('Intérêts Gagnés', '<h4>Modifier en développement...</h4><p>🚧</p>');
        }

        function loadStatistiques() {
            setActiveNavItem('stats');
            updateContent('Statistiques', '<h4>Modifier en développement...</h4><p>🚧</p>');
        }

        function loadAddFund() {
            setActiveNavItem('add-fund');
            updateContent('Ajouter un Fond', '<h4>Modifier en développement...</h4><p>🚧</p>');
        }

        function loadAddLoan() {
            setActiveNavItem('add-loan');
            updateContent('Ajouter un Prêt', '<h4>Modifier en développement...</h4><p>🚧</p>');
        }

        function loadListePDF() {
            setActiveNavItem('liste-pdf');
            window.location.href = '?section=liste-pdf';
        }

        function showQuickActions() {
            alert('Actions rapides à venir! ⚡');
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