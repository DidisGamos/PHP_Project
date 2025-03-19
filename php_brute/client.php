<?php

global $con, $quartier;
session_start();

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
        <h4 class="mb-0">Clients</h4>
        <div class="d-flex gap-3">
            <form method="POST">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchClient" name="search" class="form-control" placeholder="Rechercher...">
                </div>
            </form>
            <button class="btn new-modal" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Ajouter Clients
            </button>
        </div>
    </div>

    <!-- Carte de progression -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <?php
                include 'database.php';

                $query = "SELECT COUNT(*) AS total_clients FROM client";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                $total_clients = $row['total_clients'];

                $max_clients = 100;
                $progress = ($total_clients / $max_clients) * 100;
                $progress = min($progress, 100);

                ?>
                <div class="card-body">
                    <h6 class="text-muted">Total des clients</h6>
                    <h3><?php echo $total_clients; ?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: <?php echo $progress; ?>%;"
                             aria-valuenow="<?php echo $progress; ?>"
                             aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <i class="fa-solid fa-users icon"></i>
                </div>
            </div>
        </div>
        <?php
        include 'database.php';

        $query = "SELECT COUNT(*) AS total_male FROM client WHERE sexe='M'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $total_male = $row['total_male'];

        $max_male = 100;
        $progress = ($total_male / $max_male) * 100;
        $progress = min($progress, 100);

        ?>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="text-muted">Nombre de clients masculins</h6>
                    <h3><?php echo $total_male; ?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?php echo $progress; ?>%;"
                             aria-valuenow="<?php echo $progress; ?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-male icon"></i>
                </div>
            </div>
        </div>
        <?php
        include 'database.php';

        $query = "SELECT COUNT(*) AS total_female FROM client WHERE sexe='F'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_assoc($result);
        $total_female = $row['total_female'];

        $max_female = 100;
        $progress = ($total_female / $max_female) * 100;
        $progress = min($progress, 100);

        ?>
        <div class="col-md-3">
            <div class="card stats-card">
                <div class="card-body">
                        <h6 class="text-muted">Nombre de clients féminins</h6>
                    <h3><?php echo $total_female; ?></h3>
                    <div class="progress">
                        <div class="progress-bar bg-info" style="width: <?php echo $progress; ?>%;"
                             aria-valuenow="<?php echo $progress; ?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-female icon"></i>
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

    <div class="col">
        <div class="col-md-2 mb-3">
            <?php
            $quartier = isset($_POST['quartier']) ? $_POST['quartier'] : '';

            if (isset($_POST['reset'])) {
                $quartier = '';
            }
            ?>
            <form method="POST" action="client.php">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <!-- Sélecteur de quartier -->
                    <select class="form-select col-md-2" name="quartier" onchange="this.form.submit()">
                        <option value="" <?php echo ($quartier == '') ? 'selected' : ''; ?>>Sélectionner Quartier</option>
                        <option value="Amboriky" <?php echo ($quartier == 'Amboriky') ? 'selected' : ''; ?>>Amboriky</option>
                        <option value="Tsimenatsy" <?php echo ($quartier == 'Tsimenatsy') ? 'selected' : ''; ?>>Tsimenatsy</option>
                        <option value="Tsianaloka" <?php echo ($quartier == 'Tsianaloka') ? 'selected' : ''; ?>>Tsianaloka</option>
                        <option value="Anketa" <?php echo ($quartier == 'Anketa') ? 'selected' : ''; ?>>Anketa</option>
                        <option value="Sanfil" <?php echo ($quartier == 'Sanfil') ? 'selected' : ''; ?>>Sanfil</option>
                        <option value="Ampasikibo" <?php echo ($quartier == 'Ampasikibo') ? 'selected' : ''; ?>>Ampasikibo</option>
                        <option value="Ankilifaly" <?php echo ($quartier == 'Ankilifaly') ? 'selected' : ''; ?>>Ankilifaly</option>
                        <option value="Betania" <?php echo ($quartier == 'Betania') ? 'selected' : ''; ?>>Betania</option>
                        <option value="Mitsinjo" <?php echo ($quartier == 'Mitsinjo') ? 'selected' : ''; ?>>Mitsinjo</option>
                    </select>
                    <!-- Bouton de réinitialisation -->
                    <button type="submit" name="reset" value="1" class="btn btn-primary col-md-10">
                        <i class="fa fa-refresh me-2"></i>Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">

                <?php
                include 'database.php';

                $search = "";
                $quartier = "";
                $condition = " WHERE 1";

                if (isset($_POST['quartier']) && !empty($_POST['quartier'])) {
                    $quartier = mysqli_real_escape_string($con, $_POST['quartier']);
                    $condition .= " AND quartier = '$quartier'";
                }

                if (isset($_POST['search']) && !empty($_POST['search'])) {
                    $search = mysqli_real_escape_string($con, $_POST['search']);
                    $condition .= " AND (codecli LIKE '%$search%' OR nom LIKE '%$search%')";
                }

                $query = "SELECT * FROM client" . $condition;
                $query_run = mysqli_query($con, $query);
                ?>


                <table class="table">
                    <thead>
                    <tr>
                        <th>N°</th>
                        <th>Client</th>
                        <th>Sexe</th>
                        <th>Quartier</th>
                        <th>Niveau</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="resultatRecherche">
                    <?php
                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                    <tr>
                        <td><?php echo $row['codecli']; ?></td>
                        <td><?php echo $row['nom']; ?></td>
                        <td><?php echo $row['sexe']; ?></td>
                        <td><?php echo $row['quartier']; ?></td>
                        <td><?php echo $row['niveau']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal-<?php echo $row['codecli']; ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?php echo $row['codecli']; ?>" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center text-danger'><strong>Aucun client trouvé.</strong></td></tr>";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Ajouter Clients -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter Clients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="client.php">
                    <!-- Informations Client -->
                    <h6 class="mb-3">Informations Client</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Référence Client</label>
                            <input type="text" class="form-control" name="codecli" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="nom" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sexe</label>
                            <select class="form-select" name="sexe" required>
                                <option value="">Sélectionner</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quartier</label>
                            <input type="text" class="form-control" name="quartier" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Niveau</label>
                            <select class="form-select" name="niveau" required>
                                <option value="">Sélectionner</option>
                                <option value="1">Niveau 1</option>
                                <option value="2">Niveau 2</option>
                                <option value="3">Niveau 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary" name="btn-save">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Formulaire de modification -->
