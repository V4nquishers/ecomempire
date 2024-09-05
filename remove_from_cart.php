<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

// Get product ID from URL parameter
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// Check if user already has the product in the cart
$sql = "SELECT * FROM cart WHERE customer_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['customer_id'], $product_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Remove product from cart if quantity = 1
if ($row['quantity'] === 1) {
    $sql = "DELETE FROM cart WHERE product_id = ? AND customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_id, $_SESSION['customer_id']]);
} else {
  // Decrement quantity if quantity > 1
  $sql = "UPDATE cart SET quantity = quantity - 1 WHERE customer_id = ? AND product_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$_SESSION['customer_id'], $product_id]);
}

// $sql = "SELECT * FROM products WHERE product_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param('i', $product_id);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows === 0) {
//   echo "Product not found.";
//   exit;
// }

// $product = $result->fetch_assoc();

// $sql = "UPDATE orders SET total_amount = total_amount - ? WHERE order_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param('di', $product['price'], $_SESSION['customer_id']);
// $stmt->execute();

// //Remove order if no products in cart
// $sql = "SELECT * FROM orders WHERE order_id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param('i', $_SESSION['customer_id']);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();

// if ($row['total_amount'] == 0.00) {
//     $sql = "DELETE FROM orders WHERE order_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $_SESSION['customer_id']);
//     $stmt->execute();
//   }

// Redirect back to shopping cart page
header('Location: cart.php');

$conn->close();
?>