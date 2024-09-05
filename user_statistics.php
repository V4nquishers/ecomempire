<?php
include 'db_connect.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin

$query = $query = "SELECT 
             COUNT(*) AS total_users,
             SUM(CASE WHEN loyalty_level = 'Gold' THEN 1 ELSE 0 END) AS gold_members,
             SUM(CASE WHEN loyalty_level = 'Silver' THEN 1 ELSE 0 END) AS silver_members,
             SUM(CASE WHEN loyalty_level = 'Bronze' THEN 1 ELSE 0 END) AS bronze_members
            FROM customers";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to retrieve data: ' . $e->getMessage()]);
}
?>