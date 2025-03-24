<?php
global $con;
session_start();

include 'database.php';
include 'siderbar.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['clients'])) {
        $clients = json_decode($_POST['clients'], true);

        foreach ($clients as $client) {
            $email = $client['email'];
            $nom = $client['nom'];

            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'herllandysamoroschristy@gmail.com';
                $mail->Password = 'sfaw duqz fbqh kqoe';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('herllandysamoroschristy@gmail.com', 'Votre Société');
                $mail->addAddress($email, $nom);

                $mail->isHTML(true);
                $mail->Subject = 'Rappel de paiement de facture';
                $mail->Body    = "Bonjour $nom,<br><br>Nous vous rappelons que vous avez une facture en attente de paiement. Veuillez régulariser votre situation dès que possible.<br><br>Cordialement,<br>Votre Société";

                $mail->send();
                $results[] = ['email' => $email, 'status' => 'success'];
            } catch (Exception $e) {
                $results[] = ['email' => $email, 'status' => 'error', 'message' => $e->getMessage()];
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Tous les e-mails ont été envoyés avec succès.']);
        exit;

    } elseif (isset($_POST['email'])) {
        $email = $_POST['email'];
        $nom = $_POST['nom'];

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'herllandysamoroschristy@gmail.com';
            $mail->Password = 'sfaw duqz fbqh kqoe';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('herllandysamoroschristy@gmail.com', 'Votre Société');
            $mail->addAddress($email, $nom);

            $mail->isHTML(true);
            $mail->Subject = 'Rappel de paiement de facture';
            $mail->Body    = "Bonjour $nom,<br><br>Nous vous rappelons que vous avez une facture en attente de paiement. Veuillez régulariser votre situation dès que possible.<br><br>Cordialement,<br>Votre Société";

            $mail->send();
            echo json_encode(['status' => 'success', 'message' => 'E-mail envoyé avec succès à ' . $nom]);
            exit;
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'envoi de l\'e-mail à ' . $nom]);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historiques</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/all.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #1a237e;
            --secondary-color: #4a148c;
        }

        body {
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }

        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .action-buttons .btn {
            padding: 5px 10px;
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: scale(1.1);
        }

        .search-box {
            position: relative;
        }

        .search-box i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .search-box input {
            padding-left: 35px;
            border-radius: 20px;
        }

        .stats-card {
            position: relative;
            overflow: hidden;
        }

        .stats-card .icon {
            position: absolute;
            right: 20px;
            bottom: 20px;
            font-size: 48px;
            opacity: 0.1;
        }

        .progress {
            height: 5px;
            margin-top: 15px;
        }

        .table-responsive {
            border-radius: 10px;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            border-top: none;
            background: #f8f9fa;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1050;
        }

        /* Invoice history styles */
        .invoice-history {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 2rem;
            padding: 1.5rem;
        }

        .invoice-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .invoice-card {
            border-left: 4px solid var(--primary-color);
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #f8f9fa;
            border-radius: 0 8px 8px 0;
            transition: transform 0.2s ease;
        }

        .invoice-card:hover {
            transform: translateX(5px);
        }

        .invoice-card .date {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .invoice-card .amount {
            font-weight: bold;
            font-size: 1.1rem;
        }

        .invoice-card .status {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            display: inline-block;
        }

        .status-paid {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .table-header {
            padding: 1.25rem 1.5rem 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .info-icon {
            color: var(--primary-color);
        }

        .legend {
            display: inline-flex;
            align-items: center;
            margin-right: 1rem;
            font-size: 0.85rem;
        }

        .legend-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 0.5rem;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<!-- Main Content -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Historiques</h4>
        <div class="d-flex gap-3">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" class="form-control" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <?php
        include 'database.php';

        date_default_timezone_set('Africa/Nairobi');

        $mois_actuel = date('m');
        $annee_actuelle = date('Y');

        $mois_precedent = $mois_actuel - 1;
        if ($mois_precedent < 1) {
            $mois_precedent = 12;
            $annee_precedente = $annee_actuelle - 1;
        } else {
            $annee_precedente = $annee_actuelle;
        }

        $debut_mois = "$annee_precedente-$mois_precedent-01";
        $fin_mois = date("Y-m-t", strtotime($debut_mois));
        ?>
        <?php
        include 'database.php';

        $query_factures_attente = "SELECT COUNT(DISTINCT CLIENT.codecli) AS factures_attente 
                           FROM CLIENT
                           LEFT JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
                           LEFT JOIN RELEVE_ELEC ON COMPTEUR.codecompteur = RELEVE_ELEC.codecompteur
                           LEFT JOIN RELEVE_EAU ON COMPTEUR.codecompteur = RELEVE_EAU.codecompteur
                           LEFT JOIN PAYER ON CLIENT.codecli = PAYER.codecli
                           WHERE (RELEVE_ELEC.date_limite_paie BETWEEN ? AND ? 
                           OR RELEVE_EAU.date_limite_paie2 BETWEEN ? AND ?)
                           AND PAYER.datepaie IS NULL;";

        $stmt_factures_attente = $con->prepare($query_factures_attente);

        if (!$stmt_factures_attente) {
            die("Erreur de préparation de la requête : " . $con->error);
        }

        $stmt_factures_attente->bind_param('ssss', $debut_mois, $fin_mois, $debut_mois, $fin_mois);

        $stmt_factures_attente->execute();

        $result_factures_attente = $stmt_factures_attente->get_result();
        $row_factures_attente = $result_factures_attente->fetch_assoc();

        $factures_attente = $row_factures_attente['factures_attente'];

        $stmt_factures_attente->close();

        $max_factures = 100;
        $progress = ($factures_attente / $max_factures) * 100;
        $progress = min($progress, 100);
        ?>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted">Factures en attente</h6>
                    <h3><?= $factures_attente ?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-warning"
                             style="width: <?= $progress ?>%;"
                             aria-valuenow="<?= $progress ?>"
                             aria-valuemin="0"
                             aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-clock icon"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['status'])): ?>
        <div class="alert <?= strpos($_SESSION['status'], 'Erreur') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['status']; unset($_SESSION['status']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tableau des clients -->
    <div class="card">
        <div class="card-body">
            <div class="table-header">
                <h5 class="mb-0">Clients avec factures impayées du mois précédent</h5>
                <div class="table-info">
                    <i class="fas fa-info-circle info-icon"></i>
                    <span>Ce tableau affiche les clients qui n'ont pas réglé leurs factures du mois précédent et nécessitent un rappel.</span>
                </div>
                <div class="mt-2">
                    <div class="legend">
                        <span class="legend-indicator bg-warning"></span>
                        <span>En attente de paiement</span>
                    </div>
                    <div class="legend">
                        <span class="legend-indicator" style="background-color: var(--primary-color);"></span>
                        <span>Rappel envoyé</span>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary mt-2 send-email-all">
                        <i class="fas fa-envelope me-2"></i>Notifier par mail tous les clients
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <?php
                    include 'database.php';

                    date_default_timezone_set('Africa/Nairobi');

                    $mois_actuel = date('m');
                    $annee_actuelle = date('Y');

                    $mois_precedent = $mois_actuel - 1;
                    if ($mois_precedent < 1) {
                        $mois_precedent = 12;
                        $annee_precedente = $annee_actuelle - 1;
                    } else {
                        $annee_precedente = $annee_actuelle;
                    }

                    $debut_mois = "$annee_precedente-$mois_precedent-01";
                    $fin_mois = date("Y-m-t", strtotime($debut_mois));
                    ?>
                    <?php

                    $query = " SELECT CLIENT.codecli, CLIENT.nom, CLIENT.email, RELEVE_ELEC.codeElec AS codeReleve,
                                'ELEC' AS typeReleve, RELEVE_ELEC.date_limite_paie AS dateLimite FROM CLIENT
                                LEFT JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
                                LEFT JOIN RELEVE_ELEC ON COMPTEUR.codecompteur = RELEVE_ELEC.codecompteur
                                LEFT JOIN PAYER ON CLIENT.codecli = PAYER.codecli
                                WHERE RELEVE_ELEC.date_limite_paie BETWEEN ? AND ? 
                                AND PAYER.idpaye IS NULL UNION SELECT CLIENT.codecli, CLIENT.nom, CLIENT.email,
                                RELEVE_EAU.codeEau AS codeReleve,
                                'EAU' AS typeReleve, RELEVE_EAU.date_limite_paie2 AS dateLimite
                                FROM CLIENT LEFT JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
                                LEFT JOIN RELEVE_EAU ON COMPTEUR.codecompteur = RELEVE_EAU.codecompteur
                                LEFT JOIN PAYER ON CLIENT.codecli = PAYER.codecli
                                WHERE RELEVE_EAU.date_limite_paie2 BETWEEN ? AND ? 
                                AND PAYER.idpaye IS NULL;";
                    $stmt = $con->prepare($query);

                    if (!$stmt) {
                        die("Erreur de préparation de la requête : " . $con->error);
                    }

                    $stmt->bind_param('ssss', $debut_mois, $fin_mois, $debut_mois, $fin_mois);
                    $stmt->execute();

                    $result = $stmt->get_result();
                    $clients = $result->fetch_all(MYSQLI_ASSOC);
                    $stmt->close();
                    ?>

                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($clients)) : ?>
                        <?php foreach ($clients as $client) : ?>
                            <tr>
                                <td><?= htmlspecialchars($client['nom']) ?></td>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary send-email"
                                            title="Envoyer un email"
                                            data-email="<?= htmlspecialchars($client['email']) ?>"
                                            data-nom="<?= htmlspecialchars($client['nom']) ?>">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" class="text-center">Aucun client en attente de paiement pour le mois précédent.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="invoice-history">
            <div class="invoice-history-header">
                <h5 class="mb-0">Historique des 3 dernières factures</h5>
                <select class="form-select client-selector w-50" id="clientSelector" onchange="loadClientInvoices()">
                    <option value="">Sélectionner un client</option>
                    <!-- Les options des clients seront ajoutées ici dynamiquement -->
                </select>
                <button type="button" class="btn btn-primary" onclick="resetClientSelector()">
                    <i class="fa fa-refresh me-2"></i>Réinitialiser
                </button>
            </div>

            <div id="invoiceHistoryContent">
                <div id="clientTypeContent" class="text-center text-muted py-4">
                    Veuillez sélectionner un client pour voir son type de compteur.
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Toast de succès -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" id="emailToast">
        <div class="d-flex">
            <div class="toast-body">
                Email envoyé avec succès!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.send-email').forEach(function(button) {
            button.addEventListener('click', function() {
                var email = this.getAttribute('data-email');
                var nom = this.getAttribute('data-nom');

                fetch('historique.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `email=${encodeURIComponent(email)}&nom=${encodeURIComponent(nom)}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('E-mail envoyé avec succès');
                        } else {
                            alert('Erreur: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                    });
            });
        });
    });
