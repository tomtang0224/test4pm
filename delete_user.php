<?php
// Include your database connection
include_once("db_connection.php");
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Check if the email parameter is set in the URL
if (isset($_GET['email'])) {
    // Get the email from the URL
    $userEmail = $_GET['email'];

    // Delete or update associated records in course_members
$deleteCourseMembersQuery = "DELETE FROM course_members WHERE User_email = '$userEmail'";
$conn->query($deleteCourseMembersQuery);


    // Perform the delete operation
    $deleteQuery = "DELETE FROM users WHERE email = '$userEmail'";
    
    if ($conn->query($deleteQuery) === TRUE) {
        echo "User deleted successfully";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "User email not provided";
}

//You may want to redirect the user to a different page after deletion
header("Location: admin_dashboard.php");
exit();
?>
