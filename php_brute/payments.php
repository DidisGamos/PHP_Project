<?php

global $con;
session_start();
include ("siderbar.php");


?>

<style>
    :root {
        --sidebar-width: 250px;
        --primary-color: #1a237e;
        --secondary-color: #4a148c;
    }

    body {
        background-color: #f8f9fa;
    }

    .new-modal{
        background: #E78F1B;
        color: white;
    }
    .new-modal:hover{
        background: #d58311;
        color: white;
    }
    .new-modal:checked{
        background: #d58311;
        color: white;
    }

    .sidebar .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
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

    .status-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
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

    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
        background: #E78F1B;
        color: white;
    }

    .modal-header .btn-close {
        color: white;
        filter: brightness(0) invert(1);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 8px 12px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
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

    .pagination {
        margin-top: 20px;
    }

    .pagination .page-link {
        border-radius: 5px;
        margin: 0 2px;
        color: var(--primary-color);
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    @media (max-width: 768px) {

        .main-content {
            margin-left: 0;
        }
    }
</style>
<!-- Main Content -->
<div class="main-content">
    <!-- Top Bar -->
    <div class="top-bar d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Paiements Factures</h4>
        <div class="d-flex gap-3">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
            <button class="btn new-modal" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Nouvelle Paiement
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <?php
                    include 'database.php';
                    $selectsquery = "SELECT COUNT(*) AS total_paye FROM payer";
                    $result = mysqli_query($con, $selectsquery);
                    $row = mysqli_fetch_assoc($result);
                    $total_paye = $row['total_paye'];

                    $max_paye = 100;
                    $progress = ($total_paye / $max_paye) * 100;
                    $progress = min($progress,100);

                    ?>
                    <h6 class="text-muted">Total Client à payer</h6>
                    <h3><?php echo $row['total_paye']; ?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: <?php echo $progress;?>%;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-money-bill icon"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['status'])): ?>
        <div class="alert <?= (strpos($_SESSION['status'], 'Suppression') !== false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['status']; unset($_SESSION['status']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <?php
                include 'database.php';

                $query = "SELECT PAYER.idpaye, CLIENT.nom, CLIENT.codecli, CLIENT.quartier, CLIENT.email, COMPTEUR.type, 
                            COMPTEUR.pu, COMPTEUR.codecompteur, MAX(CASE WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.codeElec
                            WHEN COMPTEUR.type = 'eau' THEN RELEVE_EAU.codeEau END) AS code_releve, MAX(CASE 
                            WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.valeur1 WHEN COMPTEUR.type = 'eau' THEN RELEVE_EAU.valeur2
                            END) AS consommation, MAX(CASE 
                            WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.date_presentation
                            WHEN COMPTEUR.type = 'eau' THEN RELEVE_EAU.date_presentation2
                            END) AS date_presentation, MAX(CASE 
                            WHEN COMPTEUR.type = 'elec' THEN RELEVE_ELEC.date_limite_paie
                            WHEN COMPTEUR.type = 'eau' THEN RELEVE_EAU.date_limite_paie2 END) AS date_limite,
                            PAYER.datepaie, PAYER.montant FROM PAYER
                            INNER JOIN CLIENT ON PAYER.codecli = CLIENT.codecli
                            INNER JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
                            LEFT JOIN RELEVE_ELEC ON COMPTEUR.codecompteur = RELEVE_ELEC.codecompteur AND COMPTEUR.type = 'elec'
                            LEFT JOIN RELEVE_EAU ON COMPTEUR.codecompteur = RELEVE_EAU.codecompteur AND COMPTEUR.type = 'eau'
                            WHERE PAYER.montant > 0 GROUP BY PAYER.idpaye ORDER BY PAYER.idpaye DESC";

                $query_run = mysqli_query($con, $query);
                ?>

                <table class="table" id="compteurTable">
                    <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Client</th>
                        <th>Date de Paiement</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_array($query_run)) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['idpaye']); ?></td>
                                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                <td><?php echo htmlspecialchars($row['datepaie']); ?></td>
                                <td><?php echo htmlspecialchars($row['montant']); ?> Ar</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal"
                                                data-idpaye="<?= htmlspecialchars($row['idpaye']); ?>"
                                                data-nom="<?= htmlspecialchars($row['nom']); ?>"
                                                data-codecli="<?= htmlspecialchars($row['codecli']); ?>"
                                                data-quartier="<?= htmlspecialchars($row['quartier']); ?>"
                                                data-email="<?= htmlspecialchars($row['email']); ?>"
                                                data-date-presentation="<?= htmlspecialchars($row['date_presentation']); ?>"
                                                data-date-limite="<?= htmlspecialchars($row['date_limite']); ?>"
                                                data-datepaie="<?= htmlspecialchars($row['datepaie']); ?>"
                                                data-type="<?= htmlspecialchars($row['type']); ?>"
                                                data-code-releve="<?= htmlspecialchars($row['code_releve']); ?>"
                                                data-consommation="<?= htmlspecialchars($row['consommation']); ?>"
                                                data-pu="<?= htmlspecialchars($row['pu']); ?>"
                                                data-montant="<?= htmlspecialchars($row['montant']); ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo htmlspecialchars($row['idpaye']); ?>" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo htmlspecialchars($row['idpaye']); ?>" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-danger'><strong>Aucun paiement trouvé dans la base de données.</strong></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
                <div id="noResultsMessage" class="text-center text-danger mt-2" style="display: none;">
                    <strong>Aucun résultat correspondant à votre recherche.</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php
                include 'database.php';

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-paie'])) {
                    $idpaye = trim($_POST['idpaye']);
                    $codecli = trim($_POST['codecli']);
                    $datepaie = trim($_POST['datepaie']);
                    $codecompteur = trim($_POST['codecompteur']);

                    if (empty($idpaye) || empty($codecli) || empty($datepaie) || empty($codecompteur)) {
                        $_SESSION['status'] = "Tous les champs sont obligatoires.";
                        echo "<script type='text/javascript'>
                              window.location.href = 'payments.php';
                              </script>";
                        exit();
                    }

                    $query_compteur = "SELECT type, pu FROM compteur WHERE codecompteur = ?";
                    $stmt = mysqli_prepare($con, $query_compteur);
                    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        $type = $row['type'];
                        $pu = $row['pu'];
                    } else {
                        $_SESSION['status'] = "Aucun compteur trouvé.";
                        echo "<script type='text/javascript'>
                              window.location.href = 'payments.php';
                              </script>";
                        exit();
                    }
                    mysqli_stmt_close($stmt);

                    if ($type == 'ELEC') {
                        $query_releve = "SELECT valeur1 FROM releve_elec WHERE codecompteur = ? ORDER BY date_releve DESC LIMIT 1";
                    } else {
                        $query_releve = "SELECT valeur2 FROM releve_eau WHERE codecompteur = ? ORDER BY date_releve2 DESC LIMIT 1";
                    }

                    $stmt = mysqli_prepare($con, $query_releve);
                    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($row = mysqli_fetch_assoc($result)) {
                        $valeur = ($type == 'ELEC') ? $row['valeur1'] : $row['valeur2'];
                        $montant = $pu * $valeur;
                    } else {
                        $_SESSION['status'] = "Aucune donnée de relevé trouvée pour ce compteur.";
                        echo "<script type='text/javascript'>
                              window.location.href = 'payments.php';
                              </script>";
                        exit();
                    }
                    mysqli_stmt_close($stmt);

                    $query = "INSERT INTO payer (idpaye, codecli, datepaie, montant) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "sssd", $idpaye, $codecli, $datepaie, $montant);

                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['status'] = "Paiement enregistré avec succès !";
                        echo "<script type='text/javascript'>
                              window.location.href = 'payments.php';
                              </script>";
                    } else {
                        $_SESSION['status'] = "Erreur lors de l'enregistrement du paiement : " . mysqli_error($con);
                    }

                    mysqli_stmt_close($stmt);
                    mysqli_close($con);
                    header("Location: payments.php");
                    exit();
                }
                ?>

                <!-- Formulaire d'ajout de paiement -->
                <form method="POST" action="payments.php">
                    <h6 class="mb-3 mt-4">Informations Paiement</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">N° Facture</label>
                            <input type="text" class="form-control" name="idpaye" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Clients</label>
                            <select class="form-select" name="codecli" id="codecli" required>
                                <option value="">Sélectionner</option>
                                <?php
                                include 'database.php';
                                $sql = "SELECT codecli, nom FROM client ORDER BY nom ASC";
                                $result = $con->query($sql);
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($row['codecli']) . '">' . htmlspecialchars($row['nom']) . '</option>';
                                    }
                                }
                                $con->close();
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Numéro de Relevé</label>
                            <select class="form-select" name="codecompteur" id="codecompteur" required>
                                <option value="">Sélectionner un client d'abord</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Paiement</label>
                            <input type="date" class="form-control" name="datepaie" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Montant</label>
                            <input type="number" step="0.01" class="form-control" name="montant" id="montant" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="save-paie" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<?php
