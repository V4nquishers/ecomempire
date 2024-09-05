<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

include 'db_connect.php'; // Include the database connection file

$customer_id = $_SESSION['customer_id'];

// Get user information from database
$sql = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$customer_id]);
$customer_data = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Profile</title>
<style>
    body
    {
        font-family: 'Gandhi Sans', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .content
    {
        background-color: #fff;
        max-width: 400px;
        margin: 30px auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .label
    {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    input[type="tel"],
    input[type="password"]
    {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box; /* Added to keep input width consistent */
    }

    a
    {
        text-decoration: none;
        text-align: center;
        margin: 10px 20px;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #5cadb8;
        color: white;
        cursor: pointer;
        display: block;
    }

    a:hover
    {
        background-color: #4c8aae;
    }
    </style>
</head>
<body>
<h1 style="text-align: center;">Your Profile</h1>
<div class=content>
    <?php if ($customer_data): ?>
        <div class="label">Username: <?php echo $customer_data['username']; ?></div>
        <div class="label">Name: <?php echo $customer_data['first_name']; ?> <?php echo $customer_data['last_name']; ?></div>
        <div class="label">Age: <?php echo $customer_data['age']; ?></div>
        <div class="label">Phone Number: <?php echo $customer_data['phone_number']; ?></div>
        <a href="edit_profile.php">Edit Profile</a>
        <a href="add_shipping_address.php">Add new shipping address</a>
        <a href="order_history.php">Order History</a>
    <?php else: ?>
    <p>Error: User data not found.</p>
    <?php endif; ?>
</div>
</body>
</html>