<?php
session_start();
if (!isset($_SESSION['user'])) { header('Location: Login.php'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports | Student Management System</title>
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
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item active" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
                <a class="nav-item" href="oop_demo.php">OOP Demo</a>
            </nav>
        </aside>
        <main class="main-panel">
            <h2>Reports</h2>
            <p>Generate academic reports from the database. For the pure OOP demonstration (in-memory classes, transcripts, GPA), use the <a href="oop_demo.php"><strong>OOP Demo</strong></a> page.</p>
        </main>
    </div>
</body>
</html>
