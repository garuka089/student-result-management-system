<?php
// Start the session if not already started
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

// IF not logged in redirected to login page
if(!isset($_SESSION['user_id'])){
    header("Location: /student-result-system/index.php");
    exit();
}

// If logged in but NOT a student redirect to admin
if ($_SESSION['role'] !== 'student'){
    header("Location: /student-result-system/admin/dashboard.php");
    exit();
}

?>