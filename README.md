# Student Management System

A simple PHP-based student records management system with a login flow for the admin dashboard.

## Recent update

The application entry point now sends visitors directly to the login page:

- [index.php](index.php) redirects to [Login.php](Login.php)
- [index.html](index.html) also redirects to [Login.php](Login.php)

This makes the homepage behave as a landing page for authentication instead of showing a blank screen.

## Admin dashboard

After signing in, administrators are taken to a dedicated dashboard with a welcome header, overview cards for student records, courses, and enrollments, plus quick-action guidance.

## Demo access

Use the following credentials to sign in:

- Username: `admin`
- Password: `admin123`
