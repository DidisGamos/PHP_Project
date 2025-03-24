<?php
global $con;
include 'database.php';

$codecli = $_GET['codecli'];
$data = [];

$query = "SELECT c.codecompteur, c.type FROM compteur c WHERE c.codecli = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $codecli);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $codecompteur = $row['codecompteur'];
    $type = $row['type'];

    if ($type == 'ELEC') {
        $query_releve = "SELECT codeElec FROM releve_elec WHERE codecompteur = ? ORDER BY date_releve DESC LIMIT 1";
    } else {
        $query_releve = "SELECT codeEau FROM releve_eau WHERE codecompteur = ? ORDER BY date_releve2 DESC LIMIT 1";
    }

    $stmt_releve = mysqli_prepare($con, $query_releve);
    mysqli_stmt_bind_param($stmt_releve, "s", $codecompteur);
    mysqli_stmt_execute($stmt_releve);
    $result_releve = mysqli_stmt_get_result($stmt_releve);

    if ($releve = mysqli_fetch_assoc($result_releve)) {
        $data[] = [
            'codecompteur' => $codecompteur,
            'code' => $type == 'ELEC' ? $releve['codeElec'] : $releve['codeEau'],
            'type' => $type
        ];
    }
    mysqli_stmt_close($stmt_releve);
}

mysqli_stmt_close($stmt);
mysqli_close($con);

echo json_encode($data);
?>
