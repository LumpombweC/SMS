<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }

require_once __DIR__ . '/src/Database.php';
$database = Database::getInstance();
$db = $database->getConnection();

$summaries = $db->query("
  SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, COUNT(e.id) as total_courses,
    SUM(CASE WHEN e.grade IS NOT NULL THEN 1 ELSE 0 END) as graded_courses
    FROM students s
    LEFT JOIN enrollments e ON s.id = e.student_id
    GROUP BY s.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Summary | Student Management System</title>
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
                <a class="nav-item" href="Enrolment.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item active" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <h2>Academic Summary</h2>
            <table>
                <thead>
                    <tr><th>STUDENT NO</th><th>NAME</th><th>ENROLLED COURSES</th><th>GRADED COURSES</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($summaries as $sum): ?>
                    <tr>
                        <td><?= htmlspecialchars($sum['student_no'] ?? '') ?></td>
                        <td><?= htmlspecialchars($sum['name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($sum['total_courses']) ?></td>
                        <td><?= htmlspecialchars($sum['graded_courses']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>