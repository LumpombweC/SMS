<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }

require_once __DIR__ . '/src/Database.php';
$database = Database::getInstance();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    $student_id = $_POST['student_id'] ?? null;
    $course_id = $_POST['course_id'] ?? null;
    if ($student_id && $course_id) {
        $stmt = $db->prepare("INSERT INTO enrollments (student_id, course_id, enrolled_at) VALUES (?, ?, NOW())");
        $stmt->execute([$student_id, $course_id]);
    }
}

$students = $db->query("SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM students")->fetchAll(PDO::FETCH_ASSOC);
$courses = $db->query("SELECT id, course_name FROM courses")->fetchAll(PDO::FETCH_ASSOC);
$enrollments = $db->query("
    SELECT 
        e.id, 
        CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
        c.course_name 
    FROM enrollments e 
    JOIN students s ON e.student_id = s.id 
    JOIN courses c ON e.course_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrolment | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">COLLEGE ADMIN</div>
            <nav class="nav-links">
                <a class="nav-item" href="dashboard.php">Dashboard</a>
                <a class="nav-item" href="Student.php">Students</a>
                <a class="nav-item" href="Courses.php">Courses</a>
                <a class="nav-item active" href="Enrolment.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <h2>Course Enrolment</h2>
            <form method="POST">
                <select name="student_id" required>
                    <option value="">Select Student</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="course_id" required>
                    <option value="">Select Course</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="enroll">Enroll Student</button>
            </form>

            <table style="margin-top:20px;">
                <thead>
                    <tr><th>STUDENT</th><th>ENROLLED COURSE</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['student_name']) ?></td>
                        <td><?= htmlspecialchars($e['course_name']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>