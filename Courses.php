<?php
session_start();
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'administrator') {
    header('Location: Login.php');
    exit;
}
require_once __DIR__ . '/src/autoload.php';
$database = Database::getInstance();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
  $code = trim($_POST['course_code'] ?? '');
  $name = trim($_POST['course_name'] ?? '');
  $credits = trim($_POST['credits'] ?? '');
try {
        // 1. Instantiate Course object 
        $course = new Course($code, $name, $credits);

        $db = $database->getConnection();
        $stmt = $db->prepare("INSERT INTO courses (course_code, course_name, credit_hours) VALUES (?, ?, ?)");
        $stmt->execute([
            $course->getCourseCode(),
            $course->getCourseName(),
            $course->getCreditHours()
        ]);

        header('Location: Courses.php');
        exit;

    } catch (InvalidArgumentException $e) {
        // Catch validation errors thrown by Course class setters
        $errors[] = $e->getMessage();
    } catch (PDOException $e) {
        $errors[] = "Database error: " . $e->getMessage();
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
                <a class="nav-item  active" href="Courses.php">Courses</a>
                <a class="nav-item" href="Enrolments.php">Enrolment</a>
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
                <input type="number" name="credits" placeholder="Credit Hours" min="1" required>
                <button type="submit" name="add_course">Add Course</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>CODE</th>
                        <th>NAME</th>
                        <th>CREDIT HOURS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['course_code'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['course_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['credit_hours'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>