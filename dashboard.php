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

$message = $_SESSION['flash_message'] ?? 'You are logged in successfully.';
unset($_SESSION['flash_message']);

$username = $_SESSION['user']['username'] ?? 'Administrator';
$dateLabel = date('l, F j, Y');
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
    <div class="page-shell dashboard-shell">
        <div class="dashboard-card">
            <header class="dashboard-header">
                <div>
                    <p class="eyebrow">Administrator access</p>
                    <h1>Admin Dashboard</h1>
                    <p class="subtitle">Welcome back, <?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>. Today is <?php echo htmlspecialchars($dateLabel, ENT_QUOTES, 'UTF-8'); ?>.</p>
                </div>
                <a class="logout-link" href="Login.php?logout=1">Log out</a>
            </header>

            <div class="dashboard-message">
                <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <section class="stats-grid" aria-label="Administrative overview">
                <article class="stat-card">
                    <h2>Student Records</h2>
                    <p class="stat-number">120</p>
                    <p class="stat-caption">Active student profiles tracked</p>
                </article>
                <article class="stat-card">
                    <h2>Courses</h2>
                    <p class="stat-number">18</p>
                    <p class="stat-caption">Available academic offerings</p>
                </article>
                <article class="stat-card">
                    <h2>Enrollments</h2>
                    <p class="stat-number">94</p>
                    <p class="stat-caption">Students currently enrolled</p>
                </article>
            </section>

            <section class="dashboard-actions" aria-label="Administrator quick actions">
                <h2>Quick actions</h2>
                <ul>
                    <li>Review new student applications</li>
                    <li>Monitor academic performance reports</li>
                    <li>Manage course assignments</li>
                </ul>
            </section>
        </div>
    </div>
</body>
</html>
