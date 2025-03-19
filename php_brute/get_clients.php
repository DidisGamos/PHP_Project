<?php
global $con;
include 'database.php';

$query = "SELECT codecli, nom FROM CLIENT";
$result = mysqli_query($con, $query);

$clients = [];

while ($row = mysqli_fetch_assoc($result)) {
    $clients[] = [
        'codecli' => $row['codecli'],
        'nom' => $row['nom']
    ];
}

echo json_encode($clients);
?>