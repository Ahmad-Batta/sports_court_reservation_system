<?php

session_start();

require_once 'config.php';
$errors = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email == null || $password == null) {
        $errors = "all fields are required !";
    } else {
        $stmt = $pdo->prepare("select * from users where email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user == null || !password_verify($password, $user["password"])) {
            $errors = "invalid email or password";
        } else {
            if ($user["status"] !== "active") {
                $errors = "your account is deactivated by admin !";
            } else {
                $_SESSION["id"] = $user["id"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];
                $_SESSION["name"] = $user["name"];
                

                if ($user["role"] === "admin") {
                    header("Location:admin/dashboard.php");
                } else {
                    header("Location:index.php");
                }
                exit();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sports Court Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Login to Your Account</h3>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($errors) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">Don't have an account? <a href="register.php">Register here</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>