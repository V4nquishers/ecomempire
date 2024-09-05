<?php
include 'db_connect.php'; // Include database connection file

$errorMessage = ""; // Initialize error message

// Process login form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']); // Sanitize username
  $password = trim($_POST['password']); // Sanitize password

  // Prepare SQL statement (illustrative)
  $sql = "SELECT * FROM suppliers WHERE username = ?";
  $stmt = $conn->prepare($sql);
  $stmt->execute([$username]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row){
    if (password_verify($password, $row['password'])) { // Use password_verify for hashed passwords
      // Successful login (session handling or redirect logic)
      session_start(); // Start session
      $_SESSION['supplier_id'] = $row['supplier_id']; // Store supplier ID in session
      $_SESSION['s_username'] = $username; // Store username in session
      header("Location: supplier.php"); // Redirect to supplier page
      exit();
    } else {
      $errorMessage = "Invalid username or password.";
    }
  } else {
    $errorMessage = "User not found";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - EcomEmpire</title>
  <style>
    body
    {
        font-family: 'Gandhi Sans', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    form
    {
        background-color: #fff;
        max-width: 400px;
        margin: 30px auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    label
    {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    input[type="tel"],
    input[type="password"]
    {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        box-sizing: border-box; /* Added to keep input width consistent */
    }

    input[type="submit"]
    {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #5cadb8;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover
    {
        background-color: #4c8aae;
    }
    </style>
</head>
<body>
  <h1 style="text-align: center;">Supplier Login</h1>
  <?php if ($errorMessage) { ?>
    <p style="color: red;"><?php echo $errorMessage; ?></p>
  <?php } ?>
  <form action="s_login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br><br>
    <input type="submit" value="Login">
  </form>
  <div>
    <div style="text-align: center;">Not a user yet? <a href=s_register.php>Register</a> now!</div>
  </div>
</body>
</html>