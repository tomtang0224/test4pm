<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management_system"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle group creation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $group_name = $_POST['group_name'];
    $course_name = $_POST['course_name']; // Assuming you have an input field for course name
    $size = $_POST['size']; // Assuming you have an input field for size

    // Insert new group into 'groups' table
    $insertGroupSql = "INSERT INTO groups (name, course_name, size) VALUES ('$group_name', '$course_name', '$size')";
    
    if ($conn->query($insertGroupSql) === TRUE) {
        $lastGroupId = $conn->insert_id;

        // Insert the creator as the first group member
        // $creator = $_SESSION['username'];
        // $insertMemberSql = "INSERT INTO group_members (group_id, member_name) VALUES ('$lastGroupId', '$creator')";
        // $conn->query($insertMemberSql);

        echo "Group created successfully";
    } else {
        echo "Error creating group: " . $conn->error;
    }
}

// Close the database connection
$conn->close();

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
