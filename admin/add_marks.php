<?php
require_once '../include/auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

$success = "";
$error = "";

// ── Get Student ID from URL ───────────────────────────────────
// When admin clicks "Add Marks" from manage_students.php,
// the student id is passed in the URL like: add_marks.php?student_id=3
$selected_student_id = isset($_GET['student_id']) ? $_GET['student_id'] : "";

// --Handle form submission ---
if($_SERVER['REQUEST_METHOD']=== 'POST'){

    // Get values from the sumitted form
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $marks = $_POST['marks_obtained'];
    $semester = $_POST['semester'];

    // Check if marks already exist for this student + subject + semster
    $check = $pdo->prepare("
        SELECT id FROM marks
        WHERE student_id = ? AND subject_id = ? AND semester = ?
    ");
    $check->execute([$student_id, $subject_id, $semester]);

    if($check->rowCount() > 0){
        // Marks already entered - show error
        $error = "Marks already entered for this subject in semester" . $semester . "!!";

    }else{
        // No duplicate found - insert marks into database
        $stmt = $pdo->prepare("
            INSERT INTO marks (student_id, subject_id, marks_obtained, semester)
            VALUES(?,?,?,?)
        ");
        $stmt->execute([$student_id, $subject_id, $marks, $semester]);
        $success = "Marks added successfully!";

        // Keep the same student selected after saving
        $selected_student_id = $student_id;
    }
}

// --Fetch All Student for Dropdown --
$students = $pdo->query("
    SELECT s.id, u.name, s.student_id
    FROM student s
    JOIN users u ON s.user_id = u.id
    ORDER BY u.name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// --fetch ALL Subjects for Dropdown
$subjects = $pdo->query("
    SELECT * FROM subjects ORDER BY subject_name ASC
")->fetchAll(PDO::FETCH_ASSOC);

// --fetch existing marks for selected student ---
// Only runs if a student is selcted
$existingMarks = [];
if($selected_student_id){
    $markStmt = $pdo->prepare("
        SELECT m.marks_obtained, m.semester, sub.subject_name, sub.subject_code
        FROM marks m
        JOIN subjects sub ON m.subject_id = sub.id
        WHERE m.student_id = ?
        ORDER BY m.semester ASC, sub.subject_name ASC
    ");
    $markStmt->execute([$selected_student_id]);
    $existingMarks = $markStmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<div class="container mt-4">

    <!-- PAge Tittle -->
    <div class="d-flex align-items-center mb-4">
        <!-- Back button to manage student page -->
        <a href="manage_students.php" class="btn btn-outline-primary btn-sm me-3">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <h3 class="fw-bold mb-0">
            <i class="fas fa-pen me-2 text-success"></i>Add Marks
        </h3>
    </div>

    <div class="row">

        <!-- Left Side: Add MArks Form -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-success text-white fw-bold py-3">
                    <i class="fas fa-edit me-2"></i>Enter Marks
                </div>
                
                <div class="card-body p-4">

                    <!-- Show success message after saving marks -->
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>

                    <!-- Show error if duplicate marks found -->
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <!-- Student Dropdown -->
                        <!-- When student changes, page reloads with new student_id in URL -->
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-graduate me-1 text-success"></i>Select Student
                            </label>

                            <select name="student_id" class="form-select" required
                                onchange="this.form.action='add_marks.php?student_id='+this.value; this.form.submit();">
                                <option value="">-- Select Student --</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>"
                                        <?= ($selected_student_id == $student['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($student['name']) ?>
                                        (<?= htmlspecialchars($student['student_id']) ?>)
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <!-- Subject Dropdown -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-book me-1 text-success"></i>Select Subject
                            </label>
                            <select name="subject_id" class="form-select" required>
                                <option value="">-- Select Subject --</option>
                                <?php foreach ($subjects as $subject): ?>
                                    <option value="<?= $subject['id'] ?>">
                                        <?= htmlspecialchars($subject['subject_name']) ?>
                                        (<?= htmlspecialchars($subject['subject_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Semester Drop Down -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-layer-group me-1 text-success"></i>Semenster
                            </label>
                            <select name="semester" class="form-select" required>
                                <option value="">-- Select Semester --</option>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4">Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>
                                <option value="7">Semester 7</option>
                                <option value="8">Semester 8</option>
                            </select>
                        </div>

                        <!-- Marks Input (0 to 100 only) -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-star me-1 text-success"></i>Marks Obtained
                                <span class="text-muted fw-normal">(out of 100)</span>
                            </label>
                            <input
                                type="number"
                                name="marks_obtained"
                                class="form-control"
                                placeholder="e.g. 85"
                                min="0"
                                max="100"
                                step="0.01"
                                required
                            >
                        </div>

                        <!-- Save Button -->
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-save me-2"></i>Save Marks
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: Exisiting Marks Table -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-clipboard-list me-2 text-success"></i>
                    Marks Already Entered

                    <!-- Show count badge only if a student is selected -->
                    <?php if ($selected_student_id): ?>
                        <span class="badge bg-success ms-2"><?= count($existingMarks) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Code</th>
                                <th>Semester</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($existingMarks)>0):
                                $i = 1;
                                foreach ($existingMarks as $marks):

                                    //-- Calculate Grade based on marks --
                                    $m = $marks['marks_obtained'];
                                    if ($m>=75){ $grade = 'A'; $badge = 'success';}
                                    elseif ($m>=65){$grade = 'B'; $badge = 'primary';}
                                    elseif ($m>=55){$grade = 'C'; $badge = 'info';}
                                    elseif ($m >=45){$grade = 'D'; $badge = 'warning';}
                                    else            {$grade = 'F'; $badge = 'danger';}
                                ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= htmlspecialchars($marks['subject_name']) ?></td>

                                        <!-- Subject code shown as grey badge -->
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= htmlspecialchars($marks['subject_code'])  ?>
                                            </span>
                                        </td>

                                        <td>Sem <?= $marks['semester'] ?></td>
                                        <td><strong><?= $marks['marks_obtained'] ?></strong>/100</td>

                                        <!-- Grade shown as colored badge -->
                                        <td>
                                            <span class="badge bg-<?= $badge ?>">
                                                <?= $grade ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;

                            else: ?>
                                <!-- Show message if no marks found -->
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <?= $selected_student_id
                                            ? 'No marks entered for this student yet.'
                                            : 'Selecte a student to view their marks.'
                                        ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        
            <!-- Grade Scale Reference Card -->
            <div class="card border-0 shadow-sm rounded-3 mt-3">
                <div class="card-body py-2 px-4">
                    <small class="text-muted fw-semibold">Grade Scale: &nbsp;
                        <span class="badge bg-success">A 75-100</span> &nbsp;
                        <span class="badge bg-primary">B 65-74</span> &nbsp;
                        <span class="badge bg-info">C 55-64</span> &nbsp;
                        <span class="badge bg-warning text-dark">D 45-54</span> &nbsp;
                        <span class="badge bg-danger">F 0-44</span>
                    </small>
                </div>
            </div>

        </div>
    </div>                           
</div>

<?php require_once '../include/footer.php'; ?>