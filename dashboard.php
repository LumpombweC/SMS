<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: Login.php');
    exit;
}

if (($_SESSION['user']['role'] ?? '') !== 'administrator') {
    header('Location: Login.php');
    exit;
}

require_once __DIR__ . '/src/Database.php';

$message = $_SESSION['flash_message'] ?? 'You are logged in successfully.';
unset($_SESSION['flash_message']);

$username = $_SESSION['user']['username'] ?? 'Administrator';
date_default_timezone_set('Africa/Lusaka');
$dateLabel = date('l, F j, Y');

$database = new Database();
$students = $database->getRecentStudents();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">COLLEGE ADMIN</div>
            <nav class="nav-links" aria-label="Sidebar navigation">
                <a class="nav-item active" href="#">Dashboard</a>
                <a class="nav-item" href="Student.php">Students</a>
                <a class="nav-item" href="Course.php">Courses</a>
                <a class="nav-item" href="Enrolment.php">Enrolment</a>
                <a class="nav-item" href="Grades.php">Grades</a>
                <a class="nav-item" href="AcademicSummary.php">Academic Summary</a>
                <a class="nav-item" href="Reports.php">Reports</a>
                <a class="nav-item" href="Settings.php">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Administrator access</p>
                    <h1>Dashboard</h1>
                </div>
                <div class="topbar-actions">
                    <span class="topbar-pill">Admin </span>
                    <a class="logout-link" href="Login.php?logout=1">Logout</a>
                </div>
            </header>

            <section class="dashboard-content" aria-label="Administrator dashboard content">
            
                <section class="welcome-card">
    <div>
        <h2>Welcome, Administrator</h2>
        <p>Overview of the student records system</p>
    </div>
</section>
                <section class="stats-grid" aria-label="Administrative overview">
                    <article class="stat-card stat-students">
    <p class="stat-label">Students</p>
    <p class="stat-number">0</p>
    <p class="stat-description">Total registered students</p>
</article>
                    <article class="stat-card stat-courses">
    <p class="stat-label">Courses</p>
    <p class="stat-number">0</p>
    <p class="stat-description">Available courses</p>
</article>
                    <article class="stat-card stat-enrolment">
    <p class="stat-label">Enrolment</p>
    <p class="stat-number">0</p>
    <p class="stat-description">Current enrolments</p>
</article>
                </section>

                <section class="panel-card">
                    <div class="panel-heading">
                        <h3>Recent Students</h3>
                        <a href="#">View all</a>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student No</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['studentNo'], ); ?></td>
                                        <td><?php echo htmlspecialchars($student['name'], ); ?></td>
                                        <td><?php echo htmlspecialchars($student['course'], ); ?></td>
                                       
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </section>
        </main>
    </div>
</body>
</html>
