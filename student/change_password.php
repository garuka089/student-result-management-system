<?php
// ── Load required files ──────────────────────────────────────
require_once '../include/student_auth.php'; // Check if student is logged in
require_once '../include/db.php';           // Database connection
require_once '../include/header.php';       // HTML header & navbar

$success = "";
$error   = "";


// ── Handle Form Submission ────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get passwords from form
    $current_password = md5(trim($_POST['current_password']));
    $new_password     = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if current password matches database
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND password = ?");
    $stmt->execute([$_SESSION['user_id'], $current_password]);

    if ($stmt->rowCount() === 0) {
        // Current password is wrong
        $error = "Current password is incorrect!";

    } elseif ($new_password !== $confirm_password) {
        // New password and confirm password do not match
        $error = "New password and confirm password do not match!";

    } elseif (strlen($new_password) < 6) {
        // Password too short
        $error = "New password must be at least 6 characters!";

    } else {
        // All checks passed — update password in database
        $hashed = md5($new_password);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashed, $_SESSION['user_id']]);
        $success = "Password changed successfully!";
    }
}
?>

<div class="container mt-4">

    <!-- Page Title -->
    <div class="d-flex align-items-center mb-4">
        <!-- Back button to result page -->
        <a href="result.php" class="btn btn-outline-primary btn-sm me-3">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <h3 class="fw-bold mb-0">
            <i class="fas fa-key me-2 text-primary"></i>Change Password
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">

                <!-- Card header -->
                <div class="card-header bg-primary text-white fw-bold py-3">
                    <i class="fas fa-lock me-2"></i>Update Your Password
                </div>

                <div class="card-body p-4">

                    <!-- Show success message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <!-- Show error message -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-primary"></i>Current Password
                            </label>
                            <input
                                type="password"
                                name="current_password"
                                class="form-control"
                                placeholder="Enter current password"
                                required
                            >
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-key me-1 text-primary"></i>New Password
                            </label>
                            <input
                                type="password"
                                name="new_password"
                                class="form-control"
                                placeholder="Enter new password (min 6 chars)"
                                required
                            >
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-1 text-primary"></i>Confirm New Password
                            </label>
                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                placeholder="Re-enter new password"
                                required
                            >
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i>Change Password
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../include/footer.php';?>