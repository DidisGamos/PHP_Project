<?php

global $con;
session_start();
include ('siderbar.php');

?>

<style>
    :root {
        --sidebar-width: 250px;
        --primary-color: #1a237e;
        --secondary-color: #4a148c;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        --jirama-orange: #f39c12;
        --jirama-dark-orange: #e67e22;
        --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        --border-radius: 0.5rem;
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
        <h4 class="mb-0">Gestion des Payments</h4>
        <div class="d-flex gap-3">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stats-card">
                <?php
                include 'database.php';
                $selectquery = "SELECT SUM(montant) AS total_montant FROM payer";
                $result = mysqli_query($con, $selectquery);
                $row = mysqli_fetch_assoc($result);
                $total_montant = $row['total_montant'];

                $max_montant = 100;
                $progress = ($total_montant / $max_montant) * 100;
                $progress = min($progress,100);

                ?>
                <div class="card-body">
                    <h6 class="text-muted">Total à payer</h6>
                    <h3><?php echo $row['total_montant']?> Ar</h3>
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
                <div class="card-body">
                    <?php
                    include 'database.php';
                    $selectquery = "SELECT SUM(montant) AS total_lim3 
                                    FROM (SELECT montant FROM payer ORDER BY datepaie DESC LIMIT 3) AS last_three_paiements";
                    $result = mysqli_query($con, $selectquery);
                    $row = mysqli_fetch_assoc($result);
                    $total_lim3 = $row['total_lim3'];

                    $max_lim3 = 100;
                    $progress = ($total_lim3 / $max_lim3) * 100;
                    $progress = min($progress,100);

                    ?>
                    <h6 class="text-muted">Total des 3 dernières transactions</h6>
                    <h3><?php echo $row['total_lim3']?> Ar</h3>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: <?php echo $progress;?>px;"
                             aria-valuenow="<?php echo $progress;?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <i class="fas fa-money-bill icon"></i>
                </div>
            </div>
        </div>
    </div>

    <?php
    include 'database.php';

    // Récupérer le total des paiements par mois
    $query = "SELECT DATE_FORMAT(datepaie, '%M') AS mois, SUM(montant) AS total 
                  FROM PAYER WHERE YEAR(datepaie) = YEAR(CURDATE()) GROUP BY MONTH(datepaie), DATE_FORMAT(datepaie, '%M') 
                  ORDER BY MONTH(datepaie)";

    $query_run = mysqli_query($con, $query);

    $mois_labels = [];
    $montants = [];

    while ($row = mysqli_fetch_assoc($query_run)) {
        $mois_labels[] = $row['mois'];
        $montants[] = $row['total'];
    }
    ?>

    <!-- Graphique simplifié -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4">Évolution des paiements</h5>
            <canvas id="paiementChart"></canvas>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card">
        <div class="card-body">
            <?php
            include 'database.php';

            $query = "SELECT CLIENT.nom, COMPTEUR.type, 
       MAX(RELEVE_ELEC.valeur1) AS conso_elec, 
       MAX(RELEVE_ELEC.date_releve) AS date_releve_elec, 
       MAX(RELEVE_ELEC.date_presentation) AS date_pres_elec, 
       MAX(RELEVE_ELEC.date_limite_paie) AS date_limite_elec,
       MAX(RELEVE_EAU.valeur2) AS conso_eau, 
       MAX(RELEVE_EAU.date_releve2) AS date_releve_eau, 
       MAX(RELEVE_EAU.date_presentation2) AS date_pres_eau, 
       MAX(RELEVE_EAU.date_limite_paie2) AS date_limite_eau,
       MAX(PAYER.datepaie) AS datepaie, 
       MAX(PAYER.montant) AS montant
FROM CLIENT
INNER JOIN COMPTEUR ON CLIENT.codecli = COMPTEUR.codecli
LEFT JOIN RELEVE_ELEC ON COMPTEUR.codecompteur = RELEVE_ELEC.codecompteur
LEFT JOIN RELEVE_EAU ON COMPTEUR.codecompteur = RELEVE_EAU.codecompteur
LEFT JOIN PAYER ON CLIENT.codecli = PAYER.codecli
GROUP BY CLIENT.nom, COMPTEUR.type
ORDER BY COALESCE(MAX(RELEVE_ELEC.date_limite_paie), MAX(RELEVE_EAU.date_limite_paie2)) DESC";

            $query_run = mysqli_query($con, $query);
            ?>


            <div class="table-responsive">
                <table class="table" id="compteurTable">
                    <thead>
                    <tr>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Consommation</th>
                        <th>Date relevé</th>
                        <th>Date présentation</th>
                        <th>Date limite paiement</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date paiement</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($query_run && mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            $consommation = ($row['conso_elec'] !== null) ? $row['conso_elec'] . ' kWh' : (($row['conso_eau'] !== null) ? $row['conso_eau'] . ' m³' : '-');
                            $date_releve = $row['date_releve_elec'] ?? $row['date_releve_eau'] ?? '-';
                            $date_pres = $row['date_pres_elec'] ?? $row['date_pres_eau'] ?? '-';
                            $date_limite = $row['date_limite_elec'] ?? $row['date_limite_eau'] ?? '-';
                            $montant = $row['montant'] ? $row['montant'] . ' Ar' : '-';
                            $date_paie = $row['datepaie'] ?? '-';

                            if ($row['datepaie']) {
                                $statut = '<span class="status-badge bg-success">Payé</span>';
                            } elseif ($date_limite !== '-' && strtotime($date_limite) < time()) {
                                $statut = '<span class="status-badge bg-danger">En retard</span>';
                            } else {
                                $statut = '<span class="status-badge bg-warning">En attente</span>';
                            }
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nom']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo $consommation; ?></td>
                                <td><?php echo $date_releve; ?></td>
                                <td><?php echo $date_pres; ?></td>
                                <td><?php echo $date_limite; ?></td>
                                <td><?php echo $montant; ?></td>
                                <td><?php echo $statut; ?></td>
                                <td><?php echo $date_paie; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center text-danger'><strong>Aucun relevé trouvé dans la base de données.</strong></td></tr>";
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

<!-- Bootstrap JS -->
<script src="js/bootstrap.bundle.min.js"></script>

    <script src="./chart.js-4.4.8/package/dist/chart.umd.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('paiementChart').getContext('2d');

        var paiementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($mois_labels); ?>,
                datasets: [{
                    label: 'Montant payé (Ar)',
                    data: <?php echo json_encode($montants); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.4)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Montant payé (Ar)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Mois'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.raw || 0;
                                return `${label}: ${value} Ar`;
                            }
                        }
                    }
                }
            }
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