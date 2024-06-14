<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" type="image/x-icon" href="image/fav.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            background-image: url('image/background.png');
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .logo {
            width: 300px;
            height: auto;
            margin-bottom: 20px;
        }

        .signup-container {
            background-color: rgba(255, 255, 255, 0.3);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 80%;
            max-width: 800px;
        }

        form {
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: navy;
            text-align: left;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
        }

        button:hover {
            opacity: 0.8;
        }

        .signup-link {
            display: block;
            margin-top: 20px;
            color: navy;
            text-decoration: none;
        }

        .signup-link:hover {
            text-decoration: underline;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .half-width {
            width: 48%;
        }

        .notification {
            display: none;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeOut 3s forwards;
        }

        .notification.error {
            background-color: #f44336;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }
    </style>
</head>
<body>
    <div>
        <img class="logo" src="image/Logo.png" alt="Logo">
    </div>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post" onsubmit="return validateForm()">
            <div class="flex-container">
                <div class="half-width">
                    <label for="first-name">First Name:</label>
                    <input type="text" id="first-name" name="first-name" placeholder="Enter your first name.." required>
                </div>
                <div class="half-width">
                    <label for="last-name">Last Name:</label>
                    <input type="text" id="last-name" name="last-name" placeholder="Enter your last name.." required>
                </div>
            </div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email.." required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password.." required>
            <button type="submit">Sign Up</button>
        </form>
        <a href="Login.php" class="signup-link">Already have an account? Login</a>
    </div>

    <div id="notification" class="notification"></div>

    <script>
        function validateForm() {
            var password = document.getElementById('password').value;
            if (password == "") {
                alert("Password must be filled out");
                return false;
            }
            return true;
        }

        function showNotification(message, isError = false) {
            var notification = document.getElementById('notification');
            notification.textContent = message;
            if (isError) {
                notification.classList.add('error');
            } else {
                notification.classList.remove('error');
            }
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>

<?php
function createUser($firstName, $lastName, $email, $password) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password_db = "";
    $dbname = "crusadersignup";

    // Create connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        echo "<script>showNotification('Connection failed: " . $conn->connect_error . "', true);</script>";
        return;
    }

    // Check if email already exists
    $email_check_query = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $email_check_query->bind_param("s", $email);
    $email_check_query->execute();
    $email_check_query->store_result();

    if ($email_check_query->num_rows > 0) {
        echo "<script>showNotification('Email already exists.', true);</script>";
        $email_check_query->close();
        $conn->close();
        return;
    }
    $email_check_query->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashed_password);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>showNotification('Account successfully created.');</script>";
    } else {
        echo "<script>showNotification('Error: " . $stmt->error . "', true);</script>";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first-name'];
    $lastName = $_POST['last-name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password)) {
        createUser($firstName, $lastName, $email, $password);
    } else {
        global $message, $isError;
        $message = "All fields are required.";
        $isError = true;
    }
}
?>