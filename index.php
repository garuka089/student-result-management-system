<?php
require_once "include/db.php";
require_once "include/header.php";

$error = "";

if($_SERVER['REQUEST_METHOD']==="POST"){
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    // Ckeck user in database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
        // Save user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin'){
            header("Location: admin/dashboard.php");
        } else {
            header("Location: student/result.php");
        }
        exit();
    } else{
        $error = "Invalid email or password. Please try again. ";
    }
}
?>

<div class="container">
    <div class="card login-card">

        <div class="card-header">
            <i class="fas fa-graduation-cap me-2"></i>
            Student Result system
        </div>

        <div class="card-body p-4">
            <h5 class="text-center text-muted mb-4">Login to your account</h5>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?=  $error ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <div>
                <form method="POST" action="">
                    <!-- Email Field -->
                    <div class="mb-3">
                        <lable class="form-lable fw-semibold">
                            <i class="fas fa-envelope me-1 text-primary"></i> Email Address
                        </lable>
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-lg"
                            placeholder="Enter Your Email"
                            required
                        >
                    </div>
                
                    <!-- Password Field -->
                    <div class="md-4">
                        <label class="form-lable fw-semibold">
                            <i class="fas fa-lock me-1 text-primary"></i> Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            class="form-control form-control-lg"
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>

                </form>
            </div>

            <!-- Role Info -->
            <div class="mt-4 text-center text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Admin or Student credentials required to login
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/footer.php'; ?>