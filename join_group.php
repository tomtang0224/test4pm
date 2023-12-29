<?php
session_start();
include('db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'student') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['group_id'])) {
    header("Location: stu_dashboard.php");
    exit();
}

if (!isset($_GET['course_id'])) {
    header("Location: stu_dashboard.php");
    exit();
}

$courseID = $_GET['course_id'];
$groupID = $_GET['group_id'];
$userEmail = $_SESSION['user_email'];

// Add the user as a member of the group
$addMemberQuery = "INSERT INTO group_members (group_id, user_email) VALUES ('$groupID', '$userEmail')";
if ($conn->query($addMemberQuery) === TRUE) {
    // Update the group with the user's email
    $updateGroupQuery = "UPDATE groups SET user_email = '$userEmail' WHERE id = '$groupID'";
    $conn->query($updateGroupQuery);

    // Redirect back to the dashboard with a success message
    header("Location: stu_groups.php?course_id=$courseID");
    exit();
} else {
    // Error occurred while adding the member
    header("Location: stu_dashboard.php");
    exit();
}
