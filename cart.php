<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php');  // Redirect to login page if not logged in
  exit;
}

// Get cart items for the user
$sql = "SELECT c.product_id, p.name, p.price, p.image_url, c.quantity 
  FROM cart c 
  INNER JOIN products p ON c.product_id = p.product_id
  WHERE c.customer_id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate cart total
$cart_total = 0;
foreach ($cart_items as $item) {
  $cart_total += $item['price'] * $item['quantity'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
<title>EcomEmpire - Shopping Cart</title>
<style>

h1 {
  text-align: center;
  padding: 20px;
  font-size: 2em;
}

table {
  width: 90%;
  border-collapse: collapse;
  margin: 20px auto 30px auto;
  border-radius: 10px;
}

th, td {
  padding: 10px;
  border: 1px solid #ddd;
  text-align: left;
}

th {
  background-color: #f2f2f2;
  font-weight: bold;
}

td img {
  width: 100px;
}

.subtotal, .total {
  text-align: right;
}

a {
  text-decoration: none;
  color: #333;
  padding: 10px 20px;
  background-color: #ddd;
  border-radius: 5px;
  display: inline-block;
  margin: 10px auto;
}

a:hover {
  background-color: #ccc;
}
</style>
</head>
<body>

<h1>Your Shopping Cart</h1>

<?php if (empty($cart_items)): ?>
  <div style="text-align: center;">Your cart is currently empty.</div>
<?php else: ?>

<table>
  <tr>
    <th>Product Image</th>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Subtotal</th>
    <th>Remove</th>
  </tr>
  <?php foreach ($cart_items as $item): ?>
  <tr>
    <td><img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" style="width:100px;"></td>
    <td><?php echo $item['name']; ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td>₹<?php echo number_format($item['price'], 2); ?></td>
    <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
    <td><a style="text-align: center;" href="remove_from_cart.php?product_id=<?php echo $item['product_id']; ?>">Remove</a></td>
  </tr>
  <?php endforeach; ?>
  <tr>
    <td colspan="4">Total:</td>
    <td colspan="2">₹<?php echo number_format($cart_total, 2); ?></td>
  </tr>
</table>
<div style="text-align: center;"> 
  <a href="choose_shipping_address.php">Proceed to Checkout</a>
</div>
<?php endif; ?>

</body>
</html>