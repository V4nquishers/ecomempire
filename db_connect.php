<!-- <?php

$host = 'rdbms-mysql'; // Replace with your database host
$dbname = 'ecomempire'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = '123aaron'; // Replace with your database password

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
} -->
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