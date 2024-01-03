<?php
// Include your database connection
include_once("db_connection.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'TA') {
    header("Location: index.php");
    exit();
}

// Check if the group ID is provided
if (!isset($_GET['id'])) {
    echo "Group ID not provided.";
    exit();
}

// Fetch the group details based on the ID
$groupId = $_GET['id'];
$courseID = $_GET['course_id'];
$sql = "SELECT * FROM groups WHERE id = $groupId";
$result = $conn->query($sql);

// Check if the group exists
if ($result->num_rows !== 1) {
    echo "Group not found.";
    exit();
}

// Delete the group from the database
$deleteSql = "DELETE FROM groups WHERE id = $groupId";

if ($conn->query($deleteSql) === TRUE) {
    echo "Group deleted successfully";
} else {
    echo "Error deleting group: " . $conn->error;
}

// Redirect back to the edit group form
header("Location: group_manage_ta.php?course_id=" . urlencode($courseID));
exit();
