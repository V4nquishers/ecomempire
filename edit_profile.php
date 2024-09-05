<?php
session_start();

include 'db_connect.php'; // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php'); // Redirect to login page if not logged in
  exit;
}

$customer_id = $_SESSION['customer_id'];

// Get user information from database
$sql = "SELECT * FROM customers WHERE customer_id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
$stmt->execute();
$customer_data = $stmt->fetch(PDO::FETCH_ASSOC);

$message = ''; // Initialize message variable

// Form handling for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate and sanitize user input
  $first_name = trim($_POST['first_name']);
  $last_name = trim($_POST['last_name']);
  $phone_number = trim($_POST['phone_number']);

  // Update user information in database (assuming these are the editable fields)
  $sql = "UPDATE customers SET first_name = :first_name, last_name = :last_name, phone_number = :phone_number WHERE customer_id = :customer_id";
  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
  $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
  $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
  $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
  if ($stmt->execute()) {
    $message = "Profile updated successfully!";
    header("Location: edit_profile.php");
  } else {
    $message = "Error updating profile: " . $stmt->errorInfo()[2];
  }

  $stmt->closeCursor();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EcomEmpire - Edit Profile</title>
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

<h1 style="text-align: center;">Edit Profile</h1>

<?php if ($message): ?>
  <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="post">
  <label for="first_name">First Name:</label>
  <input type="text" id="first_name" name="first_name" value="<?php echo isset($customer_data['first_name']) ? $customer_data['first_name'] : ''; ?>" required><br><br>

  <label for="last_name">Last Name:</label>
  <input type="text" id="last_name" name="last_name" value="<?php echo isset($customer_data['last_name']) ? $customer_data['last_name'] : ''; ?>" required><br><br>

  <label for="phone_number">Phone Number:</label>
  <input type="number" id="phone_number" name="phone_number" value="<?php echo isset($customer_data['phone_number']) ? $customer_data['phone_number'] : ''; ?>" required><br><br>

  <input type="submit" value="Update Profile">
</form>

</body>
</html>
