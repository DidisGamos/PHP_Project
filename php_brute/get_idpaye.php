<?php
global $con;
include 'database.php';
session_start();

if (isset($_GET['codecli'])) {
    $codecli = trim($_GET['codecli']);

    $query = "SELECT idpaye FROM PAYER WHERE codecli = ? ORDER BY datepaie DESC LIMIT 1";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $codecli);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(["status" => "success", "idpaye" => $row['idpaye']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Aucun idpaye trouvé pour ce client."]);
    }

    mysqli_close($con);
}
?>