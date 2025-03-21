<?php

session_start();

global $con;
include ('siderbar.php');

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
        <h4 class="mb-0">Compteurs</h4>
        <div class="d-flex gap-3">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
            <button class="btn new-modal" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Ajouter de compteur
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
                    $selectquery = "SELECT SUM(pu) AS total_pu FROM compteur";
                    $result = mysqli_query($con, $selectquery);
                    $row = mysqli_fetch_assoc($result);
                    $total_pu = $row['total_pu'];

                    $max_pu = 100;
                    $progress = ($total_pu / $max_pu) * 100;
                    $progress = min($progress,100);

                    ?>
                    <h6 class="text-muted">Total Prix Unitaire</h6>
                    <h3><?php echo $row['total_pu'];?> Ar</h3>
                    <div class="progress">
                        <div class="progress-bar bg-primary" style="width: <?php echo $progress;?>px;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-money-bill icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <?php
                include 'database.php';
                $selectquery = "SELECT COUNT(*) AS total_elec FROM compteur WHERE type = 'elec'";
                $result = mysqli_query($con, $selectquery);
                $row = mysqli_fetch_assoc($result);
                $total_elect = $row['total_elec'];

                $max_elec = 100;
                $progress = ($total_elect / $max_elec) * 100;
                $progress = min($progress,100);

                ?>

                <?php
                include 'database.php';
                $selectsquery = "SELECT COUNT(*) AS total_eau FROM compteur WHERE type = 'eau'";
                $result = mysqli_query($con, $selectsquery);
                $row = mysqli_fetch_assoc($result);
                $total_eau = $row['total_eau'];

                $max_eau = 100;
                $progress = ($total_eau / $max_eau) * 100;
                $progress = min($progress,100);

                ?>
                <div class="card-body">
                    <h6 class="text-muted">Nombres Électricités</h6>
                    <h3><?php echo $total_elect;?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?php echo $progress;?>%;"
                            aria-valuenow="<?php echo $progress;?>"
                            aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <i class="fas fa-bolt icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted">Nombres Eau</h6>
                    <h3><?php echo $total_eau;?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: <?php echo $progress;?>%;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <i class="fas fa-tint icon"></i>
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

                $showquery = "SELECT * FROM compteur";
                $query_run=mysqli_query($con, $showquery);

                ?>

                <table class="table" id="compteurTable">
                    <thead>
                    <tr>
                        <th>N° Compteur</th>
                        <th>Référence du client</th>
                        <th>Type</th>
                        <th>Prix Unitaire</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <?php
                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_array($query_run)) {
                            ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['codecompteur']; ?></td>
                        <td><?php echo $row['codecli']; ?></td>
                        <td><?php echo $row['type']; ?></td>
                        <td><?php echo $row['pu']; ?> <span>Ar</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo htmlspecialchars($row['codecompteur']); ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['codecompteur']; ?>" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center text-danger'><strong>Aucun compteur trouvé dans la base de données.</strong></td></tr>";
                    }
                    ?>
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
                <h5 class="modal-title">Nouvelle Compteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <?php

                include 'database.php';

                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-btn'])) {
                    $codecompteur = trim($_POST['codecompteur']);
                    $codeclient = trim($_POST['codecli']);
                    $type = trim($_POST['type']);
                    $pu = trim($_POST['pu']);

                    $check_client_query = "SELECT * FROM client WHERE codecli = ?";
                    $stmt = mysqli_prepare($con, $check_client_query);
                    mysqli_stmt_bind_param($stmt, "s", $codeclient);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 0) {
                        echo "<script type='text/javascript'>
                              window.location.href = 'compteur.php';
                              </script>";
                        $_SESSION['status'] = "Le client n'existe pas dans la base de données.";
                        exit();
                    }

                    mysqli_stmt_close($stmt);

                    $check_compteur_query = "SELECT * FROM compteur WHERE codecompteur = ?";
                    $stmt = mysqli_prepare($con, $check_compteur_query);
                    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        echo "<script type='text/javascript'>
                              window.location.href = 'compteur.php';
                              </script>";
                        $_SESSION['status']="Le numéro de compteur existe déjà.";
                        exit();
                    }

                    $query = "INSERT INTO compteur (codecompteur, type, pu, codecli) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt, "ssss", $codecompteur, $type, $pu, $codeclient);

                    if (mysqli_stmt_execute($stmt)) {
                        $_SESSION['status'] = "L'ajout du Compteur avec succès !";
                        echo "<script type='text/javascript'>
                                window.location.href = 'compteur.php';
                              </script>";
                    } else {
                        $_SESSION['status'] = "Erreur lors de l'ajout du Compteur";
                    }

                    mysqli_stmt_close($stmt);
                }
                ?>

                <form method="POST" action="compteur.php">
                    <!-- Informations Compteur -->
                    <h6 class="mb-3 mt-4">Informations Compteur</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">N° Compteur</label>
                            <input type="text" class="form-control" name="codecompteur" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" required>
                                <option value="">Sélectionner</option>
                                <option value="ELEC">Électricité</option>
                                <option value="EAU">Eau</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?php
                            include 'database.php';
                            ?>
                            <label class="form-label">Référence du client</label>
                            <select class="form-select" name="codecli" required>
                                <option value="">Sélectionner</option>
                                <?php
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prix Unitaire</label>
                            <input type="number" step="0.01" class="form-control" name="pu" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="save-btn" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<?php
