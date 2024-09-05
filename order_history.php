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
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  th {
    background-color: #f2f2f2;
  }
  .return-button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
  }
</style>
<script>
  function checkReturnEligibility(orderDate, orderId) {
    const currentDate = new Date();
    const orderDateObj = new Date(orderDate);
    const diffTime = Math.abs(currentDate - orderDateObj);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays > 30) {
      alert("Not eligible for return");
    } else {
      window.location.href = `return.php?order_id=${orderId}`;
    }
  }
</script>
</head>
<body>
  <h1>Order History</h1>
  <table>
    <tr>
      <th>Order ID</th>
      <th>Order Date</th>
      <th>Total Amount</th>
      <th>Shipping Address</th>
      <th>Return</th>
      <th>View</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
      <td><?php echo htmlspecialchars($order['order_id']); ?></td>
      <td><?php echo htmlspecialchars($order['order_date']); ?></td>
      <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
      <td><?php echo htmlspecialchars($order['address'] . ', ' . $order['city'] . ', ' . $order['state'] . ' ' . $order['postal_code']); ?></td>
      <td>
        <button class="return-button" onclick="checkReturnEligibility('<?php echo $order['order_date']; ?>', <?php echo $order['order_id']; ?>)">Request Return</button>
      </td>
      <td><a href="view_order.php?order_id=<?php echo $order['order_id']; ?>">View Order</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>