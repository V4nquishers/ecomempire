<?php
  session_start();
      // Check if user is logged in
      if (!isset($_SESSION['supplier_id'])) {
        header('Location: s_login.php'); // Redirect to login page if not logged in
        exit;
      }

      // Error/Success messages (initialize as empty)
      $errorMsg = "";
      $successMsg = "";

      include 'db_connect.php'; // Include the database connection file      

      // Check if form is submitted and user is logged in
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {// Connect to the database (replace with your credentials)
      
        // Validate and sanitize inputs
        $name = trim($_POST['name']);
        $price = trim($_POST['price']);
        $stock = trim($_POST['stock']);

        $uploadOk = 0;
        $imagePath = "";

        // Image upload logic (replace with your validation and upload process)
        $target_dir = "/sites/ecomempire/assets/"; // Change to your upload directory with proper security measures

        // if (isset($_FILES['image'])) {
          $target_file = $target_dir . basename($_FILES["uploadimg"]["name"]);
          $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

          // Check if image file is a real image (replace with additional validations)
          $check = getimagesize($_FILES["uploadimg"]["tmp_name"]);
          if($check !== false) {
            // $errorMsg = "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
          } else {
            $errorMsg = "File is not an image.";
            $uploadOk = 0;
          }

          // Check if file already exists
          if (file_exists($target_file)) {
            $errorMsg = "Sorry, file already exists.";
            $uploadOk = 0;
          }
        // }
        if ($uploadOk == 1) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["uploadimg"]["tmp_name"], $target_file)) {
              // $imagePath = $target_file; // Update image path on successful upload
              $imagePath = 'assets/' . $_FILES["uploadimg"]["name"];
            } else {
              $errorMsg = "Sorry, there was an error uploading your file.";
            }
  
            // Prepare and execute INSERT query (using prepared statements)
            if ($imagePath != "") {
              $sql = "INSERT INTO products (name, price, stock, image_url) VALUES (:name, :price, :stock, :imagePath)";
              $stmt = $conn->prepare($sql);
  
              // $stmt->bind_param("ssss", $name, $price, $stock, $imagePath);
              $stmt->bindValue(':name', $name, PDO::PARAM_STR);
              $stmt->bindValue(':price', $price, PDO::PARAM_STR);
              $stmt->bindValue(':stock', $stock, PDO::PARAM_STR);
              $stmt->bindValue(':imagePath', $imagePath, PDO::PARAM_STR);
              $stmt->execute();

              if ($stmt->rowCount() == 1) {
                $successMsg = "Product added successfully!";
              } else {
                $errorMsg = "Error adding product to database.";
              }

              $product_id = $conn->lastInsertId();

              $sql = "INSERT INTO supplies (supplier_id, product_id) VALUES (:supplier_id, :product_id)";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param(':supplier_id', $_SESSION['supplier_id'], PDO::PARAM_INT);
              $stmt->bind_param(':product_id', $product_id, PDO::PARAM_INT);
              $stmt->execute();
            }
          }
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>EcomEmpire - Add Product</title>
  <style>
    /* Basic form styling */
    body {
      font-family: 'Gandhi Sans', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    .container {
      background-color: #fff;
      max-width: 400px;
      margin: 30px auto;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="text"],
    input[type="number"],
    input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box; /* Added to keep input width consistent */
    }
    .error {
      color: red;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .success {
      color: green;
      font-weight: bold;
      margin-bottom: 10px;
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
  <h1 style="text-align: center;">Add Product</h2>
  <div class="container">
    <?php if ($successMsg): ?>
      <div style="text-align: center;"><?php echo $successMsg; ?></div>
    <?php endif; ?>
    <?php if ($errorMsg): ?>
      <div style="text-align: center;"><?php echo $errorMsg; ?></div>
    <?php endif; ?>
    <form action="add_product.php" method="post" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="price">Price (₹):</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" required>
            <label for="uploadimg">Image:</label>
            <input type="file" id="uploadimg" name="uploadimg" accept="image/*" required>
            <input type="submit" value="Add Product">
    </form>
    <script>
      const uploadField = document.getElementById("uploadimg");
      uploadField.onchange = function() {
        if(this.files[0].size > 1048576) {
        alert("File is too big!");
        this.value = "";
        }
      };
    </script>
  </div>
</body>
</html>