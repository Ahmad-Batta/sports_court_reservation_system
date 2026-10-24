
<?php

require_once 'config.php';
$error = "";
$success = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $name = trim($_POST["name"]);

    if($email == null || $password == null || $name == null){
        $error = "All fields are required !";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "enter a validate email !";
    }else{
        $stmt = $pdo->prepare("select * from users where email = ?");
        $stmt->execute([$email]);
        if($stmt->rowCount() > 0){
            $error = "email already registered !";
    }else{
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $inser_stmt = $pdo->prepare("insert into users (name,email,password) values (?,?,?)");
        $inser_stmt->execute([$name,$email,$hashed_password]);
        $success = "account created successfully !";
    }
}
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sports Court Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="card-title text-center mb-4">Create an Account</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="name@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                    </form>

                    <div class="text-center mt-3">
                        <small class="text-muted">Already have an account? <a href="login.php">Login here</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>