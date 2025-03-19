<?php

global $con;
session_start();

$page_title = 'Page de Registration';

include ('includes/header.php');
include ('includes/navbar.php');
?>
<div class="py-5 main-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="text-center my-5">
                    <h1 class="display-5 fw-bold mb-4">Se connecter</h1>
                    <p class="text-secondary mb-4">Vous pouvez se connecter en tant que <strong>Admin</strong> 🔑</p>
                </div>
                <?php if (isset($_SESSION['status'])): ?>
                    <div class="alert <?= strpos($_SESSION['status'], 'réussie') !== false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['status']; unset($_SESSION['status']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form @submit.prevent="handleSubmit" action="login.php" method="POST">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email" v-model="email" class="form-control custom-input" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="password" name="password" v-model="password" class="form-control custom-input" placeholder="Votre mot de passe" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <a href="/project_php/php_brute/register.php" class="for-sing-up d-flex justify-content-end">S'inscrire</a>
                    </div>

                    <button type="submit" name="login_btn" class="btn sign-in-btn w-100 mb-5">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <style>
        .custom-input {
            background-color: #F5F9F6;
            border: none;
            padding: 0.8rem 1rem;
            font-size: 1rem;
        }

        .custom-input:focus {
            background-color: #F5F9F6;
            box-shadow: none;
        }

        .sign-in-btn {
            background-color: #E78F1B;
            color: white;
            padding: 0.8rem;
            border-radius: 10px;
            font-size: 1rem;
            transition: opacity 0.2s;
        }

        .for-sing-up{
            color: #E78F1B;
        }

        .sign-in-btn:hover {
            background-color: #f89e26;
            opacity: 0.9;
            color: white;
        }

        /* Override Bootstrap's default focus styles */
        .form-control:focus {
            border-color: transparent;
            box-shadow: none;
        }

        .input-group {
            position: relative;
        }
    </style>

<?php include('includes/footer.php'); ?>

<?php

include('database.php');

if (isset($_POST['login_btn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $_SESSION['status'] = "Tous les champs sont obligatoires.";
        header('Location: login.php');
        exit();
    }

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['idUser'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['status'] = "Connexion réussie ! Bienvenue, " . $user['name'];

            header('Location: siderbar.php');
            header('location:dashboard.php');
            exit();
        } else {

            $_SESSION['status'] = "Mot de passe incorrect!";
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['status'] = "Aucun utilisateur trouvé avec cet email!";
        header('Location: login.php');
        exit();
    }
}
?>