<?php

session_start();
require_once('../config.php');

if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit();
}
$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null ;

$allowed_status = array("cancelled" , "confirmed");

if ($id != null && in_array($status, $allowed_status)) {
    $stmt = $pdo->prepare("update reservations set status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    header("Location:reservations.php");
    exit();
}
