<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/style.css"/>
<title>EcomEmpire</title>
<style>
</style>
</head>
<body>
<!-- Navigation Bar -->
<div class="supplier navbar">
    <a href="supplier.php">Home</a>
    <a href="index.php">Customer</a>
    <?php
      if (isset($_SESSION['s_username'])) { // Check if a user is logged in
        echo '<a href="add_product.php">Add Product</a>';
        echo '<a href="s_profile.php">' . $_SESSION['s_username'] . '</a>'; // Include a logout link
        echo '<a class="split" href="logout.php">Logout</a>'; // Include a logout link
      } else {
        echo '<a class="split" href="s_login.php">Supplier Login</a>';
        echo '<a class="split" href="s_register.php">Supplier Register</a>'; // Include a register link
      }
    ?>
</div>
<h1 style="text-align: center;">Your supplied products</h1>
<div class="product-grid">
  <?php
            include 'db_connect.php'; // Include the database connection file
            if (!isset($_SESSION['supplier_id'])) {
              echo "Please login to view your products.";
            }
            else
            {
              // Get ordered products
              $sql = "SELECT p.product_id, p.name, p.price, p.image_url, p.stock
              FROM supplies s
              INNER JOIN products p ON s.product_id = p.product_id
              WHERE s.supplier_id = ?";
              $stmt = $conn->prepare($sql);
              $stmt->execute([$_SESSION['supplier_id']]);
              $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

              // Display products
              if (count($result) > 0) {
                foreach ($result as $row) {
                  echo '<div class="product">';
                  echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">';
                  echo '<h3>' . $row['name'] . '</h3>';
                  echo '<p>Price: ₹' . $row['price'] . '</p>';
                  echo '<p>Stock: ' . $row['stock'] . '</p>';
                  // echo '<form action="add_stock.php" method="post">
                  //         <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                  //         <input type="hidden" name="quantity" value="1">
                  //         <button type="submit" class="add-to-cart">Add stock</button>
                  //       </form>';
                  echo '</div>';
                }
              } else {
                echo "No products found.";
              }
            }
  ?>
</div>
<div class="supplier footer">
  <p>&copy; 2024 EcomEmpire. All rights reserved.</p>
  <p>Follow us on:
    <a href="https://www.facebook.com" style="color: white;">Facebook</a>,
    <a href="https://www.twitter.com" style="color: white;">Twitter</a>,
    <a href="https://www.instagram.com" style="color: white;">Instagram</a>
  </p>
</div>
</body>
</html>
