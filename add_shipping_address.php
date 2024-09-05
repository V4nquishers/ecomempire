<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

$message = ''; // Message to display after form submission

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate and sanitize user input
  $address = trim($_POST['address']);
  $city = trim($_POST['city']);
  $state = trim($_POST['state']);
  $country = trim($_POST['country']);
  $postal_code = (int) trim($_POST['postal_code']);
  $phone_number = (int) trim($_POST['phone_number']);

  // Prepare and execute insert statement
  $sql = "INSERT INTO shipping_addresses (address, city, state, country, postal_code, phone_number) 
          VALUES (?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  // $stmt->bind_param('ssssss', $address, $city, $state, $country, $postal_code, $phone_number);
  
  if ($stmt->execute([$address, $city, $state, $country, $postal_code, $phone_number])) {
    $message = "Your shipping address has been saved successfully!";
  } else {
    $message = "Error saving address: " . $conn->error;
  }

    // Get the inserted order ID (optional, for further processing)
    $shipping_address_id = $conn->lastInsertId();

  $sql = "INSERT INTO ships_to (customer_id, shipping_address_id) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);
  // $stmt->bind_param('ii', $_SESSION['customer_id'], $shipping_address_id);
  $stmt->execute([$_SESSION['customer_id'], $shipping_address_id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcomEmpire - Add Shipping Address</title>
<style>
    body
    {
        font-family: 'Gandhi Sans', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    form
    {
        background-color: #fff;
        max-width: 400px;
        margin: 30px auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    label
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

    input[type="submit"]
    {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #5cadb8;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover
    {
        background-color: #4c8aae;
    }
    </style>
</head>
<body>

<h1 style="text-align: center;"+>Add Shipping Address</h1>

<?php if ($message): ?>
  <div style="text-align: center;"><?php echo $message; ?></div>
<?php endif; ?>

<form action="add_shipping_address.php" method="post">
  <label for="address">Address:</label>
  <input type="text" id="address" name="address" required><br><br>

  <label for="city">City:</label>
  <input type="text" id="city" name="city" required><br><br>

  <label for="state">State:</label>
  <input type="text" id="state" name="state" required><br><br>

  <label for="country">Country:</label>
  <input type="text" id="country" name="country" required><br><br>

  <label for="postal_code">Postal Code:</label>
  <input type="text" id="postal_code" name="postal_code" required><br><br>

  <label for="phone_number">Phone Number:</label>
  <input type="tel" id="phone_number" name="phone_number" required><br><br>

  <input type="submit" value="Add address">
</form>
