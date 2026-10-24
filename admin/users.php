<?php
session_start();
require_once('../config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT id, name, email, role, status from users order by id asc");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

   <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="reservations.php">Reservations</a>
                <a class="nav-link " href="slots.php">Time Slots</a>
                <a class="nav-link active " href="users.php">Users</a>
            </div>
            <div class="d-flex align-items-center text-white">
                <span class="badge bg-danger me-3">ADMIN</span>
                <span class="me-3">Welcome, Admin <?= $_SESSION["name"] ?></span>
                <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 fw-bold">Users Management</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['id'] ?></td>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <span class="badge <?= $user['role'] === 'admin' ? 'bg-danger' : 'bg-secondary' ?>">
                                                <?= strtoupper($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $user['status'] === 'active' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['id'] == $_SESSION['id']): ?>
                                                <span class="text-muted small">Current User</span>
                                            <?php else: ?>
                                                <a href="toggle_user.php?id=<?= $user['id'] ?>" 
                                                   class="btn btn-sm <?= $user['status'] === 'active' ? 'btn-outline-danger' : 'btn-outline-success' ?>"
                                                   onclick="return confirm('Are you sure you want to change this user status?');">
                                                    <?= $user['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>