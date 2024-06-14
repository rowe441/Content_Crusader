<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - Content Crusader Guide</title>
    <link rel="icon" type="image/x-icon" href="image/fav.png">
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
            <div class="g-recaptcha" data-sitekey="6LdoFe8pAAAAAHruRhfY2hzzTlbatq6w-Ih0plZt"></div> 

            <!-- reCAPTCHA widget -->
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
