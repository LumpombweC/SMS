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

$username = $_SESSION['user']['username'] ?? 'Administrator';
$database = new Database();
$students = $database->getRecentStudents(10);

if (empty($students)) {
    $students = [
        ['studentNo' => 'ST001', 'name' => 'John Banda', 'course' => 'ICT', 'status' => 'Active'],
        ['studentNo' => 'ST002', 'name' => 'Mary Phiri', 'course' => 'Business', 'status' => 'Active'],
        ['studentNo' => 'ST003', 'name' => 'Peter Zulu', 'course' => 'Accounting', 'status' => 'Active']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand">COLLEGE ADMIN</div>
            <nav class="nav-links" aria-label="Sidebar navigation">
                <a class="nav-item" href="dashboard.php">Dashboard</a>
                <a class="nav-item active" href="Student.php">Students</a>
                <a class="nav-item" href="#">Courses</a>
                <a class="nav-item" href="#">Enrolments</a>
                <a class="nav-item" href="#">Grades</a>
                <a class="nav-item" href="#">Academic Summary</a>
                <a class="nav-item" href="#">Reports</a>
                <a class="nav-item" href="#">Settings</a>
            </nav>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Administrator access</p>
                    <h1>Students</h1>
                </div>
                <div class="topbar-actions">
                    <span class="topbar-pill">Admin ▼</span>
                    <a class="logout-link" href="Login.php?logout=1">Logout</a>
                </div>
            </header>

            <section class="dashboard-content" aria-label="Students management view">
                <div class="welcome-card">
                    <div>
                        <p class="subtitle">Welcome back, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></p>
                        <h2>Manage student records</h2>
                        <p class="muted-text">This page loads student data from the connected database when available.</p>
                    </div>
                    <div class="action-buttons">
                        <a href="#" class="btn btn-primary">+ Add Student</a>
                    </div>
                </div>

                <section class="panel-card">
                    <div class="panel-heading">
                        <h3>Student Records</h3>
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
                                        <td><?php echo htmlspecialchars($student['studentNo'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?php echo htmlspecialchars($student['course'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><span class="status-pill"><?php echo htmlspecialchars($student['status'], ENT_QUOTES, 'UTF-8'); ?></span></td>
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
