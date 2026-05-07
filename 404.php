<?php
// --Load header --
require_once 'include/header.php';
?>

<div class="container mt-5">
    <div class="text-center py-5">
        <!--Big 404 ICON -->
        <i class="fas fa-exclamation-circle fa-5x text-danger mb-4"></i>

        <!--Error code-->
        <h1 class="display-1 fw-bold text-danger">404</h1>

        <!--Error Massage-->
        <h4 class="text-muted mb-3">Oops! Page Not Found</h4>
        <p class="text-muted mb-4">
            The page you are looking for does not exist or has been moved.
        </p>

        <!--Go back home button -->
        <a href="/student-result-system/index.php" class="btn btn-primary px-5">
            <i class="fas fa-home me-2"></i>Go to home
        </a>
    </div>
</div>

<?php require_once 'include/footer.php'; ?>