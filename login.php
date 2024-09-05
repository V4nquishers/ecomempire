<?php
//Include the database connection file
include 'db_connect.php';

$errorMessage = ""; // Initialize error message

// Process login form submission (if submitted)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']); // Sanitize username
  $password = trim($_POST['password']); // Sanitize password

  // Prepare SQL statement (illustrative)
  $sql = "SELECT * FROM customers WHERE username = :username";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':username', $username); // Bind username parameter
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC); // Adjust for your database system

  if ($result) {
    if (password_verify($password, $result['password'])) { // Use password_verify for hashed passwords
      // Successful login (session handling or redirect logic)
      session_start(); // Start session
      $_SESSION['customer_id'] = $result['customer_id']; // Store customer ID in session
      $_SESSION['username'] = $username; // Store username in session (optional)
      header("Location: index.php"); // Redirect to profile page (replace with desired page)
      exit();
    } else {
      $errorMessage = "Invalid username or password.";
    }
  } else {
    $errorMessage = "Invalid username or password.";
  }

  $stmt->closeCursor(); // Close prepared statement
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - EcomEmpire</title>
  <link rel="stylesheet" href="/css/style.css"/>
  <style>
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
  <h1 style="text-align: center;">Login</h1>
  <?php if ($errorMessage) { ?>
    <p style="color: red;"><?php echo $errorMessage; ?></p>
  <?php } ?>
  <form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br><br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br><br>
    <input type="submit" value="Login">
  </form>
  <div>
    <div style="text-align: center;">Not a user yet? <a href=register.php>Register</a> now!</div>
  </div>
</body>
</html>