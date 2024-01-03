<?php
session_start();

if (isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    // Redirect based on the user's role
    if ($role == 'admin') {
        header("Location: admin_dashboard.php");
    } elseif ($role == 'teacher') {
        header("Location: teacher_dashboard.php");
    } elseif ($role == 'TA') {
        header("Location: ta_dashboard.php");
    } elseif ($role == 'student') {
        header("Location: stu_dashboard.php");
    }
} else {
    // Handle cases where the role is not set
    header("Location: index.php");
}
?>