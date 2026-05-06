<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === "POST"){
    $subject_name = trim($_POST['subject_name']);
    $subject_code = trim($_POST['subject_code']);

    // Check if subject code already exits
    $check = $pdo->prepare("SELECT id FROM subjects WHERE subject_code = ?");
    $check->execute([$subject_code]);

    if($check->rowCount()>0){
        $error = "This Subject code already exists";
    }else{
        $stmt = $pdo->prepare("
            INSERT INTO subjects (subject_name, subject_code)
            VALUES (?, ?)
        ");
        $stmt->execute([$subject_name, $subject_code]);
        $success = "Subject added successfully!";
    }
}

// Fetch all subjects to display below
$subjects = $pdo->query("SELECT * FROM subjects ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <!-- Page Title -->
    <div class="d-flex align-items-center mb-4">
        <a href="dashboard.php" class="btn btn-outline-primary btn-sm me-3">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <h3 class="fw-bold mb-0">
            <i class="fas fa-book-open me-2 text-danger"></i>Add New Subject
        </h3>
    </div>

    <div class="row">

        <!-- Add Subject Form -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-danger tet-white fw-bold py-3">
                    <i class="fas fa-plus-circle me-2"></i>Subject Details
                </div>
                <div class="card-body p-4">

                    <!-- Success / Error Message -->
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        
                        <!-- Subject Name -->
                        <div class="mb-3">
                            <label class="form-lable fw-semibold">
                                <i class="fas fa-book me-1 text-danger"></i>Subject name
                            </label>
                            <input
                                type="text"
                                name="subject_name"
                                class="form-control"
                                placeholder="e.g. Database Managemnt"
                                required
                            >
                        </div>

                        <!-- Subject Code -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag me-1 text-danger"></i>Subject Code
                            </label>
                            <input
                                type="text"
                                name="subject_code"
                                class="form-control"
                                placeholder="e.g. SE2201"
                                required
                            >
                        </div>

                        <!-- Button -->
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-save me-2"></i>Add Subject
                        </button>
                    </form>
                </div>
            </div>
        </div>
    
        <!-- Subject Links -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-list me-2 text-danger"></i>All subjects
                    <span class="badge bg-danger ms-2"><?= count($subjects) ?></span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($subjects)>0):
                                $i = 1;
                                foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($subject['subject_code']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox me-2"></i>No subjects added yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                            
                    </table>
                </div>
            </div>
        </div>

    </div>
    
</div>