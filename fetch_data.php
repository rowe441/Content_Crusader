<?php
function fetchData($category) {
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "crusadersignup";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = $conn->prepare("SELECT title, name, job_title FROM uploaded_files WHERE type = ?");
  $sql->bind_param("s", $category);
  $sql->execute();
  $result = $sql->get_result();

  $data = [];
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }

  $conn->close();
  return $data;
}
?>
