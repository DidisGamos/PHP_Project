<?php

session_start();

$page_title = 'Page de Registration';

include ('includes/header.php');
include ('includes/navbar.php');
?>

<div class="py-3 main-content">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="text-center">
                    <div class="d-flex justify-content-around align-items-center">
                        <a href="login.php" class="arrow-left text-black align-items-center">
                            <i class="fa-solid fa-arrow-left"></i></a>
                        <h1 class="display-5 fw-bold mb-4 sign-up">S'inscrire</h1>
                    </div>
                    <p class="text-secondary mb-4">Vous pouvez s'inscrire en tant que nouveau <strong>Admin</strong> 🔑</p>
                    <?php if (isset($_SESSION['status'])): ?>
                        <div class="alert <?= strpos($_SESSION['status'], 'réussie') !== false ? 'alert-success' : 'alert-danger' ?> alert-dismissible fade show" role="alert">
                            <?= $_SESSION['status']; unset($_SESSION['status']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                </div>

                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" name="name" class="form-control custom-input" placeholder="Votre Nom">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" name="phone" class="form-control custom-input" placeholder="Numéro Téléphone">
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="email" name="email" class="form-control custom-input" placeholder="Email">
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="password" name="password" class="form-control custom-input" placeholder="Votre mot de passe">
                        </div>
                    </div>
                    <button type="submit" name="btn_conn" class="btn sign-in-btn w-100">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>

    .sign-up{
        margin-right: 80px;
    }

    .arrow-left{
        font-size: 30px;
    }

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
global $con;

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_conn'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($phone) || empty($email) || empty($password)) {
        $_SESSION['status'] = "Tous les champs sont obligatoires.";
        echo "<script type='text/javascript'>
              window.location.href = 'register.php';
              </script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['status'] = "Adresse email invalide.";
        echo "<script type='text/javascript'>
              window.location.href = 'register.php';
              </script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_prepare($con, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $_SESSION['status'] = "Cet email est déjà utilisé.";
        echo "<script type='text/javascript'>
              window.location.href = 'register.php';
              </script>";
        exit();
    }

    mysqli_stmt_close($stmt);

    $query = "INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Inscription réussie !";
        echo "<script type='text/javascript'>
              window.location.href = 'register.php';
              </script>";
    } else {
        $_SESSION['status'] = "Erreur lors de l'inscription : " . mysqli_error($con);
        echo "<script type='text/javascript'>
              window.location.href = 'register.php';
              </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);

    exit();
}

?>