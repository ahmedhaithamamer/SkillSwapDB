<?php
    session_start();
    include 'connect.php';
    
    if(isset($_POST['email']))
    {
        $name      = $_POST['name'];
        $email     = mysqli_real_escape_string($conn, $_POST['email']);
        $phone     = $_POST['phone'];
        $password  = $_POST['password'];
        $cpassword = $_POST['confirmPassword'];
        $major     = $_POST['major'];
        $year      = $_POST['year'];
        $bio       = mysqli_real_escape_string($conn, $_POST['bio']) ?? '';
        
        // $otp       = md5(rand());

        $skills_json = $_POST['skills'];
        $skills_array = json_decode($skills_json, true);
    
        $skill_names = [];
        foreach ($skills_array as $skill) {
            $skill_names[] = $skill['value'];
        }
        if ($password != $cpassword)
        {
            $_SESSION['status'] = "Passwords do not match!";
            header("Location: register.php");
        }
        else
        {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $hashed_password = mysqli_real_escape_string($conn, $hashed_password);

            $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
            $check_email_query_run = mysqli_query($conn, $check_email_query);
            
            if(mysqli_num_rows($check_email_query_run) > 0)
            {
                $_SESSION['status'] = "This Email Is Already Exists";
                header("Location: register.php");
            }
            else
            {
                $query = "INSERT INTO users (name, email, password, phone, major, year, bio) VALUES ('$name', '$email', '$hashed_password', '$phone', '$major', '$year', '$bio')";
                $query_run = mysqli_query($conn, $query);

                $_SESSION['user_id'] = $conn->insert_id;
                $user_id = $_SESSION['user_id'];

                foreach ($skills_array as $skill)
                {
                    $skill_id = $skill['id'];

                    $query2 = "INSERT INTO users_skills (user_id, skill_id) VALUES ('$user_id', '$skill_id')";
                    $query_run2 = mysqli_query($conn, $query2);
                }
                
                header("Location: login.php");
            }
        }
    }
?>