<?php
$config = require 'config.php';

$host = $config["DB_HOST"];
$dbname = $config["DB_NAME"];
$user = $config["DB_USER"];

$pass = $config["DB_PASS"];


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $stmt = $pdo->query("SELECT 1");

} catch (PDOException $e)
{
    die("Ошибка подключния: " . $e->getMessage());
}

return $pdo;