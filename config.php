<?php

define("DB_USER","root");
define("DB_PASSWORD","");

$dsn = "mysql:host=localhost;dbname=football_reservation";

try{
    $pdo = new PDO($dsn,DB_USER,DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e){
    die("". $e->getMessage());  
}