</script>

<script>
    function sendEmail(email, name) {
        console.log(`Sending email to ${name} at ${email}`);

        setTimeout(() => {
            const toastElement = document.getElementById('emailToast');
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }, 500);
    }

    document.addEventListener('click', function(event) {
        const button = event.target.closest('.send-email');
        if (button) {
            const email = button.getAttribute('data-email');
            const name = button.getAttribute('data-nom');
            sendEmail(email, name);
        }
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<script>
    function showToast() {
        const toastElement = document.getElementById('emailToast');
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
    }

    function sendEmail(email, name) {
        console.log(`Envoi d'un e-mail à ${name} (${email})`);

        setTimeout(() => {
            showToast();
        }, 500);
    }

    document.addEventListener('click', function(event) {
        const button = event.target.closest('.send-email');
        if (button) {
            const email = button.getAttribute('data-email');
            const name = button.getAttribute('data-nom');
            sendEmail(email, name);
        }
    });
    document.querySelector('.send-email-all').addEventListener('click', function() {
        const clients = [];
        document.querySelectorAll('.send-email').forEach(function(button) {
            const email = button.getAttribute('data-email');
            const name = button.getAttribute('data-nom');
            clients.push({ email, name });
        });

        clients.forEach(client => {
            sendEmail(client.email, client.name);
        });

        setTimeout(() => {
            showToast();
        }, 500 * clients.length);
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<script>
    function loadClients() {
        const clientSelector = document.getElementById('clientSelector');

        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_clients.php', true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const clients = JSON.parse(xhr.responseText);

                clients.forEach(client => {
                    const option = document.createElement('option');
                    option.value = client.codecli;
                    option.textContent = client.nom;
                    clientSelector.appendChild(option);
                });
            }
        };
        xhr.send();
    }

    window.onload = function() {
        loadClients();
    }

    function formatCurrency(amount) {
        return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " Ar";
    }

    function loadClientInvoices() {
        const clientId = document.getElementById('clientSelector').value;
        const contentDiv = document.getElementById('invoiceHistoryContent');

        if (!clientId) {
            contentDiv.innerHTML = `
                <div class="text-center text-muted py-4">
                    Veuillez sélectionner un client pour voir son historique de factures
                </div>
            `;
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_invoices.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                const invoices = response.invoices;
                const totalAmount = response.totalAmount;

                if (invoices.length === 0) {
                    contentDiv.innerHTML = `
                        <div class="text-center text-muted py-4">
                            Aucune facture trouvée pour ce client.
                        </div>
                    `;
                    return;
                }

                let invoiceHtml = '';
                invoices.forEach(invoice => {
                    const statusClass = invoice.status === 'paid' ? 'status-paid' : 'status-pending';
                    const statusText = invoice.status === 'paid' ? 'Payée' : 'En attente';

                    const invoiceType = invoice.type ? invoice.type : 'Non défini';

                    invoiceHtml += `
                        <div class="invoice-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">${invoice.id}</div>
                                    <div class="date">Émise le ${invoice.date}</div>
                                </div>
                                <div class="text-end">
                                    <div class="amount">${formatCurrency(invoice.amount)}</div>
                                    <span class="status ${statusClass}">${statusText}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });

                invoiceHtml += `
                    <div class="total-amount">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fw-bold">Montant total des 3 dernières factures</div>
                            <div class="fw-bold fs-5">${formatCurrency(totalAmount)}</div>
                        </div>
                    </div>
                `;

                contentDiv.innerHTML = invoiceHtml;
            } else {
                contentDiv.innerHTML = `
                    <div class="text-center text-muted py-4">
                        Erreur lors de la récupération des factures.
                    </div>
                `;
            }
        };
        xhr.send('clientId=' + clientId);
    }
</script>

<script>
    function resetClientSelector() {
        const clientSelector = document.getElementById("clientSelector");

        clientSelector.value = "";

        loadClientInvoices();
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var emailToast = new bootstrap.Toast(document.getElementById('emailToast'));

        document.querySelector('.send-email-all').addEventListener('click', function() {
            var clients = [];
            document.querySelectorAll('.send-email').forEach(function(button) {
                var email = button.getAttribute('data-email');
                var nom = button.getAttribute('data-nom');
                clients.push({ email: email, nom: nom });
            });

            fetch('historique.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'clients=' + encodeURIComponent(JSON.stringify(clients))
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        emailToast.show();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        });
    });
</script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>