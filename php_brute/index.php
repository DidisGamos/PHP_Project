<?php

$page_title = "Home Page";

include('includes/header.php');
include ('includes/navbar.php');
?>

<style>
    /* Styles généraux */
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content {
        flex: 1;
        background: linear-gradient(to bottom, #ffffff, #fff8ee);
    }

    .text-orange {
        color: #E78F1B;
    }

    /* Card */
    .login-card {
        max-width: 450px;
        width: 100%;
        background-color: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-top: 2rem;
    }

    /* Logo */
    .logo-container {
        position: relative;
        padding-top: 1rem;
    }

    .logo-circle {
        width: 80px;
        height: 80px;
        background-color: #E78F1B;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 4px 8px rgba(231, 143, 27, 0.3);
    }

    .logo-letter {
        color: white;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .logo-text {
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Bouton de connexion - votre style original */
    .link-login {
        background-color: #E78F1B;
        color: white;
        padding: 0.8rem;
        border-radius: 10px;
        font-size: 1rem;
        transition: opacity 0.2s;
    }

    .link-login:hover {
        color: white;
        background-color: #f89e26;
        opacity: 0.9;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .login-card {
            padding: 1.5rem;
            margin: 1rem;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
        }

        .logo-letter {
            font-size: 2rem;
        }
    }
</style>
</head>
<body>
<!-- Main Content -->
<div class="py-5 main-content">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="login-card">
                <!-- Logo Section -->
                <div class="logo-container text-center mb-4">
                    <div class="logo-circle">
                        <span class="logo-letter">J</span>
                    </div>
                    <h2 class="logo-text mt-3">
                        <span class="text-dark">JIRAMA</span>
                        <span class="text-orange">TECH</span>
                    </h2>
                </div>

                <!-- Welcome Text -->
                <div class="text-center mb-4">
                    <h3 class="text-secondary">Bienvenue</h3>
                    <p class="text-muted">Connectez-vous pour accéder à votre espace</p>
                </div>

                <!-- Login Button (keeping your original link) -->
                <div class="d-flex justify-content-center">
                    <a class="nav-link link-login w-75 text-center" aria-current="page" href="/project_php/php_brute/login.php">
                        <i class="fas fa-sign-in-alt me-2"></i>Se Connecter
                    </a>
                </div>

                <!-- Additional Links -->
                <div class="text-center mt-4">
                    <a href="#" class="text-orange text-decoration-none small">Besoin d'aide ?</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
