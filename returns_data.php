<?php
// Include the database connection file
include 'db_connect.php';

// SQL query to fetch return data
$sql = "
    SELECT 
        return_reason AS label, 
        COUNT(*) AS value 
    FROM returns 
    WHERE return_reason IN ('Damaged Items', 'Incorrect Item', 'Changed Mind')
    GROUP BY return_reason;
";

// Prepare the query
$stmt = $conn->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch the results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize an empty array to store chart data
$chartData = [
    'labels' => [],
    'values' => []
];

// Populate chart data
foreach ($results as $row) {
    $chartData['labels'][] = $row['label'];
    $chartData['values'][] = (int) $row['value'];
}

// Fetch the total number of returns and calculate return percentage (dummy example)
$totalReturns = array_sum($chartData['values']);
$returnPercentage = $totalReturns > 0 ? round(($totalReturns / 1000) * 100, 2) : 0; // Example logic for percentage

// Add total returns and percentage to the response
$chartData['totalReturns'] = $totalReturns;
$chartData['returnPercentage'] = $returnPercentage;

// Return the chart data as a JSON response
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
echo json_encode($chartData);
?>