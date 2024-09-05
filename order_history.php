<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

// Get user's order history
$sql = "SELECT o.order_id, o.order_date, o.total_amount, s.address, s.city, s.state, s.postal_code
        FROM orders o
        INNER JOIN shipping_addresses s ON o.shipping_address_id = s.shipping_address_id
        WHERE o.customer_id = ?
        ORDER BY o.order_date DESC"; // Order by latest first
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['customer_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/style.css">
<title>EcomEmpire - Order History</title>
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

<h1>Your Order History</h1>

<?php if (empty($orders)): ?>
  <p>You haven't placed any orders yet.</p>
<?php else: ?>

<table>
  <tr>
    <th>Order ID</th>
    <th>Date Ordered</th>
    <th>Total Amount</th>
    <th>Shipping Address</th>
    <th>View Order</th>
  </tr>
  <?php foreach ($orders as $order): ?>
  <tr>
    <td><?php echo $order['order_id']; ?></td>
    <td><?php echo date('Y-m-d', strtotime($order['order_date'])); ?></td>
    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
    <td><?php echo $order['address'] . " " . $order['city'] . " " . $order['postal_code'] . " " . $order['state']; ?></td>
    <td><a href="view_order.php?order_id=<?php echo $order['order_id']; ?>">View</a></td>
  </tr>
  <?php endforeach; ?>
</table>

<?php endif; ?>

</body>
</html>
