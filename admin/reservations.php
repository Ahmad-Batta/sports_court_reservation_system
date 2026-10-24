<?php

session_start();

require_once('../config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header('Location:../index.php');
    exit();
}

$stmt = $pdo->prepare('SELECT reservations.id as reservation_id, reservations.status, reservations.created_at, users.email, users.name,time_slots.court_name, time_slots.date, time_slots.start_time, time_slots.end_time from reservations inner join users on reservations.user_id = users.id inner join time_slots on reservations.slot_id = time_slots.id ORDER BY reservations.id DESC');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">


        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link active" href="reservations.php">Reservations</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Reservations</h2>
        </div>

        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Court</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Booked At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($result)): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['reservation_id']) ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                </td>
                                <td><?= htmlspecialchars($row['court_name']) ?></td>
                                <td>
                                    <?= htmlspecialchars($row['date']) ?><br>
                                    <small class="text-muted"><?= htmlspecialchars($row['start_time']) ?> - <?= htmlspecialchars($row['end_time']) ?></small>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($row['status'] === 'confirmed'): ?>
                                        <span class="badge bg-success">Confirmed</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['created_at']) ?></td>
                                <td class="text-center">
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <a href="update_status.php?id=<?= $row['reservation_id'] ?>&status=confirmed" 
                                           class="btn btn-sm btn-success me-1">Confirm</a>
                                        <a href="update_status.php?id=<?= $row['reservation_id'] ?>&status=cancelled" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to reject this booking?')">Reject</a>
                                    <?php else: ?>
                                        <span class="text-muted small">No action needed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No reservations found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>