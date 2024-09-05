<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

// Get user's shipping addresses
$sql = "SELECT s.shipping_address_id, a.address, a.city, a.state, a.country
        FROM ships_to s
        INNER JOIN shipping_addresses a ON s.shipping_address_id = a.shipping_address_id
        WHERE s.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['customer_id']]);
$shipping_addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selected_address_id = ''; // Initialize selected address ID
if (isset($_SESSION['selected_address_id'])) {
  $selected_address_id = $_SESSION['selected_address_id'];
}

// Handle form submission (if user selects an address)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selected_address_id = filter_var($_POST['shipping_address'], FILTER_SANITIZE_NUMBER_INT);
  $_SESSION['selected_address_id'] = $selected_address_id; // Store in session
  header("Location: checkout.php");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
<title>EcomEmpire - Checkout</title>
<style>
  /* Add your styling here */
</style>
</head>
<body>

<h1>Choose Shipping Address</h1>

<?php if (empty($shipping_addresses)): ?>
  <div>You don't have any saved shipping addresses. Please add one in your profile.</div>
<?php else: ?>

<form method="post">
  <p>Select your preferred shipping address:</p>
  <?php foreach ($shipping_addresses as $address): ?>
    <label for="address_<?php echo $address['shipping_address_id']; ?>">
      <input type="radio" name="shipping_address" id="address_<?php echo $address['shipping_address_id']; ?>" value="<?php echo $address['shipping_address_id']; ?>" 
        <?php if ($selected_address_id === $address['shipping_address_id']): ?>checked<?php endif; ?>>
      <?php echo $address['address'] . ', ' . $address['city'] . ', ' . $address['state'] . ', ' . $address['country'];  ?>
    </label><br>
  <?php endforeach; ?>
  <br>
  <input type="submit" value="Continue to Checkout">
</form>

<?php endif; ?>

</body>
</html>
