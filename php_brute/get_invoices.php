<?php
global $con;
include 'database.php';

if (isset($_POST['clientId'])) {
    $clientId = $_POST['clientId'];

    $queryInvoices = "
        SELECT p.idpaye, p.datepaie, p.montant
        FROM PAYER p
        WHERE p.codecli = '$clientId'
        ORDER BY p.idpaye DESC
        LIMIT 3
    ";

    $resultInvoices = mysqli_query($con, $queryInvoices);
    $invoices = [];
    $totalAmount = 0;

    while ($row = mysqli_fetch_assoc($resultInvoices)) {
        $invoices[] = [
            'id' => $row['idpaye'],
            'date' => date("d/m/Y", strtotime($row['datepaie'])),
            'amount' => $row['montant'],
            'status' => $row['montant'] > 0 ? 'paid' : 'pending',
        ];
        $totalAmount += $row['montant'];
    }

    echo json_encode([
        'invoices' => $invoices,
        'totalAmount' => number_format($totalAmount, 2, ',', ' ')
    ]);
}
?>
