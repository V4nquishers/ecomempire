<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php');
  exit;
}

// Get product details from request
$product_id = (int) $_POST['product_id'];
$quantity = (int) $_POST['quantity'];

// Validate product ID and quantity
if ($product_id <= 0 || $quantity <= 0) {
  echo "Invalid product ID or quantity.";
  exit;
}

// Check if product exists
$sql = "SELECT * FROM products WHERE product_id = :product_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (count($product) === 0) {
  echo "Product not found.";
  exit;
}

// Check if user already has the product in the cart
$sql = "SELECT * FROM cart WHERE customer_id = :customer_id AND product_id = :product_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add product to cart if not already present
if (count($result) === 0) {
  $sql = "INSERT INTO cart (customer_id, product_id, quantity, price) VALUES (:customer_id, :product_id, :quantity, :price)";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
  $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
  $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
  $stmt->bindParam(':price', $product['price'], PDO::PARAM_STR);
  $stmt->execute();
} else {
  // Update quantity if product already exists in cart
  $sql = "UPDATE cart SET quantity = quantity + :quantity WHERE customer_id = :customer_id AND product_id = :product_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
  $stmt->bindParam(':customer_id', $_SESSION['customer_id'], PDO::PARAM_INT);
  $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
  $stmt->execute();
}

header('Location: cart.php');

$conn->close();
?>