include 'database.php';

if (isset($_POST['modif-paiement'])) {
    $idpaye = mysqli_real_escape_string($con, $_POST['idpaye']);
    $codecli = mysqli_real_escape_string($con, $_POST['codecli']);
    $datepaie = mysqli_real_escape_string($con, $_POST['datepaie']);
    $montant = mysqli_real_escape_string($con, $_POST['montant']);

    $query = "UPDATE payer SET codecli='$codecli', datepaie='$datepaie', montant='$montant' WHERE idpaye='$idpaye'";

    if (mysqli_query($con, $query)) {
        $_SESSION['status'] = "Paiement modifier avec réussie !";
        echo "<script type='text/javascript'>
              window.location.href = 'payments.php';
              </script>";
    } else {
        echo "Erreur de mise à jour du paiement : " . mysqli_error($con);
    }
}

$query = "SELECT * FROM payer";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $codecli = $row['codecli'];

    $client_query = "SELECT nom FROM client WHERE codecli = '$codecli'";
    $client_result = mysqli_query($con, $client_query);
    $client_name = ($client_result && mysqli_num_rows($client_result) > 0) ? mysqli_fetch_assoc($client_result)['nom'] : "Inconnu";
    ?>

    <!-- Modal de modification pour chaque paiement -->
    <div class="modal fade" id="editModal-<?php echo htmlspecialchars($row['idpaye']); ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo htmlspecialchars($row['idpaye']); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-<?php echo htmlspecialchars($row['idpaye']); ?>">Modifier le Paiement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="payments.php">
                        <h6 class="mb-3 mt-4">Informations Paiement</h6>
                        <div class="row">
                            <!-- ID Paiement -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">N° Facture</label>
                                <input type="text" class="form-control" name="idpaye" value="<?php echo $row['idpaye']; ?>" required readonly>
                            </div>
                            <!-- Client -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Client</label>
                                <select class="form-select" name="codecli" required>
                                    <option value="">Sélectionner</option>
                                    <?php
                                    $sql = "SELECT codecli, nom FROM client ORDER BY nom ASC";
                                    $clients_result = $con->query($sql);
                                    if ($clients_result->num_rows > 0) {
                                        while ($client_row = $clients_result->fetch_assoc()) {
                                            $selected = ($client_row['codecli'] == $row['codecli']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($client_row['codecli']) . '" ' . $selected . '>' . htmlspecialchars($client_row['nom']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Date Paiement -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Paiement</label>
                                <input type="date" class="form-control" name="datepaie" value="<?php echo $row['datepaie']; ?>" required>
                            </div>
                            <!-- Montant -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Montant</label>
                                <input type="number" step="0.01" class="form-control" name="montant" value="<?php echo $row['montant']; ?>" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="modif-paiement" class="btn btn-warning">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>

<!-- Delete Modal -->
<?php
include 'database.php';

if (isset($_POST['delete-paye'])) {
    $idpaye = mysqli_real_escape_string($con, $_POST['idpaye']);

    $query = "DELETE FROM payer WHERE idpaye = '$idpaye'";

    if (mysqli_query($con, $query)) {
        $_SESSION['status'] = "Suppression du Paiement avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'payments.php';
              </script>";
    } else {
        $_SESSION['status'] = "Erreur lors de la suppression du paiement : " . mysqli_error($con);
    }
    exit();
}
?>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="payments.php">
                    <div class="alert alert-danger mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cette facture ?
                    </div>
                    <input type="hidden" name="idpaye" id="payeId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="delete-paye" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Informations Client -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Informations client</h6>
                        <p id="client-info"></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6>Facture N° <span id="facture-id"></span></h6>
                        <p>Date d'émission : <span id="date-presentation"></span><br>
                            Date limite : <span id="date-limite"></span><br>
                            Date paiement : <span id="date-paie"></span></p>
                    </div>
                </div>
                <!-- Détails Relevé -->
                <div class="row mb-4" id="releve-details">
                    <!-- Dynamically populated -->
                </div>
                <!-- Total -->
                <table class="table">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total TTC</strong></td>
                        <td><strong id="total-ttc"></strong> Ar</td>
                    </tr>
                </table>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary" id="download-pdf" data-idpaye="">
                        <i class="fas fa-download me-2"></i>Télécharger PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('codecli').addEventListener('change', function() {
        var codecli = this.value;
        if (codecli) {
            fetch('payments.php?codecli=' + codecli)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        document.getElementById('montant').value = data.montant;
                    } else {
                        alert('Erreur lors du calcul du montant.');
                    }
                });
        } else {
            document.getElementById('montant').value = '';
        }
    });
</script>
<script>
    const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const payeId = this.getAttribute('data-id');
            document.getElementById('payeId').value = payeId;
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var detailsModal = document.getElementById('detailsModal');
        detailsModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;

            var montant = button.getAttribute("data-montant");

            document.getElementById("facture-id").textContent = button.getAttribute("data-idpaye");
            document.getElementById("client-info").innerHTML = button.getAttribute("data-nom") + "<br>" +
                "Code Client : " + button.getAttribute("data-codecli") + "<br>" +
                "Quartier : " + button.getAttribute("data-quartier") + "<br>" +
                "Email : " + button.getAttribute("data-email");

            document.getElementById("date-presentation").textContent = button.getAttribute("data-date-presentation");
            document.getElementById("date-limite").textContent = button.getAttribute("data-date-limite");
            document.getElementById("date-paie").textContent = button.getAttribute("data-datepaie");

            var releveDetails = document.getElementById("releve-details");
            releveDetails.innerHTML = '';

            var codeReleve = button.getAttribute("data-code-releve") || "Non spécifié";
            var consommation = button.getAttribute("data-consommation") || "Non spécifié";
            var pu = button.getAttribute("data-pu") || "Non spécifié";

            var newReleve = document.createElement('div');
            newReleve.classList.add('col-md-6');
            newReleve.innerHTML = `
                <h6>Relevé</h6>
                <table class="table table-sm">
                    <tr>
                        <td>N° Relevé :</td>
                        <td>${codeReleve}</td>
                    </tr>
                    <tr>
                        <td>Consommation :</td>
                        <td>${consommation}</td>
                    </tr>
                    <tr>
                        <td>Prix unitaire :</td>
                        <td>${pu} Ar</td>
                    </tr>
                </table>
            `;

            releveDetails.appendChild(newReleve);

            document.getElementById("total-ttc").textContent = montant;

            document.getElementById('download-pdf').addEventListener('click', function() {
                var idpaye = button.getAttribute("data-idpaye");
                window.location.href = 'generate_pdf.php?idpaye=' + idpaye;
            });
        });
    });
</script>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#compteurTable tbody tr');
        let hasVisibleRow = false;

        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            const isVisible = rowText.includes(searchValue);
            row.style.display = isVisible ? '' : 'none';

            if (isVisible) hasVisibleRow = true;
        });

        document.getElementById('noResultsMessage').style.display = hasVisibleRow ? 'none' : 'block';
    });
</script>

<script>
    document.getElementById('codecli').addEventListener('change', function () {
        var codecli = this.value;
        var codecompteurSelect = document.getElementById('codecompteur');

        codecompteurSelect.innerHTML = '<option value="">Chargement...</option>';

        if (codecli) {
            fetch('get_releves.php?codecli=' + codecli)
                .then(response => response.json())
                .then(data => {
                    codecompteurSelect.innerHTML = '<option value="">Sélectionner un relevé</option>';
                    data.forEach(function (releve) {
                        var option = document.createElement('option');
                        option.value = releve.codecompteur;
                        option.textContent = releve.code + ' (' + releve.type + ')';
                        codecompteurSelect.appendChild(option);
                    });
                });
        } else {
            codecompteurSelect.innerHTML = '<option value="">Sélectionner un client d\'abord</option>';
        }
    });

</script>
