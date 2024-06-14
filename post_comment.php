<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO comments (comment, created_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $comment);

    // Get comment from POST data
    $comment = $_POST['comment'];

    // Execute statement
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
