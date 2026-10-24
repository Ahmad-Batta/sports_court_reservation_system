<?php

session_start();

require_once('../config.php');

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../index.php');
    exit();
}

$id = $_GET['id'] ?? null;

if (!empty($id)) {
    $stmt = $pdo->prepare('SELECT status FROM users WHERE id = ?');
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($result['status'] == 'active') { 
        $status = 'deactivated';
    } else {
            $status = 'active';
    }

    $query = $pdo->prepare('UPDATE users SET status = ? WHERE id = ?');
    $query->execute([$status, $id]);
    
}

header('Location: users.php');
exit();