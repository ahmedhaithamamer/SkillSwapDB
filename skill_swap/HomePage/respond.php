<?php
session_start();
include '../Register/connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must login first!'); window.location.href='../Register/login.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = intval($_POST['request_id']);
    $response_type = $_POST['response_type'];
    $user_id = $_SESSION['user_id'];

    // Insert into responds table
    $stmt = $conn->prepare("INSERT INTO responds (request_id, user_id, response_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $request_id, $user_id, $response_type);

    if ($stmt->execute()) {
        echo "<script>alert('Responded successfully!'); window.location.href='home.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
