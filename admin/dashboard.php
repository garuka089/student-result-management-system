<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

// Count total student
$totalStudents = $pdo->query("SELECT COUNT(*) FROM student")->fetchColumn();

// Count total subjects
$totalSubjects = $pdo->query("SELECT COUNT(*) FROM subjects")->fetchColumn();

// Count total marks entered
$totalMarks = $pdo->query("SELECT COUNT(*) FROM marks")->fetchColumn();
?>

<div class="container mt-4">

    <!-- Welcome Message -->
    <div class="mb-4">
        <h3 class="fw-bold">
            <i class="fas fa-tachometer-alt me-2 text-primary"></i>
            Admin Dashboard
        </h3>
        <p class="text-muted">
            Welcome back, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>!
            Here's your system overview.
        </p>

    </div>

    <!-- Start Cards Row -->
    <div class="row g-4 mb-5">

        <!-- Total Student -->
        <div class="col-md-4">
            <div class="stat-card blue p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Student</p>
                        <h2 class="fw-bold mb-0"><?=  $totalStudents ?></h2>
                    </div>
                    <i class="fas fa-user-graduate fa-3x opacity-75"></i>
                </div>

            </div>
        </div>

        <!-- Total Subjects -->
        <div class="col-md-4">
            <div class="stat-card green p-4 rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Subjects</p>
                        <h2 class="fw-bold mb-0"><?=  $totalStudents ?></h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-75"></i>
                </div>
            </div>        
        </div>

        <!-- Total Marks Entered -->
        <div class="col-md-4">
            <div class="stat-card orange p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Marks Entered</p>
                        <h2 class="fw-bold mb-0"><?=  $totalMarks ?></h2>
                    </div>
                    <i class="fas fa-clipboard-list fa-3x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <!--- Quick Action -->
    <div class="mb-4">
        <h5 class="fw-bold mb-3">
            <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
        </h5>
        
        <div class="row g-3">

            <!-- Add Student -->
            <div class="col-md-3">
                <a href="add_student.php" class="text-decoration-none"> 
                    <div class="card text-center p-3 h-100 shadow-sm border-0 quick-card">
                        <i class="fas fa-user-plus fa-2x test-primary mb-2"></i>
                        <p class="mb-0 fw-semibold">Add Student</p>
                    </div>
                </a>
            </div>

            <!-- Add Marks -->
            <div class="col-md-3">
                <a href="add_marks.php" class="text-decoration-none">
                    <div class="card text-center p-3 h-100 shadow-sm border-0 quick-card">
                        <i class="fas fa-pen fa-2x text-success mb-2"></i>
                        <p class="mb-0 fw-semibold">Add Marks</p>
                    </div>
                </a>
            </div>    

            <!-- Manage Student -->
            <div class="col-md-3">
                <a href="manage_students.php" class="text-decoration-none">
                    <div class="card text-center p-3 h-100 shadow-sm border-0 quick-card">
                        <i class="fas fa-users fa-2x text-warning mb-2"></i>
                        <p class="mb-0 fw-semibold">Manage Student</p>
                    </div>
                </a>
            </div>

            <!-- Add Subject -->
            <div class="col-md-3">
                <a href="add_subject.php" class="text-decoration-none">
                    <div class="card text-center p-3 h-100 shadow-sm border-0 quick-card">
                        <i class="fas fa-book-open fa-2x text-danger mb-2"></i>
                        <p class="mb-0 fw-semibold">Add Subject</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Students Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold py-3">
            <i class="fas fa-clock me-2 text-primary"></i>Recently Added Student
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Student</th>
                        <th>Department</th>
                        <th>Year</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch last 5 student
                    $stmt = $pdo->query("
                    SELECT u.name, s.student_id, s.department, s.year
                    FROM student s
                    JOIN users u ON s.user_id = u.id
                    ORDER BY s.id DESC
                    LIMIT 5
                    ");
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if(count($students) > 0):
                        $i = 1;
                        foreach($students as $student): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['student_id']) ?></td>
                                <td><?= htmlspecialchars($student['department']) ?></td>
                                <td>Year <?= $student['year'] ?></td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-mutedd py-4">
                                <i class="fas fa-indox me-2"></i>No students added yet.
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require_once '../include/footer.php'; ?>