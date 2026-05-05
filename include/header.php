<?php
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result System</title>

    <!--Boostrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!---Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!--Custome CSS -->
    <link href="/student-result-system/css/style.css" rel="stylesheet">
</head>

<body>
<!-- Nav -->
<nav class="navbar navbar-dark bg-primary px-4">
    <span class="navbar-brand fw-bold">
        <i class="fas fa-graduation-cap me-2"></i>Student Result System
    </span>>

    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white"> 
                <i class="fas fa-user me-1"></i>
                <?htmlspecialchars($_SESSION['name'])?>
            </span>
            <a href="/student-result-system/logout.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt me-1"></i>Logout
            </a>
        </div>
    <?php endif; ?>    

</nav>    
    
