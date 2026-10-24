<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location:login.php");
    exit();
}
require_once('config.php');

$stmt = $pdo->prepare('SELECT reservations.id AS reservation_id,reservations.status,reservations.created_at,time_slots.court_name,time_slots.date,time_slots.start_time,time_slots.end_time FROM reservations INNER JOIN time_slots ON reservations.slot_id = time_slots.id WHERE reservations.user_id = ? ORDER BY reservations.id DESC');
$stmt->execute([ $_SESSION['id']]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Court Reservation</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="index.php">Available Slots</a>
                <a class="nav-link active" href="user_reservations.php">My Reservations</a>
            </div>
            <div class="d-flex align-items-center text-white">
                <span class="me-3">Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?></span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">My Booked Slots</h2>

        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <table class="table table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Court Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Booked At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($result)): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["reservation_id"]) ?></td>
                                <td><?= htmlspecialchars($row["court_name"]) ?></td>
                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                <td><?= htmlspecialchars($row["start_time"]) ?> - <?= htmlspecialchars($row["end_time"]) ?></td>
                                <td>
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php elseif ($row['status'] === 'confirmed'): ?>
                                        <span class="badge bg-success">Confirmed</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Cancelled</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row["created_at"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">You have no reservations yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>




