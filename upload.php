<?php
session_start();

// Handle upload form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $name = $_POST['name'];
    $jobTitle = $_POST['job_title'];
    $category = $_POST['category'];  // New category field

    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $fileTmpPath = $_FILES['pdf_file']['tmp_name'];
        $fileName = $_FILES['pdf_file']['name'];
        $fileSize = $_FILES['pdf_file']['size'];
        $fileType = $_FILES['pdf_file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('pdf');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = './uploaded_files/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $message = 'File is successfully uploaded.';
                
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "crusadersignup";

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "INSERT INTO uploaded_files (title, abstract, name, job_title, category, file_name, file_path) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $title, $abstract, $name, $jobTitle, $category, $fileName, $dest_path);

                if ($stmt->execute()) {
                    $message .= "<br>Record added to database.";
                } else {
                    $message .= "<br>Error: " . $sql . "<br>" . $conn->error;
                }

                $stmt->close();
                $conn->close();
            } else {
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $message = 'There is some error in the file upload. Please check the following error.<br>';
        $message .= 'Error:' . $_FILES['pdf_file']['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload PDF file</title>
    <link rel="icon" type="image/x-icon" href="image/fav.png">
    <style>
        /* Global styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-image: linear-gradient(rgba(4, 9, 30, 0.7), rgba(4, 9, 30, 0.7)), url('image/background.png');
            min-height: 100vh;
            width: 100%;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'DM Serif Text', serif;
            color: #ffffff;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 50px;
            border-radius: 10px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header a {
            text-decoration: none;
            color: yellow;
            font-size: 1.2em;
            font-weight: bold;
        }
        h2 {
            color: yellow;
            margin-bottom: 20px;
            text-align: center;
            font-size: 2em;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 1.2em;
        }
        input[type="text"],
        textarea,
        input[type="file"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: rgba(255, 255, 255, 0.5);
            color: #000;
            font-size: 1em;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2em;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.2em;
            color: yellow;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="javascript:history.back()">Back</a>
        </div>
        <h2>Upload PDF</h2>
        <?php 
        if (!empty($message)) {
            echo '<p class="message">' . $message . '</p>';
        }
        ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>

            <label for="abstract">Abstract:</label>
            <textarea id="abstract" name="abstract" rows="4" required></textarea>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="job_title">Job Title:</label>
            <input type="text" id="job_title" name="job_title" required>

            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                <option value="MARKETING TRENDS">MARKETING TRENDS</option>
                <option value="CONTENT STRATEGY">CONTENT STRATEGY</option>
                <option value="SOCIAL MEDIA">SOCIAL MEDIA</option>
                <option value="SEO">SEO</option>
                <option value="BRANDING">BRANDING</option>
            </select>

            <label for="pdf_file">Upload PDF:</label>
            <input type="file" id="pdf_file" name="pdf_file" required>

            <input type="submit" name="upload" value="Upload">
        </form>
    </div>
</body>
</html>
