<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

$success = "";
$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));
    $student_id = trim($_POST['student_id']);
    $department = trim($_POST['department']);
    $year = trim($_POST['year']);

    // Check if email already exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    
    if($check->rowCount() >0){
        $error = "This email is already registered";
    } else{
        // Insert into users table
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, 'student');
        ");
        $stmt->execute([$name, $email, $password]);
        $user_id = $pdo->lastInsertId();

        // Insert into stundet table
        $stmt2 = $pdo->prepare("
            INSERT INTO student (user_id, student_id, department, year)
            VALUES (?, ?, ?, ?)
        ");
        $stmt2->execute([$user_id, $student_id, $department, $year]);

        $success = "Student added successfully";
    }

}
?>

<div class="container mt-4">

    <!-- Page Tittle -->
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn btn-outline-primary btn-sm me-3">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <h3 class="fw-bold mb-0">
            <i class="fas fa-user-plus me-2 text-primary"></i>Add New Student
        </h3>
    </div>

    <div class="row justify-content-center">
        <div class="col-mb-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-primary text-white fw-bold py-3 rounded-top">
                    <i class="fas fa-user-graduate me-2"></i>Student Information
                </div>
                <div class="card-body p-4">

                    <!-- Success / Error Massages -->
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <!-- full Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i>Full name
                            </label>
                            <input 
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="e.g. John Perea"
                                required
                            >
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-lable fw-semibold">
                                <i class="fas fa-envelope me-1 text-primary"></i> Email adderess
                            </label>
                            <input
                                type = "email"
                                name = "email"
                                class="form-control"
                                placeholder="e.g. john@gmail.com"
                                required
                            >
                        </div>
                        
                        <!-- password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-primary"></i>password
                            </label>
                            <input
                                type = "password"
                                name = "password"
                                class="form-control"
                                placeholder="Student login password"
                                required
                            >
                        </div>
                        
                        <!-- Student ID -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-id-card me-1 text-primary"></i>Student ID
                            </label>
                            <input
                                type = "text"
                                name = "student_id"
                                class = "form-control"
                                placeholder = "e.g. STU2024001"
                                required
                            >
                        </div>

                        <!-- Department -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-building me-1 text-primary"></i>Department
                            </label>
                            <select name="department" class="form-select" required>
                                <option value="">-- Select Department --</option>
                                <option value="Software Engineering">Software Engineering</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Electrical Engineering">Electrical Enginnering</option>
                                <option value="Business Management">Business Management</option>
                            </select>

                        </div>

                        <!-- Year -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-calendar me-1 text-primary"></i>Academic Year
                            </label>
                            <select name="year" class="form-select" required>
                                <option value="">-- Select Year --</option>
                                <option value="1">Year 1 </option>
                                <option value="2">Year 2 </option>
                                <option value="3">Year 3 </option>
                                <option value="4">Year 4 </option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Add Student
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../include/footer.php'; ?>