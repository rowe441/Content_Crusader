<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Sample data (replace with your actual user data logic)
$userName = 'John Doe';
$uploads = [
    ['name' => 'Upload 1', 'abstract' => 'This is the abstract for Upload 1.', 'public' => true],
    ['name' => 'Upload 2', 'abstract' => 'This is the abstract for Upload 2.', 'public' => false],
    ['name' => 'Upload 3', 'abstract' => 'This is the abstract for Upload 3.', 'public' => true],
];

// Handle upload form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    // Handle file upload here
    // Save file, abstract, and privacy setting
    $uploadName = $_POST['upload_name'];
    $abstract = $_POST['abstract'];
    $isPublic = isset($_POST['is_public']) ? true : false;

    // Sample save logic (replace with actual saving logic)
    $uploads[] = [
        'name' => $uploadName,
        'abstract' => $abstract,
        'public' => $isPublic,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your external CSS file -->
</head>
<body>
    <div class="glass-container">
        <img class="logo" src="image/Logo.png" alt="Logo">
        <h2>User Account: <?php echo htmlspecialchars($userName); ?></h2>

        <!-- Upload Form -->
        <h3>Uploads</h3>
        <form action="" method="POST">
            <label for="upload_name">Upload Name:</label><br>
            <input type="text" id="upload_name" name="upload_name" required><br><br>

            <label for="abstract">Abstract:</label><br>
            <textarea id="abstract" name="abstract" rows="4" cols="50" required></textarea><br><br>

            <label for="is_public">Public:</label>
            <input type="checkbox" id="is_public" name="is_public" value="true"><br><br>

            <input type="submit" name="upload" value="Upload">
        </form>

        <!-- Uploaded Items -->
        <?php foreach ($uploads as $index => $upload) { ?>
            <div class="upload-item">
                <strong><?php echo htmlspecialchars($upload['name']); ?></strong><br>
                Abstract: <?php echo htmlspecialchars($upload['abstract']); ?><br>
                <?php echo $upload['public'] ? 'Public' : 'Private'; ?>
            </div>
        <?php } ?>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <script>
        // Password show/hide functionality (from your original login page)
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('psw');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        // Form validation (from your original login page)
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
