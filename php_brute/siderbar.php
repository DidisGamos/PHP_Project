<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paiements Électricité & Eau</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/all.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #1a237e;
            --secondary-color: #4a148c;
        }

        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #E78F1B;
            color: white;
            padding: 20px;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 15px;
            margin-bottom: 5px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .logo-container {
            position: relative;
            padding-top: 1rem;
        }
        .logo-letter {
            color: #E78F1B;
            font-size: 2.5rem;
            font-weight: bold;
        }
        .logo-circle {
            width: 80px;
            height: 80px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 4px 8px rgba(231, 143, 27, 0.3);
        }


        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="logo-container text-center mb-4">
        <div class="logo-circle">
            <span class="logo-letter">J</span>
        </div>
        <h2 class="logo-text mt-3">
            <span class="text-dark">JIRAMA</span>
            <span class="text-orange">TECH</span>
        </h2>
    </div>
    <nav class="nav list-sidebar flex-column">
        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/dashboard.php') ? 'active' : '' ?>"
           href="/project_php/php_brute/dashboard.php">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/client.php') ? 'active' : '' ?>"
           href="/project_php/php_brute/client.php">
            <i class="fas fa-users"></i> Clients
        </a>
        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/compteur.php') ? 'active' : '' ?>"
           href="/project_php/php_brute/compteur.php">
            <i class="fas fa-tachometer-alt"></i> Compteurs
        </a>
        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/releves.php') ? 'active' : '' ?>"
           href="/project_php/php_brute/releves.php">
            <i class="fas fa-file-invoice"></i> Relevés
        </a>
        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/payments.php') ? 'active' : '' ?>"
        href="/project_php/php_brute/payments.php">
            <i class="fas fa-money-bill"></i> Paiements
        </a>
        <a class="nav-link"<?= ($_SERVER['REQUEST_URI'] == '/project_php/php_brute/historique.php') ? 'active' : '' ?>
           href="/project_php/php_brute/historique.php">
            <i class="fas fa-history"></i> Historique
        </a>
        <a class="nav-link mt-5" href="/project_php/php_brute/index.php">
            <i class="fas fa-sign-out"></i> Se Déconnecter
        </a>
    </nav>
</div>
<script src="js/bootstrap.bundle.min.js"></script>

<script>

    document.addEventListener("DOMContentLoaded", function () {
        let currentPath = window.location.pathname;
        let navLinks = document.querySelectorAll(".list-sidebar .nav-link");

        navLinks.forEach(link => {
            if (link.getAttribute("href") === currentPath) {
                document.querySelector(".list-sidebar .active")?.classList.remove("active");
                link.classList.add("active");
            }
        });
    });

</script>

</body>
</html>