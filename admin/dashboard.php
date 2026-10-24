<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$total_res = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$pending_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'pending'")->fetchColumn();
$confirmed_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'confirmed'")->fetchColumn();
$rejected_res = $pdo->query("SELECT COUNT(*) FROM reservations WHERE status = 'rejected'")->fetchColumn();
$total_slots = $pdo->query("SELECT COUNT(*) FROM time_slots")->fetchColumn();

$courts_data = $pdo->query("
    SELECT ts.court_name, COUNT(r.id) as total_bookings 
    FROM time_slots ts 
    LEFT JOIN reservations r ON ts.id = r.slot_id 
    GROUP BY ts.court_name
")->fetchAll(PDO::FETCH_ASSOC);

$court_names = [];
$court_counts = [];
foreach ($courts_data as $row) {
    $court_names[] = $row['court_name'];
    $court_counts[] = (int)$row['total_bookings'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link active" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="reservations.php">Reservations</a>
                <a class="nav-link " href="slots.php">Time Slots</a>
                <a class="nav-link " href="users.php">Users</a>
            </div>
            <div class="d-flex align-items-center text-white">
                <span class="badge bg-danger me-3">ADMIN</span>
                <span class="me-3">Welcome, Admin <?= $_SESSION["name"] ?></span>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Total Reservations</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $total_res ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Pending</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $pending_res ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Confirmed</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $confirmed_res ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm border-0">
                    <div class="card-body">
                        <h6 class="card-title text-uppercase">Time Slots</h6>
                        <h2 class="display-6 fw-bold mb-0"><?= $total_slots ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 g-4">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Reservation Status Breakdown</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height: 320px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">Bookings per Court</h6>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="position: relative; height: 320px;">
                        <canvas id="courtChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <h5>Manage Reservations</h5>
                        <p class="text-muted">Review, accept, or reject client reservations.</p>
                        <a href="reservations.php" class="btn btn-primary">Go to Reservations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-4">
                        <h5>Manage Time Slots</h5>
                        <p class="text-muted">Create new slots or remove existing timings.</p>
                        <a href="slots.php" class="btn btn-secondary">Go to Slots</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Rejected'],
                datasets: [{
                    data: [<?= $pending_res ?>, <?= $confirmed_res ?>, <?= $rejected_res ?>],
                    backgroundColor: ['#ffc107', '#198754', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        const courtCtx = document.getElementById('courtChart').getContext('2d');
        new Chart(courtCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($court_names) ?>,
                datasets: [{
                    label: 'Total Bookings',
                    data: <?= json_encode($court_counts) ?>,
                    backgroundColor: '#0d6efd'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>
</html>