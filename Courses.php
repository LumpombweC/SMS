<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'administrator') {
    header('Location: Login.php');
    exit;
}

require_once __DIR__ . '/src/Database.php';
$database = Database::getInstance();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
  $code = trim($_POST['course_code'] ?? '');
$name = trim($_POST['course_name'] ?? '');

if ($code && $name) {
    $db = $database->getConnection();
    $stmt = $db->prepare("INSERT INTO courses (course_code, course_name) VALUES (?, ?)");
    $stmt->execute([$code, $name]);
}
    }


// Fetch courses
$db = $database->getConnection();
$courses = $db->query("SELECT * FROM courses ORDER BY course_code")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Courses | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">COLLEGE ADMIN</div>
            <nav class="nav-links">
                <a class="nav-item" href="dashboard.php">Dashboard</a>
                <a class="nav-item" href="Student.php">Students</a>
                <a class="nav-item active" href="Courses.php">Courses</a>
                <a class="nav-item" href="Enrolment.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <h2>Course Management</h2>
            
            <!-- Add Course Form -->
            <form method="POST" style="margin-bottom: 20px;">
                <input type="text" name="course_code" placeholder="Course Code (e.g. CS101)" required>
                <input type="text" name="course_name" placeholder="Course Name" required>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Law">Law</option>
                    <option value="Social Work">Social Work</option>
                    <option value="Business Enterprise">Business Enterprise</option>
                </select>
                <button type="submit" name="add_course">Add Course</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>NAME</th>
                        <th>DEPARTMENT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['course_code'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['course_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['department'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>