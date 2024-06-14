<?php
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

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['g-recaptcha-response'])) {
        // Verify reCAPTCHA
        $recaptcha_secret = '6LdoFe8pAAAAAPt2Fe8-8DmETRzduu2s0sOShAZr';  // Replace with your secret key
        $recaptcha_response = $_POST['g-recaptcha-response'];

        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            echo '<script>alert("Please complete the reCAPTCHA");</script>';
        } else {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // Check if the username is an email
                if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                    $sql = "SELECT * FROM users WHERE email=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if (password_verify($password, $row['password'])) {
                            $_SESSION['user'] = $row['id'];
                            header("Location: Dashboard.html");
                            exit();
                        } else {
                            echo '<script>alert("Invalid password.");</script>';
                        }
                    } else {
                        echo '<script>alert("No user found with the provided email.");</script>';
                    }
                } else {
                    echo '
                    Invalid login. Please use an email.
                    ';
                }
            } else {
                echo '<script>alert("Please enter both username and password.");</script>';
            }
        }
    } else {
        echo '<script>alert("reCAPTCHA response is missing.");</script>';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Crusader Guide</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="glass-container">
        <img class="logo" src="image/Logo.png" alt="Logo">
        <form id="loginForm" action="action_page.php" method="post">
            <label for="username">Email:</label>
            <input type="text" id="username" name="username" placeholder="Email.." required>
            <label for="psw">Password:</label>
            <input type="password" id="psw" name="password" placeholder="Your password.." required>
            <div class="show-password-container">
                <input type="checkbox" class="show-password" id="showPassword">
                <label for="showPassword" class="show-password-label">Show Password</label>
            </div>
            <div class="g-recaptcha" data-sitekey="6LdoFe8pAAAAAHruRhfY2hzzTlbatq6w-Ih0plZt"></div> <!-- reCAPTCHA widget -->
            <button type="submit">Login</button>
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
            <div class="forgot-password">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
            <div class="signup">
                <a href="signup.php">Don't have an account? Sign up</a>
            </div>
            <div id="notification" class="notification"></div> <!-- Notification container -->
        </form>
    </div>

    <script>
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('psw');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            var username = document.getElementById('username').value;
            var password = document.getElementById('psw').value;
            var notification = document.getElementById('notification');

            notification.innerHTML = '';

            if (!username) {
                event.preventDefault();
                notification.innerHTML = 'Please enter your email.';
            } else if (!validateEmail(username)) {
                event.preventDefault();
                notification.innerHTML = 'Invalid login. Please use an email.';
            } else if (!password) {
                event.preventDefault();
                notification.innerHTML = 'Please enter your password.';
            }
        });

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(String(email).toLowerCase());
        }
    </script>
</body>
</html>
