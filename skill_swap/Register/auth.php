<?php
    session_start();
    if(!isset($_SESSION['authenticated']))
    {
        $_SESSION['status'] = 'Please Login Firest';
        header('Location: http://localhost:8888/skill_swap/register/login.php');
        exit(0);
    }
?>