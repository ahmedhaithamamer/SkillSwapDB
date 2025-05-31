<?php
session_start();
include 'connect.php';

if (isset($_POST['email'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password']; // Keep raw password for verification

        $login_query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $login_query_run = mysqli_query($conn, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_assoc($login_query_run);

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['authenticated'] = true;
                $_SESSION['auth_user'] = [
                    'username' => $row['name'],
                    'phone'    => $row['phone'],
                    'email'    => $row['email'],
                ];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['status'] = 'You Logged In Successfully';
                header("Location: http://localhost:8888/skill_swap/HomePage/home.php");
                exit(0);
            } else {
                $_SESSION['status'] = 'Invalid Email or Password';
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = 'Invalid Email or Password';
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = 'All fields are mandatory';
        header("Location: login.php");
        exit(0);
    }
}
?>
