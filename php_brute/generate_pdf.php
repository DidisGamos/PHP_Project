<?php
global $con;

header('Content-Type: text/html; charset=utf-8');

require('fpdf/fpdf.php');

if (isset($_GET['idpaye'])) {
    $idpaye = $_GET['idpaye'];

    include 'database.php';

    $query = "SELECT PAYER.idpaye, CLIENT.nom, CLIENT.codecli, CLIENT.quartier, CLIENT.email, 
                COMPTEUR.type, COMPTEUR.pu, COMPTEUR.codecompteur,
                CASE WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.codeElec ELSE RELEVE_EAU.codeEau END AS code_releve,
                CASE WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.valeur1 ELSE RELEVE_EAU.valeur2 END AS consommation,
                CASE WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.date_presentation ELSE RELEVE_EAU.date_presentation2 END AS date_presentation,
                CASE WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.date_limite_paie ELSE RELEVE_EAU.date_limite_paie2 END AS date_limite,
                PAYER.datepaie, PAYER.montant FROM PAYER
                INNER JOIN CLIENT ON PAYER.codecli = CLIENT.codecli
                INNER JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
                LEFT JOIN RELEVE_ELEC ON COMPTEUR.codecompteur = RELEVE_ELEC.codecompteur
                LEFT JOIN RELEVE_EAU ON COMPTEUR.codecompteur = RELEVE_EAU.codecompteur
                WHERE PAYER.idpaye = '$idpaye'";

    $query_run = mysqli_query($con, $query);
    $data = mysqli_fetch_array($query_run);

    if ($data) {
        // Créer un objet FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetAutoPageBreak(true, 10);

        // Ajouter une image centrée en haut
        $imagePath = './assets/unnamed.png';
        $imageWidth = 110; // Largeur de l'image en mm
        $imageHeight = 37; // Hauteur de l'image en mm
        $pageWidth = $pdf->GetPageWidth();
        $x = ($pageWidth - $imageWidth) / 2;
        $y = 10; // Position verticale de l'image

        $pdf->Image($imagePath, $x, $y, $imageWidth, $imageHeight);

        // Ajuster le point de départ du texte après l'image
        $pdf->SetXY(10, $y + $imageHeight + 10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, 'Informations client', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(100, 7, $data['nom'], 0, 1);
        $pdf->Cell(100, 7, 'Code Client : ' . $data['codecli'], 0, 1);
        $pdf->Cell(100, 7, 'Quartier : ' . $data['quartier'], 0, 1);
        $pdf->Cell(100, 7, 'Email : ' . $data['email'], 0, 1);

        $pdf->SetXY(120, $y + $imageHeight + 10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(80, 10, iconv('UTF-8', 'CP1252', 'Facture N° ') . iconv('UTF-8', 'CP1252', $data['idpaye']), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(120, $y + $imageHeight + 20);
        $pdf->Cell(80, 7, iconv('UTF-8', 'CP1252', 'Date d\'émission : ') . iconv('UTF-8', 'CP1252', $data['date_presentation']), 0, 1, 'R');
        $pdf->SetXY(120, $y + $imageHeight + 27);
        $pdf->Cell(80, 7, 'Date limite : ' . $data['date_limite'], 0, 1, 'R');
        $pdf->SetXY(120, $y + $imageHeight + 34);
        $pdf->Cell(80, 7, 'Date paiement : ' . $data['datepaie'], 0, 1, 'R');

        $pdf->SetXY(10, $y + $imageHeight + 55);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, iconv('UTF-8', 'CP1252', 'Relevé'), 0, 1);

        $pdf->SetFont('Arial', '', 11);
        $pdf->SetXY(10, $y + $imageHeight + 65);
        $pdf->Cell(120, 10, iconv('UTF-8', 'CP1252', 'N° Relevé :'), 1, 0);
        $pdf->Cell(70, 10, iconv('UTF-8', 'CP1252', $data['code_releve']), 1, 1, 'C');

        $pdf->SetX(10);
        $pdf->Cell(120, 10, 'Consommation :', 1, 0);
        $pdf->Cell(70, 10, $data['consommation'], 1, 1, 'C');

        $pdf->SetX(10);
        $pdf->Cell(120, 10, 'Prix unitaire :', 1, 0);
        $pdf->Cell(70, 10, $data['pu'] . ' Ar', 1, 1, 'C');

        //$pdf->Line(10, $y + $imageHeight + 100, 190, $y + $imageHeight + 100);

        $pdf->SetXY(10, $y + $imageHeight + 100);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(120, 10, 'Total TTC :', 0, 0, 'R');
        $pdf->Cell(70, 10, $data['montant'] . ' Ar', 0, 1, 'C');

        $pdf->Output('D', 'facture_' . $data['nom'] . '.pdf');
    } else {
        echo "Aucune facture trouvée.";
    }
} else {
    echo "ID de la facture manquant.";
}
?>
