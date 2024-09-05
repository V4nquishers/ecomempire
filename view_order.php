<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

// Get order ID from URL parameter
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Get ordered products
$sql = "SELECT p.name, p.price, p.image_url, op.quantity
        FROM ordered_products op
        INNER JOIN products p ON op.product_id = p.product_id
        WHERE op.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$order_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
<title>EcomEmpire - View Order</title>
<style>
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

<?php echo '<h1>Order details for Order #' . $order_id . ':</h1>'; ?>

<table>
    <tr>
      <th>Product Image</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach ($products as $product): ?>
    <tr>
      <td><img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" style="width:100px;"></td>
      <td><?php echo $product['name']; ?></td>
      <td><?php echo $product['quantity']; ?></td>
      <td>₹<?php echo number_format($product['price'], 2); ?></td>
      <td>₹<?php echo number_format($product['price'] * $product['quantity'], 2); ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

</body>
</html>