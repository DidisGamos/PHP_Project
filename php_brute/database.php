<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "payment";

$con = mysqli_connect($host, $user, $password, $database);

if (!$con) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
?>