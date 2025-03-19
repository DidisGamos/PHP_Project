<?php
global $con;
include 'database.php';

if (isset($_GET['codecompteur'])) {
    $codecompteur = $_GET['codecompteur'];

    $query = "SELECT type, pu FROM compteur WHERE codecompteur = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $type = $row['type'];
        $pu = $row['pu'];

        if ($type == 'ELEC') {
            $releve_query = "SELECT valeur1 FROM releve_elec WHERE codecompteur = ? ORDER BY date_releve DESC LIMIT 1";
        } else {
            $releve_query = "SELECT valeur2 FROM releve_eau WHERE codecompteur = ? ORDER BY date_releve2 DESC LIMIT 1";
        }

        $stmt = mysqli_prepare($con, $releve_query);
        mysqli_stmt_bind_param($stmt, "s", $codecompteur);
        mysqli_stmt_execute($stmt);
        $releve_result = mysqli_stmt_get_result($stmt);
        $releve = mysqli_fetch_assoc($releve_result);

        $valeur = $type == 'ELEC' ? $releve['valeur1'] : $releve['valeur2'];
        $montant = $pu * $valeur;

        echo json_encode(['montant' => $montant]);
    } else {
        echo json_encode(['montant' => 0]);
    }
}
?>