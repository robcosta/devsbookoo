<?php
session_start();

$base = 'http://localhost/devsbookoo';

$db_name = 'devsbook';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:dbname=$db_name;host=$db_host", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo "ERRO DE CONEXÃO: " + $e;
}
