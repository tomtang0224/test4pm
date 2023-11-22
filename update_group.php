<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupId = $_POST['group_id'];
    $group_name = $_POST['group_name'];
    $course_name = $_POST['course_name'];
    $size = $_POST['size'];

    // Update group information
    $updateSql = "UPDATE groups SET name='$group_name', course_name='$course_name', size='$size' WHERE id=$groupId";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "Group updated successfully";
    } else {
        echo "Error updating group: " . $conn->error;
    }
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
