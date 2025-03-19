<?php

session_start();

global $con;
include("siderbar.php");

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
        <h4 class="mb-0">Gestion des relevés</h4>
        <div class="d-flex gap-3">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
            <button class="btn new-modal" data-bs-toggle="modal" data-bs-target="#addModalEl">
                <i class="fas fa-plus"></i> Électricité
            </button>
            <button class="btn new-modal" data-bs-toggle="modal" data-bs-target="#addModalEa">
                <i class="fas fa-plus"></i> Eau
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
                    $query_selec = "SELECT SUM(valeur1) AS total_valeur1 FROM releve_elec";
                    $result=mysqli_query($con, $query_selec);
                    $row=mysqli_fetch_assoc($result);
                    $total_valeur1 = $row['total_valeur1'];

                    $max_totelec=100;
                    $progress=($total_valeur1/ $max_totelec)*100;
                    $progress=min($progress, 100);
                    ?>
                    <h6 class="text-muted">Consommation Électricité</h6>
                    <h3><?php echo $row['total_valeur1'];?> kWh</h3>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?php echo $progress;?>%;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-bolt icon"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <?php
                    include 'database.php';
                    $query_seleau = "SELECT SUM(valeur2) AS total_valeur2 FROM releve_eau";
                    $result=mysqli_query($con, $query_seleau);
                    $row=mysqli_fetch_assoc($result);
                    $total_valeur2 = $row['total_valeur2'];

                    $max_toteau=100;
                    $progress=($total_valeur2/ $max_toteau)*100;
                    $progress=min($progress, 100);
                    ?>
                    <h6 class="text-muted">Consommation Eau</h6>
                    <h3><?php echo $row['total_valeur2'];?> m³</h3>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: <?php echo $progress;?>%;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-tint icon"></i>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['status'])): ?>
        <div class="alert <?= strpos($_SESSION['status'], 'réussie') !== false ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
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

                $query_elec = "SELECT * FROM releve_elec";
                $query_run_elec = mysqli_query($con, $query_elec);

                if (!$query_run_elec) {
                    die("Erreur SQL Electricité: " . mysqli_error($con));
                }

                $query_eau = "SELECT * FROM releve_eau";
                $query_run_eau = mysqli_query($con, $query_eau);

                if (!$query_run_eau) {
                    die("Erreur SQL Eau: " . mysqli_error($con));
                }
                ?>
                <table class="table" id="compteurTable">
                    <thead>
                    <tr>
                        <th>N° Relevé</th>
                        <th>N° Compteur</th>
                        <th>kWh</th>
                        <th>m<sup>3</sup></th>
                        <th>Date de relevé</th>
                        <th>Date de présentation</th>
                        <th>Date limite</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <?php
                    if ($query_run_elec && mysqli_num_rows($query_run_elec) > 0) {
                    while ($row = mysqli_fetch_array($query_run_elec)) {
                    ?>
                    <tbody>
                    <tr>
                        <td><?php echo $row['codeElec'];?></td>
                        <td><?php echo $row['codecompteur'];?></td>
                        <td><?php echo $row['valeur1'];?></td>
                        <td></td>
                        <td><?php echo $row['date_releve'];?></td>
                        <td><?php echo $row['date_presentation'];?></td>
                        <td><?php echo $row['date_limite_paie'];?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo htmlspecialchars($row['codeElec']); ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['codeElec']; ?>" title="Supprimer">
                                    <i class="fas fa-trash me-2"></i>Elec
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php while ($row = mysqli_fetch_assoc($query_run_eau)) { ?>
                    <tr>
                        <td><?php echo $row['codeEau'];?></td>
                        <td><?php echo $row['codecompteur'];?></td>
                        <td></td>
                        <td><?php echo $row['valeur2'];?></td>
                        <td><?php echo $row['date_releve2'];?></td>
                        <td><?php echo $row['date_presentation2'];?></td>
                        <td><?php echo $row['date_limite_paie2'];?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-warning"  data-bs-toggle="modal" data-bs-target="#editModal1-<?php echo htmlspecialchars($row['codeEau']); ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal1" data-id="<?php echo htmlspecialchars($row['codeEau']); ?>">
                                    <i class="fas fa-trash me-2"></i>Eau
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
<div class="modal fade" id="addModalEl" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Relevé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php
                include 'database.php';

                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saveElec'])){
                    $codeElec=trim($_POST['codeElec']);
                    $codecompteur=trim($_POST['codecompteur']);
                    $valeur1=trim($_POST['valeur1']);
                    $date_releve=trim($_POST['date_releve']);
                    $date_presentation=trim($_POST['date_presentation']);
                    $date_limite_paie=trim($_POST['date_limite_paie']);

                    $check_compteur_query = "SELECT * FROM compteur WHERE codecompteur = ?";
                    $stmt = mysqli_prepare($con, $check_compteur_query);
                    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 0) {
                        echo "<script type='text/javascript'>
                            window.location.href = 'releves.php';
                          </script>";
                        $_SESSION['status'] ="Le numéro de compteur existe déjà.";
                        exit();
                    }
                    mysqli_stmt_close($stmt);

                    $check_relevElec_query = "SELECT * FROM releve_elec WHERE codeElec = ?";
                    $stmt = mysqli_prepare($con, $check_relevElec_query);
                    mysqli_stmt_bind_param($stmt, "s", $codeElec);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        echo "<script type='text/javascript'>
                            window.location.href = 'releves.php';
                          </script>";
                        $_SESSION['status'] ="Le numéro de compteur existe déjà.";
                        exit();
                    }

                    $query="INSERT INTO releve_elec (codeElec, codecompteur, valeur1, date_releve, date_presentation, date_limite_paie) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt,"ssssss", $codeElec, $codecompteur, $valeur1, $date_releve, $date_presentation, $date_limite_paie);

                    if(mysqli_stmt_execute($stmt)){
                        $_SESSION ['status'] = "L'ajout du relevé avec succès !";
                        echo "<script type='text/javascript'>
                            window.location.href = 'releves.php';
                          </script>";
                    }else{
                        echo json_encode(["status" => "error", "message" => "Erreur lors de l'ajout du relevé."]);
                    }
                    mysqli_stmt_close($stmt);
                }
                ?>
                <form method="POST" action="releves.php">
                    <!-- Relevé Électricité -->
                    <h6 class="mb-3 mt-4">Relevé Électricité</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">N° Relevé</label>
                            <input type="text" class="form-control" name="codeElec">
                        </div>
                        <div class="col-md-6 mb-3">
                            <?php
                            include 'database.php';
                            ?>
                            <label class="form-label">N° Compteur</label>
                            <select class="form-select" name="codecompteur" required>
                                <?php
                                $sql = "SELECT codecompteur FROM `compteur` WHERE type='elec' ORDER BY codecompteur ASC";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row["codecompteur"].'">'.$row["codecompteur"].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valeur</label>
                            <input type="number" class="form-control" name="valeur1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Relevé</label>
                            <input type="date" class="form-control" name="date_releve">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Présentation</label>
                            <input type="date" class="form-control" name="date_presentation">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Limite Paiement</label>
                            <input type="date" class="form-control" name="date_limite_paie">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="saveElec" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addModalEa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Relevé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php
                include 'database.php';

                if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save-eau'])){
                    $codeEau=trim($_POST['codeEau']);
                    $codecompteur=trim($_POST['codecompteur']);
                    $valeur2=trim($_POST['valeur2']);
                    $date_releve2=trim($_POST['date_releve2']);
                    $date_presentation2=trim($_POST['date_presentation2']);
                    $date_limite_paie2=trim($_POST['date_limite_paie2']);

                    $check_compteur_query = "SELECT * FROM compteur WHERE codecompteur = ?";
                    $stmt = mysqli_prepare($con, $check_compteur_query);
                    mysqli_stmt_bind_param($stmt, "s", $codecompteur);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) == 0) {
                        $_SESSION ['status'] = "Le numéro de compteur existe déjà.";
                        echo "<script type='text/javascript'>
                            window.location.href = 'releves.php';
                          </script>";
                        exit();
                    }
                    mysqli_stmt_close($stmt);

                    $check_relevEau_query = "SELECT * FROM releve_eau WHERE codeEau = ?";
                    $stmt = mysqli_prepare($con, $check_relevEau_query);
                    mysqli_stmt_bind_param($stmt, "s", $codeEau);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $_SESSION ['status'] = "Le numéro de compteur existe déjà.";
                        echo "<script type='text/javascript'>
                            window.location.href = 'releves.php';
                          </script>";
                        exit();
                    }

                    $query="INSERT INTO releve_eau (codeEau, codecompteur, valeur2, date_releve2, date_presentation2, date_limite_paie2) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $query);
                    mysqli_stmt_bind_param($stmt,"ssssss", $codeEau, $codecompteur, $valeur2, $date_releve2, $date_presentation2, $date_limite_paie2);

                    if(mysqli_stmt_execute($stmt)){
                        $_SESSION ['status'] = "L'ajout du relevé avec succès !";
                        echo "<script type='text/javascript'>
                              window.location.href = 'releves.php';
                              </script>";
                    }else{
                        $_SESSION ['status'] = "Erreur lors de l'ajout du relevé.";
                        echo "<script type='text/javascript'>
                              window.location.href = 'releves.php';
                              </script>";
                    }
                    mysqli_stmt_close($stmt);
                }
                ?>
                <form method="POST" action="releves.php">
                    <!-- Relevé Électricité -->
                    <h6 class="mb-3 mt-4">Relevé Eau</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">N° Relevé</label>
                            <input type="text" class="form-control" name="codeEau">
                        </div>
                        <div class="col-md-6 mb-3">
                            <?php
                            include 'database.php';
                            ?>
                            <label class="form-label">N° Compteur</label>
                            <select class="form-select" name="codecompteur" required>
                                <?php
                                $sql = "SELECT codecompteur FROM `compteur` WHERE type='eau' ORDER BY codecompteur ASC";
                                $result = mysqli_query($con, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row["codecompteur"].'">'.$row["codecompteur"].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valeur</label>
                            <input type="number" class="form-control" name="valeur2">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Relevé</label>
                            <input type="date" class="form-control" name="date_releve2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Présentation</label>
                            <input type="date" class="form-control" name="date_presentation2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date Limite Paiement</label>
                            <input type="date" class="form-control" name="date_limite_paie2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="save-eau" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->

<?php
include 'database.php';

if (isset($_POST['up-rel'])) {
    $codeElec = mysqli_real_escape_string($con, $_POST['codeElec']);
    $codecompteur = mysqli_real_escape_string($con, $_POST['codecompteur']);
    $valeur1 = mysqli_real_escape_string($con, $_POST['valeur1']);
    $date_releve = mysqli_real_escape_string($con, $_POST['date_releve']);
    $date_presentation = mysqli_real_escape_string($con, $_POST['date_presentation']);
    $date_limite_paie = mysqli_real_escape_string($con, $_POST['date_limite_paie']);

    $query = "UPDATE releve_elec SET codecompteur='$codecompteur', valeur1='$valeur1', 
              date_releve='$date_releve', date_presentation='$date_presentation', date_limite_paie='$date_limite_paie' 
              WHERE codeElec='$codeElec'";

    if (mysqli_query($con, $query)){
        $query_compteur = "SELECT pu FROM compteur WHERE codecompteur='$codecompteur' AND type='elec'";
        $result_compteur = mysqli_query($con, $query_compteur);
        if (mysqli_num_rows($result_compteur) > 0) {
            $compteur = mysqli_fetch_assoc($result_compteur);
            $pu = $compteur['pu'];
            $montant = $pu * $valeur1;
            $query_update_payer = "UPDATE payer SET montant ='$montant' WHERE codecli IN 
                                    (SELECT codecli FROM client WHERE codecli IN 
                                    (SELECT codecli FROM compteur WHERE codecompteur='$codecompteur'))";
            mysqli_query($con, $query_update_payer);
        }
        $_SESSION ['status'] = "Modification du relevé avec success !";
        echo "<script type='text/javascript'>
                window.location.href = 'releves.php';
              </script>";
    } else {
        echo "Erreur lors de mise à jour du relevé : " . mysqli_error($con);
    }
}
$query = "SELECT * FROM releve_elec";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $codecompteur = $row['codecompteur'];

    $compteur_query = "SELECT * FROM compteur WHERE codecompteur = '$codecompteur'";
    $compteur_result = mysqli_query($con, $compteur_query);
    if ($compteur_result && mysqli_num_rows($compteur_result) > 0) {
        $compteur = mysqli_fetch_assoc($compteur_result);
        $codecompteur = $compteur['codecompteur'];
    } else {
        $codecompteur = "";
    }
    ?>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal-<?php echo htmlspecialchars($row['codeElec']); ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo htmlspecialchars($row['codeElec']); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-<?php echo htmlspecialchars($row['codeElec']); ?>">Modifier la Facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="releves.php">
                        <h6 class="mb-3">Informations Client</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° Relevé</label>
                                <input type="text" class="form-control" name="codeElec" value="<?php echo htmlspecialchars($row['codeElec']); ?>" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° Compteur</label>
                                <select class="form-select" name="codecompteur" required>
                                    <?php
                                    $sql = "SELECT codecompteur FROM `compteur` WHERE type='elec' ORDER BY codecompteur ASC";
                                    $compteur_result = mysqli_query($con, $sql);
                                    if (mysqli_num_rows($compteur_result) > 0) {
                                        while($compteur_row = mysqli_fetch_assoc($compteur_result)) {
                                            $selected = ($codecompteur == $compteur_row['codecompteur']) ? 'selected' : '';
                                            echo '<option value="'. htmlspecialchars($compteur_row['codecompteur']) .'"' . $selected . '>' . htmlspecialchars($compteur_row['codecompteur']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valeur</label>
                                <input type="number" class="form-control" name="valeur1" value="<?php echo htmlspecialchars($row['valeur1']); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Relevé</label>
                                <input type="date" class="form-control" name="date_releve" value="<?php echo date('Y-m-d', strtotime($row['date_releve'])); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Présentation</label>
                                <input type="date" class="form-control" name="date_presentation" value="<?php echo date('Y-m-d', strtotime($row['date_presentation'])); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Limite Paiement</label>
                                <input type="date" class="form-control" name="date_limite_paie" value="<?php echo date('Y-m-d', strtotime($row['date_limite_paie'])); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="up-rel" class="btn btn-warning">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
include 'database.php';

if (isset($_POST['up-eau'])) {
    $codeEau = mysqli_real_escape_string($con, $_POST['codeEau']);
    $codecompteur = mysqli_real_escape_string($con, $_POST['codecompteur']);
    $valeur2 = mysqli_real_escape_string($con, $_POST['valeur2']);
    $date_releve2 = mysqli_real_escape_string($con, $_POST['date_releve2']);
    $date_presentation2 = mysqli_real_escape_string($con, $_POST['date_presentation2']);
    $date_limite_paie2 = mysqli_real_escape_string($con, $_POST['date_limite_paie2']);

    $query = "UPDATE releve_eau SET codecompteur='$codecompteur', valeur2='$valeur2', 
              date_releve2='$date_releve2', date_presentation2='$date_presentation2', 
              date_limite_paie2='$date_limite_paie2' WHERE codeEau='$codeEau'";

    if (mysqli_query($con, $query)) {
        $query_compteur = "SELECT pu FROM compteur WHERE codecompteur = '$codecompteur' AND type='eau'";
        $result_compteur = mysqli_query($con, $query_compteur);
        if ($result_compteur && mysqli_num_rows($result_compteur) > 0) {
            $compteur = mysqli_fetch_assoc($result_compteur);
            $pu = $compteur['pu'];

            $montant = $pu * $valeur2;

            $query_update_payer = "UPDATE payer 
                                   SET montant='$montant' 
                                   WHERE codecli IN (SELECT codecli FROM client WHERE codecli IN 
                                                     (SELECT codecli FROM compteur WHERE codecompteur='$codecompteur'))";
            mysqli_query($con, $query_update_payer);
        }

        $_SESSION['status'] = "Modification du relevé avec succès!";
        echo "<script type='text/javascript'>
                window.location.href = 'releves.php';
              </script>";
    } else {
        echo "Erreur lors de mise à jour du relevé : " . mysqli_error($con);
    }
}

$query = "SELECT * FROM releve_eau";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $codecompteur = $row['codecompteur'];

    $compteur_query = "SELECT * FROM compteur WHERE codecompteur = '$codecompteur'";
    $compteur_result = mysqli_query($con, $compteur_query);
    if ($compteur_result && mysqli_num_rows($compteur_result) > 0) {
        $compteur = mysqli_fetch_assoc($compteur_result);
        $codecompteur = $compteur['codecompteur'];
    } else {
        $codecompteur = "";
    }
    ?>

    <!-- Modal de modification -->
    <div class="modal fade" id="editModal1-<?php echo htmlspecialchars($row['codeEau']); ?>" tabindex="-1" aria-labelledby="editModalLabel1-<?php echo htmlspecialchars($row['codeEau']); ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel1-<?php echo htmlspecialchars($row['codeEau']); ?>">Modifier la Facture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="releves.php">
                        <h6 class="mb-3">Informations Client</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° Relevé</label>
                                <input type="text" class="form-control" name="codeEau" value="<?php echo htmlspecialchars($row['codeEau']); ?>" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">N° Compteur</label>
                                <select class="form-select" name="codecompteur" required>
                                    <?php
                                    $sql = "SELECT codecompteur FROM `compteur` WHERE type='eau' ORDER BY codecompteur ASC";
                                    $compteur_result = mysqli_query($con, $sql);
                                    if (mysqli_num_rows($compteur_result) > 0) {
                                        while ($compteur_row = mysqli_fetch_assoc($compteur_result)) {
                                            $selected = ($codecompteur == $compteur_row['codecompteur']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($compteur_row['codecompteur']) . '" ' . $selected . '>' . htmlspecialchars($compteur_row['codecompteur']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Valeur</label>
                                <input type="number" class="form-control" name="valeur2" value="<?php echo htmlspecialchars($row['valeur2']); ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Relevé</label>
                                <input type="date" class="form-control" name="date_releve2" value="<?php echo date('Y-m-d', strtotime($row['date_releve2'])); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Présentation</label>
                                <input type="date" class="form-control" name="date_presentation2" value="<?php echo date('Y-m-d', strtotime($row['date_presentation2'])); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date Limite Paiement</label>
                                <input type="date" class="form-control" name="date_limite_paie2" value="<?php echo date('Y-m-d', strtotime($row['date_limite_paie2'])); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="up-eau" class="btn btn-warning">Modifier</button>
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

if (isset($_POST['del-releve'])) {
    $codeElec = mysqli_real_escape_string($con, $_POST['codeElec']);

    $query = "DELETE FROM releve_elec WHERE codeElec = '$codeElec'";

    if (mysqli_query($con, $query)) {
        $_SESSION ['status'] = "Suppression du relevé avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'releves.php';
              </script>";
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
}
?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la Relevé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="releves.php">
                    <div class="alert alert-danger mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cette facture ?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="codeElec" id="codeElecId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="del-releve" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'database.php';

if (isset($_POST['del-releves'])) {
    $codeEau = mysqli_real_escape_string($con, $_POST['codeEau']);

    $query = "DELETE FROM releve_eau WHERE codeEau = '$codeEau'";

    if (mysqli_query($con, $query)) {
        $_SESSION ['status'] = "Suppression du relevé avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'releves.php';
              </script>";
    } else {
        echo "Erreur lors de la suppression : " . mysqli_error($con);
    }
}
?>

<div class="modal fade" id="deleteModal1" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer la Relevé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="releves.php">
                    <div class="alert alert-danger mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Êtes-vous sûr de vouloir supprimer cette facture ?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="codeEau" id="codeEauId">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" name="del-releves" class="btn btn-danger">
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
<script>
    const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const codeElecId = this.getAttribute('data-id');
            document.getElementById('codeElecId').value = codeElecId;
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal1"]');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const codeEauId = this.getAttribute('data-id');
                document.getElementById('codeEauId').value = codeEauId;
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
