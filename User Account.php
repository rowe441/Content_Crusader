<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crusadersignup";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Initialize user info
$user_info = [
    'name' => '',
    'email' => '',
    'uploaded_files' => []
];

if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Fetch user details
    $sql = "SELECT first_name, email FROM users WHERE users_email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($first_name, $email);
        if ($stmt->fetch()) {
            $user_info['name'] = $first_name;
            $user_info['email'] = $email;
        } else {
            echo "No user found with the email: " . htmlspecialchars($email);
        }
        $stmt->close();
    } else {
        error_log("Error in preparing statement: " . $conn->error);
        echo "Error in preparing statement: " . $conn->error;
    }

    // Fetch user uploaded files
    $sql = "SELECT file_name, privacy_status FROM uploads WHERE users_email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $user_info['uploaded_files'][] = $row;
        }
        $stmt->close();
    } else {
        error_log("Error in preparing statement: " . $conn->error);
        echo "Error in preparing statement: " . $conn->error;
    }
} else {
    echo "Session email not set.";
}

// Handle file uploads
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['uploads'])) {
    $uploadDir = 'upload/';
    foreach ($_FILES['uploads']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['uploads']['name'][$key]);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            // Insert file info into database
            $sql = "INSERT INTO uploads (user_email, file_name, privacy_status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $privacyStatus = 'private'; // Set default privacy status
                $stmt->bind_param('sss', $email, $fileName, $privacyStatus);
                if (!$stmt->execute()) {
                    error_log("Error inserting file info into database: " . $stmt->error);
                }
                $stmt->close();
            } else {
                error_log("Error in preparing statement: " . $conn->error);
            }
        } else {
            error_log("Error moving uploaded file: " . $fileName);
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
    <title>User Account</title>
    <link rel="icon" type="image/x-icon" href="image/fav.png">
    <link rel="stylesheet" href="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-image: linear-gradient(rgba(4, 9, 30, 0.7), rgba(4, 9, 30, 0.7)), url(image/background.png);
            min-height: 100vh;
            width: 100%;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-family: 'DM Serif Text', serif;
            padding: 20px;
        }

        .header {
            width: 100%;
            height: 90px;
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
            justify-content: space-between;
        }

        .glass-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        nav {
            display: flex;
            align-items: center;
            width: 100%;
        }

        .nav-center {
            display: flex;
            justify-content: center;
            flex-grow: 5;
        }

        .nav-center ul {
            display: flex;
            list-style: none;
        }

        .nav-center ul li a {
            margin: 0 20px;
            padding: 10px;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }

        .nav-center ul li a:hover {
            color: yellow;
        }

        .logo img {
            width: 160px;
        }

        .profile-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-icon img {
            width: 40px;
            height: 40px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 8px;
            position: absolute;
            top: 60px;
            right: 10px;
            width: 150px;
        }

        .dropdown-menu a {
            padding: 10px;
            text-decoration: none;
            color: white;
            text-align: center;
        }

        .dropdown-menu a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .profile-container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 1200px;
            margin: 100px 20px 20px 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .profile-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: yellow;
            text-align: center;
        }

        .profile-container label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        .profile-container input[type="text"],
        .profile-container input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .uploads-container {
            margin-top: 20px;
        }

        .uploads-container h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .uploads-container ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .uploads-container ul li {
            margin-bottom: 10px;
        }

        .upload-btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: yellow;
            color: black;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
        }

        .upload-btn:hover {
            background-color: gold;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="glass-container">
            <nav>
                <a href="Dashboard.html" class="logo"><img src="image/Logo.png" alt="Logo"></a>
                <div class="nav-center">
                    <ul>
                        <li><a href="Solutions.html">Solutions</a></li>
                        <li><a href="Blog.html">Blog</a></li>
                        <li><a href="About.html">About</a></li>
                    </ul>
                </div>
                <div class="right-side">
                    <div class="profile-icon" onclick="toggleDropdownMenu()">
                        <img src="image/profile.png" alt="Profile" id="profile-icon">
                        <div class="dropdown-menu" id="dropdown-menu">
                            <a href="#">User Account</a>
                            <a href="Logout.php">Logout</a>
                            <a href="#" onclick="openUpload()">Upload</a>
                            <a href="Privacy.html">Privacy</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <div class="profile-container">
        <h2>User Account</h2>
        <?php if (isset($user_info['name']) && $user_info['name'] != ''): ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_info['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></p>
        
            <div class="uploads-container">
                <h3>Uploaded Files</h3>
                <ul>
                    <?php if (!empty($user_info['uploaded_files'])): ?>
                        <?php foreach ($user_info['uploaded_files'] as $file): ?>
                            <li><?php echo htmlspecialchars($file['file_name']) . ' (' . htmlspecialchars($file['privacy_status']) . ')'; ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No uploaded files found.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php else: ?>
            <p>No user information found. Please log in again.</p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="file" name="uploads[]" multiple>
            <button type="submit" class="upload-btn">Upload Files</button>
        </form>
    </div>

    <script>
        function toggleDropdownMenu() {
            var dropdownMenu = document.getElementById("dropdown-menu");
            dropdownMenu.style.display = dropdownMenu.style.display === "none" || dropdownMenu.style.display === "" ? "flex" : "none";
        }
    </script>
</body>
</html>