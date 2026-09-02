<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Student Management System</title>
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
                <a class="nav-item" href="Enrolments.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item active" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <h2>System Settings</h2>
            <p>Logged in as: <strong><?= htmlspecialchars($_SESSION['user']['username'] ?? 'Administrator') ?></strong></p>
            <p><a href="Login.php" style="color:red;">Logout</a></p>
        </main>
    </div>
</body>
</html>