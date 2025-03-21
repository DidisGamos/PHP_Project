<?php
global $con;
include 'database.php';

if (isset($_POST['clientId'])) {
    $clientId = $_POST['clientId'];

    // Requête pour récupérer les 3 derniers paiements et calculer le total
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
        // Ajouter chaque montant à totalAmount pour le total des 3 derniers paiements
        $totalAmount += $row['montant'];
    }

    // Retourner les données sous forme JSON
    echo json_encode([
        'invoices' => $invoices,
        'totalAmount' => number_format($totalAmount, 2, ',', ' ')
    ]);
}
?>
