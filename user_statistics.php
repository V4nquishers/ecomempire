<?php
include 'db_connect.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin

$query = "SELECT COUNT(*) AS total_users FROM customers";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to retrieve data: ' . $e->getMessage()]);
}
?>