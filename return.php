<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

$order_id = $_GET['order_id'] ?? null;

// Query the orders table to get the product_id and quantity
$sql = "SELECT product_id, quantity FROM ordered_products WHERE order_id = :order_id";
$stmt = $conn->prepare($sql);
$stmt->execute([':order_id' => $order_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    echo "Invalid order ID.";
    exit;
}

$product_id = $result['product_id'];
$quantity = $result['quantity'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_reason = $_POST['return_reason'];

    // Insert return reason into returns table
    $sql = "INSERT INTO returns (order_id, product_id, quantity, return_reason) VALUES (:order_id, :product_id, :quantity, :return_reason)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':order_id' => $order_id,
        ':product_id' => $product_id,
        ':quantity' => $quantity,
        ':return_reason' => $return_reason
    ]);

    // Update order status in orders table
    $sql = "UPDATE orders SET order_status = 'Pending' WHERE order_id = :order_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);

    echo "Return request submitted successfully.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Return</title>
</head>
<body>
    <h1>Request Return</h1>
    <form method="POST" action="return.php?order_id=<?php echo htmlspecialchars($order_id); ?>">
        <label for="product_id">Product ID:</label>
        <input type="number" id="product_id" name="product_id" value="<?php echo $product_id; ?>" readonly><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" readonly><br><br>
        <label for="return_reason">Return Reason:</label><br>
        <textarea id="return_reason" name="return_reason" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Submit Return Request</button>
    </form>
</body>
</html>