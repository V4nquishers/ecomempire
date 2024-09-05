<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="/css/style.css"/>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcomEmpire</title>
<script src="https://kit.fontawesome.com/9aafc13c9e.js" crossorigin="anonymous"></script>
<style>
</style>
</head>
<body>
<!-- Navigation Bar -->
<div class="navbar customer">
    <a href="index.php">Home</a>
    <a href="supplier.php">Supplier</a>
    <?php
      if (isset($_SESSION['username'])) {
        echo '<div class="dropdown">
                <button class="dropbtn">' .$_SESSION['username'] . '</button>
                  <div class="dropdown-content">
                    <a href="cart.php">Cart</a>
                    <a href="profile.php">Profile</a>
                  </div>
              </div>';
        echo '<a class="split" href="logout.php">Logout</a>';
        echo '<a class="split" href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>';
      } else {
        echo '<a class="split" href="login.php">Login</a>';
        echo '<a class="split" href="register.php">Register</a>';
      }
    ?>
</div>

<div class="product-grid">
  <?php
          // Include the database connection file
          include 'db_connect.php';
            try {
              // Query to fetch products from the database
              $sql = "SELECT * FROM products";
              $stmt = $conn->query($sql);
      
              // Display products
              if ($stmt->rowCount() > 0) {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                      echo '<div class="product">';
                      echo '<img src="' . $row['image_url'] . '" alt="' . $row['name'] . '">';
                      echo '<h3>' . $row['name'] . '</h3>';
                      echo '<p>Price: ₹' . $row['price'] . '</p>';
                      echo '<form class="hidden" action="add_to_cart.php" method="post">
                              <input type="hidden" name="product_id" value="' . $row['product_id'] . '">
                              <input type="hidden" name="quantity" value="1">
                              <button type="submit" class="add-to-cart">Add to Cart</button>
                            </form>';
                      echo '</div>';
                  }
              }
          } catch(PDOException $e) {
              echo "Connection failed: " . $e->getMessage();
          }
  ?>
</div>
<div class="footer customer">
  <p>&copy; 2024 EcomEmpire. All rights reserved.</p>
  <p>Follow us on:
    <a href="https://www.facebook.com" style="color: white;">Facebook</a>,
    <a href="https://www.twitter.com" style="color: white;">Twitter</a>,
    <a href="https://www.instagram.com" style="color: white;">Instagram</a>
  </p>
</div>
</body>
</html>