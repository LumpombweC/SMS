<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }

// Require all necessary OOP classes in proper order
require_once __DIR__ . '/src/autoload.php';

$database = Database::getInstance();
$db = $database->getConnection();

// Fetch all students from the database
$studentRows = $db->query("SELECT * FROM students ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$studentObjects = [];
$errors = [];

foreach ($studentRows as $sRow) {
    try {
        $fullName = $sRow['first_name'] . ' ' . $sRow['last_name'];
        $programme = $sRow['programme'] ?? 'General';
        $year = (int)($sRow['year_of_study'] ?? 1);

        // Instantiate Student Object
        $student = new Student($fullName, $programme, $year);

        // Override auto-generated student number with the database student number
        $reflector = new ReflectionProperty(Student::class, 'studentNumber');
        $reflector->setAccessible(true);
        $reflector->setValue($student, $sRow['student_number']);

        // Fetch enrolments with joined course details for this student
        $enrolStmt = $db->prepare("
            SELECT c.course_code, c.course_name, c.credit_hours, e.grade 
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.student_id = :student_id
        ");
        $enrolStmt->execute([':student_id' => $sRow['id']]);
        $enrolments = $enrolStmt->fetchAll(PDO::FETCH_ASSOC);

        $gradeMap = ['A' => 90, 'B+' => 78, 'B' => 70, 'C+' => 63, 'C' => 55, 'D' => 45, 'F' => 30];

        foreach ($enrolments as $eRow) {
            $course = new Course($eRow['course_code'], $eRow['course_name'], (int)$eRow['credit_hours']);
            
            // Student::enrol internally instantiates Enrolment
            $student->enrol($course);

            if (!empty($eRow['grade']) && isset($gradeMap[$eRow['grade']])) {
                $student->recordMark($eRow['course_code'], $gradeMap[$eRow['grade']]);
            }
        }

        $studentObjects[] = $student;
    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Summary | Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>

        .trans-wrap { max-width: 1100px; margin: 0 auto; padding: 0 1rem; }
        .trans-header { background: #1e3a5f; color: #fff; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .trans-header h1 { margin: 0 0 0.4rem; font-size: 1.5rem; }
        .trans-header p { margin: 0; opacity: 0.9; }
        .student-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; margin-bottom: 1.5rem; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .student-card h2 { background: #f1f5f9; margin: 0; padding: 0.9rem 1.2rem; font-size: 1.1rem; color: #1e3a5f; }
        .student-meta { padding: 0.6rem 1.2rem; font-size: 0.9rem; color: #475569; border-bottom: 1px solid #e2e8f0; }
        .student-card table { width: 100%; border-collapse: collapse; }
        .student-card th, .student-card td { padding: 0.65rem 1.2rem; text-align: left; border-bottom: 1px solid #f1f5f9; }
        .student-card th { background: #f8fafc; font-weight: 600; color: #334155; }
        .gpa { font-weight: 700; color: #0f766e; }
        .error-box { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.2rem; border-radius: 6px; margin-bottom: 1.5rem; }
        .error-box ul { margin: 0.4rem 0 0; padding-left: 1.2rem; }
        .nav-back { display: inline-block; margin-bottom: 1rem; color: #1e3a5f; text-decoration: none; font-weight: 600; }
        .nav-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">COLLEGE ADMIN</div>
            <nav class="nav-links">
                <a class="nav-item" href="dashboard.php">Dashboard</a>
                <a class="nav-item" href="Student.php">Students</a>
                <a class="nav-item" href="Courses.php">Courses</a>
                <a class="nav-item" href="Enrolments.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item active" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <div class="trans-wrap">
                <a class="nav-back" href="dashboard.php">← Back to Dashboard</a>

                <div class="trans-header">
                    <h1>Student Records </h1>
                    <p>Student Transcript Recods</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="error-box" role="alert">
                        <strong>Exception handling (graceful catch):</strong>
                        <ul>
                            <?php foreach ($errors as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php foreach ($studentObjects as $student): ?>
                    <div class="student-card">
                        <h2><?= htmlspecialchars($student->getFullName()) ?></h2>
                        <div class="student-meta">
                            <strong>Student No:</strong> <?= htmlspecialchars($student->getStudentNumber()) ?>
                            &nbsp;|&nbsp;
                            <strong>Programme:</strong> <?= htmlspecialchars($student->getProgramme()) ?>
                            &nbsp;|&nbsp;
                            <strong>Year:</strong> <?= (int)$student->getYearOfStudy() ?>
                            <?php $gpa = $student->calculateGpa(); ?>
                            <?php if ($gpa !== null): ?>
                                &nbsp;|&nbsp; <span class="gpa">GPA: <?= number_format($gpa, 2) ?></span>
                            <?php endif; ?>
                        </div>
                        <table>
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credit Hours</th>
                                    <th>Mark</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $transcript = $student->getTranscript(); ?>
                                <?php if (!empty($transcript)): ?>
                                    <?php foreach ($transcript as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['courseCode']) ?></td>
                                            <td><?= htmlspecialchars($row['courseName']) ?></td>
                                            <td><?= (int)$row['creditHours'] ?></td>
                                            <td><?= $row['mark'] !== null ? number_format($row['mark'], 1) : '—' ?></td>
                                            <td><strong><?= htmlspecialchars($row['grade'] ?? '—') ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">No courses enrolled yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>