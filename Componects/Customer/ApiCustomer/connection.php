<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_project";

// $servername = "student.crru.ac.th";
// $username = "641463021";
// $password = "38905";
// $dbname = "641463021";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");
