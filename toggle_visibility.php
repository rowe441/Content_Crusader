<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileToToggle = $_POST['file'];
    // Simulated logic to toggle visibility (replace with actual logic)
    $uploadedFiles = array(
        array('file' => 'marketing_trends.pdf', 'published' => true),
        array('file' => 'content_strategy.pdf', 'published' => false)
    );

    foreach ($uploadedFiles as &$file) {
        if ($file['file'] === $fileToToggle) {
            $file['published'] = !$file['published'];
            break;
        }
    }

    $_SESSION['message'] = 'Visibility updated successfully.';
} else {
    $_SESSION['message'] = 'Error: Invalid request.';
}

header('Location: profile.php');
exit();
?>
