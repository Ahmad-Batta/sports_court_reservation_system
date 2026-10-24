<?php

session_start();
require_once('../config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] != "admin") {
    header("Location:../login.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $court_name = trim($_POST["court_name"]);
    $date = $_POST["date"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    if (empty($court_name) || empty($date) || empty($start_time) || empty($end_time)) {
        $error = "all fields are required !";
    } else {
        $stmt = $pdo->prepare("INSERT into time_slots (court_name , date , start_time , end_time) values (?,?,?,?)");
        $stmt->execute([$court_name, $date, $start_time, $end_time]);
        $success = "new time slot inserted correctly !";
    }
}

$query = $pdo->prepare("SELECT * from time_slots order by date desc, start_time desc");
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Time Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="reservations.php">Reservations</a>
                <a class="nav-link active" href="slots.php">Time Slots</a>
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

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo (htmlspecialchars($error)) ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo (htmlspecialchars($success)) ?></div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Add New Slot</h5>
                    </div>
                    <div class="card-body">
                        <form action="slots.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Court Name</label>
                                <input type="text" name="court_name" class="form-control" placeholder="e.g. Court A"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Slot</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Existing Slots</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Court</th>
                                        <th>Date</th>
                                        <th>Timing</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($result)): ?>
                                        <?php foreach ($result as $row): ?>
                                            <tr>
                                                <td><?= $row['id'] ?></td>
                                                <td><?= htmlspecialchars($row['court_name']) ?></td>
                                                <td><?= $row['date'] ?></td>
                                                <td><?= htmlspecialchars($row['start_time'] . ' - ' . $row['end_time']) ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-danger" href="delete_slot.php?id=<?= $row['id'] ?>"
                                                        onclick="return confirm('Are you sure you want to delete this slot?');">
                                                        Delete
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No slots found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>