<?php
include 'database.php';

if (isset($_POST['btn-up'])) {
    $codecli = mysqli_real_escape_string($con, $_POST['codecli']);
    $nom = mysqli_real_escape_string($con, $_POST['nom']);
    $sexe = mysqli_real_escape_string($con, $_POST['sexe']);
    $quartier = mysqli_real_escape_string($con, $_POST['quartier']);
    $niveau = mysqli_real_escape_string($con, $_POST['niveau']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $query = "UPDATE client SET nom='$nom', sexe='$sexe', quartier='$quartier', niveau='$niveau', email='$email' WHERE codecli='$codecli'";

    if (mysqli_query($con, $query)) {
        $_SESSION['status']="Modification du client avec réussie !";
        echo "<script type='text/javascript'>
              window.location.href = 'client.php';
              </script>";
    } else {
        echo "Erreur de mise à jour : " . mysqli_error($con);
    }
}
?>

<?php
include 'database.php';

$query = "SELECT * FROM client";
$result = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <!-- Modal pour éditer un client -->
    <div class="modal fade" id="editModal-<?php echo $row['codecli']; ?>" tabindex="-1" aria-labelledby="editModalLabel-<?php echo $row['codecli']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-<?php echo $row['codecli']; ?>">Modifier le Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="client.php">
                        <h6 class="mb-3">Informations Client</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Référence Client</label>
                                <input type="text" class="form-control" name="codecli" value="<?php echo $row['codecli']; ?>" required readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control" name="nom" value="<?php echo $row['nom']; ?>" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sexe</label>
                                <select class="form-select" name="sexe" required>
                                    <option value="M" <?php echo $row['sexe'] == 'M' ? 'selected' : ''; ?>>Masculin</option>
                                    <option value="F" <?php echo $row['sexe'] == 'F' ? 'selected' : ''; ?>>Féminin</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Quartier</label>
                                <input type="text" class="form-control" name="quartier" value="<?php echo $row['quartier']; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Niveau</label>
                                <select class="form-select" name="niveau" required>
                                    <option value="1" <?php echo $row['niveau'] == '1' ? 'selected' : ''; ?>>Niveau 1</option>
                                    <option value="2" <?php echo $row['niveau'] == '2' ? 'selected' : ''; ?>>Niveau 2</option>
                                    <option value="3" <?php echo $row['niveau'] == '3' ? 'selected' : ''; ?>>Niveau 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="btn-up" class="btn btn-warning">Modifier</button>
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

if (isset($_POST['delete-client'])) {
    $codecli = mysqli_real_escape_string($con, $_POST['codecli']);

    $query = "DELETE FROM client WHERE codecli = '$codecli'";

    if (mysqli_query($con, $query)) {
        $_SESSION['status'] = "Le client a été supprimé avec succès !";
        echo "<script type='text/javascript'>
              window.location.href = 'client.php';
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
                    <h5 class="modal-title">Supprimer le Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="client.php">
                        <!-- Message de confirmation -->
                        <div class="alert alert-danger mt-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Attention :</strong> Cette action est irréversible. Êtes-vous sûr de vouloir supprimer ce client ?
                        </div>
                        <!-- Champ caché pour l'ID du client -->
                        <input type="hidden" name="codecli" id="clientId">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" name="delete-client" class="btn btn-danger">
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
    <script src="js/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#searchClient").on("keyup", function(){
                var query = $(this).val();
                var quartier = $("select[name='niveau']").val();
                $.ajax({
                    url: "client.php",
                    method: "POST",
                    data: { search: query, niveau: quartier },
                    success: function(data){
                        $("#resultatRecherche").html($(data).find("#resultatRecherche").html());
                    }
                });
            });
        });
    </script>
    <script>
        const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteModal"]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const clientId = this.getAttribute('data-id');
                document.getElementById('clientId').value = clientId;
            });
        });
    </script>


<?php

include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-save'])) {

    $codecli = trim($_POST['codecli']);
    $nom = trim($_POST['nom']);
    $sexe = trim($_POST['sexe']);
    $quartier = trim($_POST['quartier']);
    $niveau = trim($_POST['niveau']);
    $email = trim($_POST['email']);

    $check_email = "SELECT * FROM client WHERE email = ?";
    $stmt = mysqli_prepare($con, $check_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script type='text/javascript'>
              window.location.href = 'client.php';
              </script>";
        $_SESSION['status'] = "Cet email est déjà utilisé !";
        exit();
    }
    mysqli_stmt_close($stmt);

    $query = "INSERT INTO client (codecli, nom, sexe, quartier, niveau, email) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $codecli, $nom, $sexe, $quartier, $niveau, $email);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status'] = "Client ajouté avec réussie !";
        echo "<script type='text/javascript'>
                window.location.href = 'client.php';
              </script>";
    } else {
        $_SESSION['status'] = "Erreur lors de l'ajout du client. Veuillez réessayer.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

?>