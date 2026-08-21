<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }

require_once __DIR__ . '/src/Database.php';
$database = Database::getInstance();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_grade'])) {
    $enrollment_id = $_POST['enrollment_id'];
    $grade = $_POST['grade'];
    $stmt = $db->prepare("UPDATE enrollments SET grade = ? WHERE id = ?");
    $stmt->execute([$grade, $enrollment_id]);
}

$enrollments = $db->query("
    SELECT 
        e.id, 
        CONCAT(s.first_name, ' ', s.last_name) AS student_name, 
        c.course_name, 
        e.grade 
    FROM enrollments e
    JOIN students s ON e.student_id = s.id
    JOIN courses c ON e.course_id = c.id
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grades | Student Management System</title>
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
                <a class="nav-item active" href="Grades.php">Grades</a>
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <h2>Grade Management</h2>
            <table>
                <thead>
                    <tr><th>STUDENT</th><th>COURSE</th><th>CURRENT GRADE</th><th>ASSIGN GRADE</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['student_name']) ?></td>
                        <td><?= htmlspecialchars($e['course_name']) ?></td>
                        <td><?= htmlspecialchars($e['grade'] ?? 'N/A') ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="enrollment_id" value="<?= $e['id'] ?>">
                                <input type="text" name="grade" placeholder="e.g. A, B, C" style="width: 80px;" required>
                                <button type="submit" name="save_grade">Save</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>