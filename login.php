<?php
session_start();
include('db_connection.php');

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on the user's role
        if ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } elseif ($user['role'] == 'teacher') {
            header("Location: teacher_dashboard.php");
        } elseif ($user['role'] == 'student') {
            header("Location: student_dashboard.php");
        }
    } else {
        // Login failed
        echo "Login failed. Please check your username and password.";
    }
}

$conn->close();
?>
<!-- Your HTML login form goes here -->
