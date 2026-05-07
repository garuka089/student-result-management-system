<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

$sucess = "";
$error = "";

// Check if delete button was clicked
if(isset($_GET['delete_id'])){
    $delete_id = $_GET['delete_id'];

    // Get the user_id linked to this student
    $getUSer = $pdo->prepare("SELECT user_id FROM student WHERE id = ?");
    $getUSer->execute([$delete_id]);
    $userData = $getUSer->fetch(PDO::FETCH_ASSOC);

    if($userData){
        // Step-1 Delete marks first (because marks depend on student)
        $pdo->prepare("DELETE FROM marks WHERE student_id= ?")->execute([$delete_id]);

        // Step-2 Delete from student table
        $pdo->prepare("DELETE FROM student WHERE id = ?")->execute([$delete_id]);

        // Step-3 Delete from users table (login account)
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$userData['user_id']]);

        $sucess = "Student deleted successfully!";
    } else{
        $error = "Student not found";
    }
}

// Fetch all from the Database
// Join student table with user table to get the name and email
$stmt = $pdo->query("
    SELECT s.id, u.name, u.email, s.student_id, s.department, s.year
    FROM student s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.id DESC
");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">

    <!-- Page Title & Add Button -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <!-- Back button to dashboard -->
            <a href="dashboard.php" class="btn btn-outline-primary btn-sm me-3">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
            <h3 class="fw-bold mb-0">
                <i class="fas fa-users me-2 text-warning"></i> Manage Students
            </h3>
        </div>

        <!-- Button to go Add student page -->
        <a href="add_student.php" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Add New Student
        </a>
    </div>

    <!-- Show success massage if student was deleted -->
    <?php if($sucess): ?>
        <div class="alert alert-success"><?= $sucess ?></div>
    <?php endif; ?>

    <!-- Show an erro message if somethong went wrong -->
    <?php if($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Student table -->
    <div class="card-header bg-white fw-bold py-3">
        <i class="fas fa-list me-2 text-warning"></i>All Students
        <span class="badge bg-warning text-dark ms-2"><?= count($students) ?></span>
    </div>

    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Student</th>
                    <th>Department</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($students) > 0):
                    $i = 1; //Row number counter
                    foreach ($students as $student): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <!-- Student name with icon -->
                            <td>
                                <i class="fas fa-user-circle text-primary me-1"></i>
                                <?= htmlspecialchars($student['name']) ?>
                            </td>

                            <td><?= htmlspecialchars($student['email']) ?></td>

                            <!-- Student ID shows as badge -->
                            <td>
                                <span class="badge bg-primary">
                                    <?= htmlspecialchars($student['student_id']) ?>

                                </span>
                            </td>

                            <td><?= htmlspecialchars($student['department']) ?></td>
                            <td>Year<?= $student['year'] ?></td>

                            <td>
                                <!-- Add Marks button: passes student id in URL -->
                                <a href="add_marks.php?student_id=<?=  $student['id'] ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-pen me-1"></i>Add Marks
                                </a>

                                <!-- Delete button: asks confirmation before deleting -->
                                <a href="manage_students.php?delete_id=<?= $student['id'] ?>"
                                    class="btn btn-danger btn-sm ms-1"
                                    onclick="return confirm('Are you sure you wants to delete thos student?')">
                                    <i class="fas fa-trash me-1"></i>Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                else: ?>
                    <!-- Show this row if no student exist -->
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-inbox me-2"></i>No student found.
                            <a href="add_student.php" class="ms-2">Add one now</a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            
        </table>
    </div>
</div>

<?php require_once '../include/footer.php';?>