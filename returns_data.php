<?php
// Connect to the database
$servername = "localhost"; // or your server name
$username = "your_username"; // your database username
$password = "your_password"; // your database password
$dbname = "ecommerce_db"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch return data
$sql = "
    SELECT 
        return_reason AS label, 
        COUNT(*) AS value 
    FROM returns 
    WHERE return_reason IN ('Damaged Items', 'Incorrect Item', 'Changed Mind')
    GROUP BY return_reason;
";

// Execute the query and fetch results
$result = $conn->query($sql);

// Initialize an empty array to store chart data
$chartData = [
    'labels' => [],
    'values' => []
];

// Check if there are results
if ($result->num_rows > 0) {
    // Fetch results and populate chart data
    while ($row = $result->fetch_assoc()) {
        $chartData['labels'][] = $row['label'];
        $chartData['values'][] = (int) $row['value'];
    }
}

// Fetch the total number of returns and calculate return percentage (dummy example)
$totalReturns = array_sum($chartData['values']);
$returnPercentage = $totalReturns > 0 ? round(($totalReturns / 1000) * 100, 2) : 0; // Example logic for percentage

// Add total returns and percentage to the response
$chartData['totalReturns'] = $totalReturns;
$chartData['returnPercentage'] = $returnPercentage;

// Return the chart data as a JSON response
header('Content-Type: application/json');
echo json_encode($chartData);

// Close the connection
$conn->close();
?>