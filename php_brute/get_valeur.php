<?php
global $con;
include 'database.php';
session_start();

if (isset($_GET['codecli']) && isset($_GET['idpaye'])) {
    $codecli = trim($_GET['codecli']);
    $idpaye = trim($_GET['idpaye']);

    $query_compteur = "SELECT c.codecompteur, c.type, c.pu FROM compteur c 
                       JOIN client cl ON c.codecli = cl.codecli 
                       WHERE cl.codecli = ?";
    $stmt = mysqli_prepare($con, $query_compteur);
    mysqli_stmt_bind_param($stmt, "s", $codecli);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $codecompteur = $row['codecompteur'];
        $type = $row['type'];
        $pu = $row['pu'];
        mysqli_stmt_close($stmt);

        if ($type == 'ELEC') {
            $query_releve = "SELECT valeur1 AS valeur FROM releve_elec WHERE codecompteur = ? ORDER BY date_releve DESC LIMIT 1";
        } else {
            $query_releve = "SELECT valeur2 AS valeur FROM releve_eau WHERE codecompteur = ? ORDER BY date_releve2 DESC LIMIT 1";
        }

        $stmt = mysqli_prepare($con, $query_releve);
        mysqli_stmt_bind_param($stmt, "s", $codecompteur);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $valeur = $row['valeur'];
            mysqli_stmt_close($stmt);

            $montant = $pu * $valeur;

            $query_update = "UPDATE payer SET montant = ? WHERE idpaye = ?";
            $stmt_update = mysqli_prepare($con, $query_update);
            mysqli_stmt_bind_param($stmt_update, "di", $montant, $idpaye);

            if (mysqli_stmt_execute($stmt_update)) {
                echo json_encode(["status" => "success", "montant" => $montant]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erreur mise à jour payer : " . mysqli_error($con)]);
            }
            mysqli_stmt_close($stmt_update);
        } else {
            echo json_encode(["status" => "error", "message" => "Aucun relevé trouvé."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Aucun compteur trouvé pour ce client."]);
    }

    mysqli_close($con);
}
?>