include 'database.php';

if (isset($_POST['modif-compteur'])) {
    $codecompteur = mysqli_real_escape_string($con, $_POST['codecompteur']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $pu = mysqli_real_escape_string($con, $_POST['pu']);
    $codecli = mysqli_real_escape_string($con, $_POST['codecli']);

    $query = "UPDATE compteur SET type='$type', pu='$pu', codecli='$codecli' WHERE codecompteur='$codecompteur'";

    if (mysqli_query($con, $query)) {
        $_SESSION ['status'] = "Mise à jour du Compteur avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'compteur.php';
              </script>";
    } else {
        echo "Erreur de mise à jour du compteur : " . mysqli_error($con);
    }
}

$query = "SELECT * FROM compteur";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $codecompteur = htmlspecialchars($row['codecompteur']);
    $codecli = htmlspecialchars($row['codecli']);

    $client_query = "SELECT nom FROM client WHERE codecli = '$codecli'";
    $client_result = mysqli_query($con, $client_query);

    if ($client_result && mysqli_num_rows($client_result) > 0) {
        $client = mysqli_fetch_assoc($client_result);
        $client_name = htmlspecialchars($client['nom']);
    } else {
        $client_name = "Inconnu";
    }
    ?>

    <!-- Modal pour chaque compteur -->
    <div class="modal fade" id="editModal-<?php echo $codecompteur; ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo $codecompteur; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-<?php echo $codecompteur; ?>">Modifier le Compteur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="compteur.php">
                        <h6 class="mb-3 mt-4">Informations Compteur</h6>
                        <div class="row">
                            <!-- N° Compteur -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° Compteur</label>
                                <input type="text" class="form-control" name="codecompteur" value="<?php echo $codecompteur; ?>" required readonly>
                            </div>
                            <!-- Type du compteur -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type" required>
                                    <option value="ELEC" <?php echo $row['type'] == 'ELEC' ? 'selected' : ''; ?>>Électricité</option>
                                    <option value="EAU" <?php echo $row['type'] == 'EAU' ? 'selected' : ''; ?>>Eau</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Référence du client -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Référence du client</label>
                                <select class="form-select" name="codecli" required>
                                    <option value="">Sélectionner</option>
                                    <?php
                                    $sql = "SELECT codecli, nom FROM client ORDER BY nom ASC";
                                    $client_result = $con->query($sql);
                                    if ($client_result->num_rows > 0) {
                                        while ($client_row = $client_result->fetch_assoc()) {
                                            $selected = ($client_row['codecli'] == $codecli) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($client_row['codecli']) . '" ' . $selected . '>' . htmlspecialchars($client_row['nom']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- Prix Unitaire -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prix Unitaire</label>
                                <input type="number" step="0.01" class="form-control" name="pu" value="<?php echo htmlspecialchars($row['pu']); ?>" readonly>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="modif-compteur" class="btn btn-warning">Modifier</button>
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

if (isset($_POST['delete-compteur'])) {
    $codecompteur = mysqli_real_escape_string($con, $_POST['codecompteur']);

    $query = "DELETE FROM compteur WHERE codecompteur = '$codecompteur'";

    if (mysqli_query($con, $query)) {
        $_SESSION ['status'] = "Suppression du Compteur avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'compteur.php';
              </script>";
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
}
?>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer le Compteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="compteur.php">
                    <!-- Message de confirmation -->
                    <div class="alert alert-danger mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Êtes-vous sûr de vouloir supprimer ce compteur ?
                    </div>
                    <input type="hidden" name="codecompteur" id="compteurId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="delete-compteur" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const compteurId = this.getAttribute('data-id');
            document.getElementById('compteurId').value = compteurId;
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

