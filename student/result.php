<?php
require_once '../include/student_auth.php';
require_once '../include/db.php';
require_once '../include/header.php';

// Get logged in student info
$user_id = $_SESSION['user_id'];

// --Fetch student profile from Database
// Join student table users table to get full info
$stmt = $pdo->prepare("
    SELECT u.name, u.email, s.id AS student_db_id,
        s.student_id, s.department, s.year
    FROM users u
    JOIN student s ON u.id = s.user_id
    WHERE u.id = ?
");

$stmt->execute([$user_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$student){
    die("Student profile not found");
}

// --Fetch All marks for this Student 
// Join marks with subjects to get subject name and code
$markStmt = $pdo->prepare("
    SELECT m.marks_obtained, m.semester,
        sub.subject_name, sub.subject_code
    FROM marks m
    JOIN subjects sub ON m.subject_id = sub.id
    WHERE m.student_id = ?
    ORDER BY m.semester ASC, sub.subject_name ASC
");

$markStmt->execute([$student['student_db_id']]);
$allMarks = $markStmt->fetchAll(PDO::FETCH_ASSOC);

// --Calculate overakk GPA--
$totalMarks = 0;
$totalPoints = 0;
$countMarks = count($allMarks);

foreach ($allMarks as $mark){
    $m = $mark['marks_obtained'];
    $totalMarks += $m;

    // Convert marks to GPA points (4.0)
    if($m>=75)  $totalPoints += 4.0;
    elseif ($m >= 65) $totalPoints += 3.0;
    elseif ($m >= 55) $totalPoints += 2.0;
    elseif ($m >= 45) $totalPoints += 1.0;
    else              $totalPoints += 0.0;
}

// Calculate GPA average (avoid division by zero)
$gpa = ($countMarks > 0) ? round($totalPoints / $countMarks, 2) : 0;
$avgMarks = ($countMarks > 0) ? round($totalMarks / $countMarks, 2) : 0;

// --Group Marka by Semester --
// Makes it easier to display marks semester by semester
$marksBySemester = [];
foreach ($allMarks as $mark){
    $sem = $mark['semester'];
    $marksBySemester[$sem][] = $mark;
}
?>

<div class="container mt-4">

    <!-- Page title-->
    <div class="mb-4">
        <h3 class="fw-bold">
            <i class="fas fa-graduation-cap me-2 text-primary"></i>My Results
        </h3>
        <p class="text-muted">View your marks, grades and GPA below.</p>
    </div>

    <!--Student profile card -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center">

                <!--Student avatar icon -->
                <div class="col-md-1 text-center">
                    <div class="bg-primary rounded-circle d-flex align-items-center
                                justify-content-center mx-auto"
                        style="width:60px; height:60px;">
                        <i class="fas fa-user-graduate fa-2x text-white"></i>
                    </div>
                </div>

                <!-- Student details -->
                <div class="col-md-8">
                    <h4 class="fw-bold mb-1">
                        <?= htmlspecialchars($student['name'])  ?>
                    </h4>
                    <p class="text-muted mb-0">
                        <!-- Student ID badge-->
                        <span class="badge bg-primary me-2">
                            <?= htmlspecialchars($student['student_id']) ?>
                        </span>
                        <!-- Department -->
                        <i class="fas fa-building me-1"></i>
                        <?= htmlspecialchars($student['department']) ?>
                        &nbsp;|&nbsp;
                        <!-- Academic year -->
                        <i class="fas fa-calendar me-1"></i>
                        Year <?= $student['year'] ?>
                    </p>

                </div>

                <!-- GPA display on right sice -->
                <div class="col-md-3 text-end">
                    <div class="bg-light rounded-3 p-3 text-center">
                        <p class="text-muted mb-0 small">Overall GPA</p>
                        <!-- Color GPA based on value -->
                        <h2 class="fw-bold mb-0
                            <?= $gpa >= 3 ? 'text-success' : ($gpa >= 2 ? 'text-warning' : 'text-danger') ?>">
                            <?= $gpa ?> / 4.0
                        </h2>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Summary stat card -->
    <div class="row g-4 mb-4">

        <!-- Total Subjecvt -->
        <div class="col-md-4">
            <div class="stat-card blue p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Total Subjects</p>
                        <h2 class="fw-bold mb-0"><?= $countMarks ?></h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-75"></i>
                </div>
            </div>

        </div>

        <!-- Average Marks -->
        <div class="col-md-4">
            <div class="stat-card green p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">Average Marks</p>
                        <h2 class="fw-bold mb-0"><?= $avgMarks ?>%</h2>
                    </div>
                    <i class="fas fa-chart-bar fa-3x opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- GPA -->
        <div class="col-md-4">
            <div class="stat-card orange p-4 rounded-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 opacity-75">GPA (4.0 Scale)</p>
                        <h2 class="fw-bold mb-0"><?= $gpa ?></h2>
                    </div>
                    <i class="fas fa-star fa-3x opacity-75"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Marks by Semester -->
    <?php if (count($marksBySemester) > 0): ?>

        <!-- Loop through each semester -->
        <?php foreach ($marksBySemester as $semester => $semMarks): ?>

            <?php
            // Calculate semester average marks
            $semTotal = array_sum(array_column($semMarks, 'marks_obtained'));
            $semAvg = round($semTotal / count($semMarks), 2);
            ?>

            <!-- Semester Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">

                <!-- Semester header with average -->
                <div class="card-header bg-primary text-white fw-bold py-3
                            d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fas fa-layer-group me-2"></i>Semester <?= $semester ?>
                    </span>
                    <span class="badge bg-white text-primary">
                        Avg: <?= $semAvg ?>
                    </span>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject Name</th>
                                <th>Subject Code</th>
                                <th>Marks</th>
                                <th>Grade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($semMarks as $mark):

                                //--Calculate grade for each subject--
                                $m = $mark['marks_obtained'];
                                if($m >= 75) { $grade = "A"; $badge = "success"; }
                                elseif ($m >= 65) {$grade = "B"; $badge = "primary";}
                                elseif ($m >= 55) {$grade = "C"; $badge = "info";} 
                                elseif ($m >= 45) {$grade = "D"; $badge = "warning";}
                                else              {$grade = "F"; $badge = "danger";}


                                //--Pass for Fail staus --
                                $status = ($m >=45) ? 'Pass' : 'Fail';
                                $statusBadge = ($m >=45) ? 'success' : 'danger';
                            ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($mark['subject_name']) ?></td>

                                    <!--Subject code as grey bedge -->
                                    <td>
                                        <span class="badge bg-secondary">
                                            <?= htmlspecialchars($mark['subject_code']) ?>
                                        </span>
                                    </td>   

                                    <!-- Marks with progress bar -->
                                    <td>
                                        <strong><?= $mark['marks_obtained'] ?></strong>/100
                                        <div class="progress mt-1" style="height: 5px">
                                            <div class="progress-bar bg-<?= $badge ?>"
                                                style="width: <?= $mark['marks_obtained'] ?>%">
                                            </div>
                                        </div>    

                                    </td>

                                    <!-- Grade colored badge -->
                                    <td>
                                        <span class="badge bg-<?= $badge ?>">
                                            <?= $grade ?>
                                        </span>
                                    </td>

                                    <!-- Pass / Fail status -->
                                    <td>
                                        <span class="badge bg-<?= $statusBadge ?>">
                                            <?= $status ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>

        <!-- Show this if no marks entered yet -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-inbox fa-3x mb-3"></i>
                <h5>No marks avaulabke yet.</h5>
                <p>Please contact your admin to enter your marks.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Grade Scale info -->
    <div class="card border-0 shadow-sm rounded-3 mt-2 mb-4">
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

<?php require_once '../include/footer.php';?>