<?php
        // Initialize error message
          $errorMessage = "";

          $message = ''; // Message to display after form submission
        
         // Include the database connection file
            include 'db_connect.php';
        
        // Process registration form submission (if submitted)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = trim($_POST['first_name']); // Sanitize first name
            $lastName = trim($_POST['last_name']);  // Sanitize last name
            $age = (int) trim($_POST['age']);        // Sanitize and convert age to integer
            $phone = (int) trim($_POST['phone']);    // Sanitize and convert phone number to integer
            $username = trim($_POST['username']);  // Sanitize username
            $password = trim($_POST['password']);  // Sanitize password

            try {
                // Prepare SQL statement (illustrative)
                $sql = "INSERT INTO customers (username, first_name, last_name, age, phone_number, password) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Hash the password before storing it in the database (assuming password hashing is implemented)
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // // Bind parameters
                // $stmt->bindParam(1, $username);
                // $stmt->bindParam(2, $firstName);
                // $stmt->bindParam(3, $lastName);
                // $stmt->bindParam(4, $age);
                // $stmt->bindParam(5, $phone);
                // $stmt->bindParam(6, $hashedPassword);

                if ($stmt->execute([$username, $firstName, $lastName, $age, $phone, $hashedPassword])) {
                    // Registration successful (redirect or confirmation message)
                    $message = 'Registration successful! Please <a href="login.php">login</a> to continue.';
                } else {
                    // Registration failed
                    $errorMessage = "Registration failed: " . $stmt->errorInfo()[2];
                    echo $errorMessage;
                }

                $stmt->closeCursor(); // Close prepared statement
            } catch (PDOException $e) {
                $errorMessage = "Registration failed: " . $e->getMessage();
                echo $errorMessage;
            }
        }
?> 
<!DOCTYPE html>
<html>
    <head>
        <title>EcomEmpire - Register</title>
        <link rel="stylesheet" href="/css/style.css">
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
        <h1 style="text-align: center;">Register</h1>
        <?php if ($message): ?>
            <div style="text-align: center;"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required><br><br>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required><br><br>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required><br><br>
            <label for="age">Age:</label>
            <input type="number" name="age" id="age" min="18" required><br><br>
            <label for="phone">Phone Number:</label>
            <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required><br><br>
            <input type="submit" value="Register">
        </form>
        <div>
            <div style="text-align: center;">Already registered? <a href=login.php>Login</a> now!</div>
        </div>
        <script>
            window.onload = function()
            {
                var form = document.querySelector('form');
                form.addEventListener('submit', function(event)
                {
                    event.preventDefault();
        
                    var username = document.getElementById('username').value;
                    var firstName = document.getElementById('first_name').value;
                    var lastName = document.getElementById('last_name').value;
                    var age = document.getElementById('age').value;
                    var phone = document.getElementById('phone').value;
                    var password = document.getElementById('password').value;
        
                    if(username.length < 4)
                    {
                        alert('Username must be at least 4 characters long.');
                        return false;
                    }
        
                    if(!/^[a-zA-Z]+$/.test(firstName))
                    {
                        alert('First name must contain only letters.');
                        return false;
                    }
        
                    if(!/^[a-zA-Z]+$/.test(lastName))
                    {
                        alert('Last name must contain only letters.');
                        return false;
                    }
        
                    if(age < 18)
                    {
                        alert('You must be at least 18 years old.');
                        return false;
                    }
        
                    var phonePattern = /^[0-9]{10}$/;
                    if(!phonePattern.test(phone))
                    {
                        alert('Phone number must be 10 digits long.');
                        return false;
                    }
        
                    if(password.length < 6)
                    {
                        alert('Password must be at least 6 characters long.');
                        return false;
                    }
                    form.submit();
                });
            };
        </script>
    </body>
</html>