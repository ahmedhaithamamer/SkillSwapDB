<?php
include '../Register/connect.php';
include '../Register/auth.php';

// Check if request ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Request ID is required'); window.location.href='../HomePage/home.php';</script>";
    exit();
}

$request_id = intval($_GET['id']);
$current_user_id = $_SESSION['user_id'];

// Process new response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_response'])) {
    $message = mysqli_real_escape_string($conn, $_POST['response_message']);
    $response_type = mysqli_real_escape_string($conn, $_POST['response_type']);
    
    $sql_insert = "INSERT INTO responds (request_id, user_id, note, response_type, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iiss",$request_id, $current_user_id, $message, $response_type);
    
    if ($stmt_insert->execute()) {
        echo "<script>window.location.href='marketplace.php';</script>";
        exit();
    } else {
        $error_message = "Failed to submit response: " . $stmt_insert->error;
    }
}
?>