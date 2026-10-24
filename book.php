<?php

session_start();
require_once('config.php');

if (!isset($_SESSION["id"])) {
    header("Location:login.php");
    exit();
}

if (isset($_GET["id"])) {

    $slot_id = $_GET["id"];
    $user_id = $_SESSION["id"];

    try {

        $stmt = $pdo->prepare("insert into reservations (user_id,slot_id,status) values (?,?,?)");
        $stmt->execute([$user_id, $slot_id, "pending"]);

        header("Location:index.php?success=1");
        exit();

    } catch (PDOException $e) {

        if ($e->getCode() == '23000') {
            header("Location: index.php?error=already_booked");
        } else {
            header("Location: index.php?error=failed");
        }
        exit();
    }

} else {

    header("Location:index.php");
    exit();
}