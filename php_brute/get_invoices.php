<?php
global $con;
include 'database.php';

if (isset($_POST['clientId'])) {
    $clientId = $_POST['clientId'];

    $query = "
        SELECT SUM(p.montant) AS totalAmount
        FROM PAYER p
        WHERE p.codecli = '$clientId'
    ";

    $result = mysqli_query($con, $query);
    $totalAmount = 0;

    if ($row = mysqli_fetch_assoc($result)) {
        $totalAmount = $row['totalAmount'];
    }

    $queryInvoices = "
        SELECT p.idpaye, p.datepaie, p.montant
        FROM PAYER p
        WHERE p.codecli = '$clientId'
        ORDER BY p.datepaie DESC
        LIMIT 3
    ";

    $resultInvoices = mysqli_query($con, $queryInvoices);
    $invoices = [];

    while ($row = mysqli_fetch_assoc($resultInvoices)) {
        $invoices[] = [
            'id' => $row['idpaye'],
            'date' => date("d/m/Y", strtotime($row['datepaie'])),
            'amount' => $row['montant'],
            'status' => $row['montant'] > 0 ? 'paid' : 'pending',
        ];
    }

    echo json_encode([
        'invoices' => $invoices,
        'totalAmount' => $totalAmount
    ]);
}
?>