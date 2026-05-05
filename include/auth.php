<?php
//Authentocation part
if (session_status() == PHP_SESSION_NONE){
    session_start();
}

// If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])){
    header("Location: /student-result-system/index.php");
    exit();
}

// If logged in but NOT admin, redirect to student page
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'admin'){
    header("Location: /student-result-system/student/result.php");
    exit();
}
?>