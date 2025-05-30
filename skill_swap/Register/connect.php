<?php
$servername = "localhost";
$username = "root";
// $password = "root"; // Default XAMPP password
$password = ""; // Ashour's laptop
$dbname = "skill_swap_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>