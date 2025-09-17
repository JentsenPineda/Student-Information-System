<?php
$host = "localhost";
$user = "root";   // Default user in XAMPP
$pass = "";       // Default password is empty
$db   = "student_information";  // Change if your DB name is different

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
