<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crusadersignup";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $message = "Please fill in all fields.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } else {
            // Check if the email exists in the database
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    // Email exists, update the password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET password = ? WHERE email = ?";
                    $stmt = $conn->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("ss", $hashed_password, $email);
                        if ($stmt->execute()) {
                            $success = true;
                            $message = '<script>alert("Password reset successful.");</script>';
                        } else {
                            $message = '<script>alert("Error updating password.");</script>';
                        }
                    } else {
                        $message = '<script>alert("Failed to prepare statement for updating password.");</script>';
                    }
                } else {
                    $message = '<script>alert("No account found with that email.");</script>';
                }
            } else {
                $message = '<script>alert("Failed to prepare statement for email check.");</script>';
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

        .reset-password-container {
            background-color: rgba(255, 255, 255, 0.3);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 80%;
            max-width: 500px;
            transition: height 0.5s ease-in-out;
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
        input[type="password"] {
            width: calc(100% - 24px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #007bff;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
            width: calc(100% - 24px);
        }

        button:hover {
            opacity: 0.8;
        }

        .login-link {
            display: block;
            margin-top: 20px;
            color: navy;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .signup {
            margin-top: 20px;
        }

        .signup a {
            color: navy;
            text-decoration: none;
        }

        .signup a:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 20px;
            font-size: 18px;
            color: red;
            text-align: center;
            width: 80%;
            max-width: 500px;
            margin-bottom: 20px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .message.show {
            opacity: 1;
        }

        .success {
            color: goldenrod;
            background-color: navy;
            padding: 10px;
            border-radius: 5px;
            animation: fadeIn 1s ease-in-out;
            text-align: center;
            width: 80%;
            max-width: 500px;
            margin-bottom: 20px;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

       

        .reset-password-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="message <?php echo !empty($message) && !$success ? 'show' : ''; ?>">
        <?php echo $message; ?>
    </div>

    <div class="success<?php echo $success ? 'show' : ''; ?>">
        Password reset successful.
    </div>

    <div>
        <img class="logo" src="image/Logo.png" alt="Logo">
    </div>
    <div class="reset-password-container">
        <p class="reset-password-title">Reset Password</p>
        <form action="forgot_password.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your email..">
            <label for="new-password">New Password:</label>
            <input type="password" id="new-password" name="new-password" placeholder="Enter new password..">
            <label for="confirm-password">Re-enter Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="Re-enter new password..">
            <button type="submit">Reset Password</button>
        </form>
        <a href="Login.php" class="login-link">Go back to Login</a>
        <div class="signup">
            <a href="signup.php">Don't have an account? Sign up</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = document.querySelector('.message');
            const successMessage = document.querySelector('.success');

            if (message.classList.contains('show')) {
                setTimeout(() => {
                    message.style.opacity = 0;
                }, 3000);
            }

            if (successMessage.classList.contains('show')) {
                setTimeout(() => {
                    successMessage.style.opacity = 0;
                }, 3000);
            }
        });
    </script>
</body>
</html>
