<?php

session_start();
require_once('../config.php');

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
$id = $_GET['id'] ?? null;


if ($id != null) {
    $stmt = $pdo->prepare("DELETE from time_slots WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location:slots.php");
exit();