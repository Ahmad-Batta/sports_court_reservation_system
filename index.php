<?php

session_start();

if (!isset($_SESSION["id"])) {
    header("Location:login.php");
    exit();
}

require_once("config.php");

$stmt = $pdo->prepare("select * from time_slots order by id desc");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Court Reservation</title>
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
        <h2 class="mb-4">Available Time Slots</h2>

        <div class="table-responsive bg-white p-3 shadow-sm rounded">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Reservation placed successfully! Your request is pending confirmation.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <table class="table table-hover align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th>#</th>
                        <th>Court Name</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Capacity</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($result)): ?>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id"]) ?></td>
                                <td><?= htmlspecialchars($row["court_name"]) ?></td>
                                <td><?= htmlspecialchars($row["date"]) ?></td>
                                <td><?= htmlspecialchars($row["start_time"]) ?></td>
                                <td><?= htmlspecialchars($row["end_time"]) ?></td>
                                <td><?= htmlspecialchars($row["capacity"]) ?></td>
                                <td>
                                    <a href="book.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Book Now</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No available slots found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>