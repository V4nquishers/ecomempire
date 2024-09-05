<?php

$host = 'velum.cinis.cf'; // Database host
$port = '3308'; // Database port
$dbname = 'ecomempire'; // Database name
$username = 'root'; // Database username
$password = '123aaron'; // Database password

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}