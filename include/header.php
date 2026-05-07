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
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
    <!-- System logo and name -->
    <a class="navbar-brand fw-bold" href="#">
        <i class="fas fa-graducation-cap me-2"></i>Student Result system
    </a>
    
    <!--Mobile toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar links - only shows when users is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="collapse navbar-collapse" id="navbarNav">

        <!--Admin navigation links -->
        <?php if($_SESSION['role'] === 'admin'): ?>
        <ul class="navbar-nav me-auto">

            <!-- Dashboard link -->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/admin/dashboard.php">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
            </li>

            <!-- Add Student link -->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/admin/add_student.php">
                    <i class="fas fa-user-plus me-1"></i>Add Student
                </a>
            </li>

            <!-- Manage Student-->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/admin/manage_students.php">
                    <i class="fas fa-users me-1"></i>Manage Student
                </a>
            </li>

            <!--Add MArks Links -->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/admin/add_marks.php">
                    <i class="fas fa-pen me-1"></i>Add Marks
                </a>
            </li>

            <!-- Add Subject link -->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/admin/add_subject.php">
                    <i class="fas fa-book-open me-1"></i>Add Subject
                </a>
            </li>

        </ul>
        <?php endif; ?>

        <!--Student navigation bar -->
        <?php if($_SESSION['role'] === 'student'): ?>
        <ul class="navbar-nav me-auto">

            <!-- My REsults link -->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/student/result.php">
                    <i class="fas fa-chart-bar me-1"></i>My Results
                </a>
            </li>

            <!-- Change Password link-->
            <li class="nav-item">
                <a class="nav-link" href="/student-result-system/student/change_password.php">
                    <i class="fas fa-key me-1"></i>Change Password
                </a>
            </li>
        </ul>
        <?php endif; ?>

        <!-- Right Side: USer info and logout -->
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3">

                <!-- Show logged in user name and role badge -->
                <span class="text-white">
                    <i class="fas fa-user-circle me-1"></i>
                    <?= htmlspecialchars($_SESSION['name']) ?>
                    <span class="badge bg-warning text-dark ms-1">
                        <?= ucfirst($_SESSION['role']) ?>
                    </span>
                </span>
            </li>

            <!-- Logout button -->
            <li class="nav-item">
                <a href="/student-result-system/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>
</nav>
