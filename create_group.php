<?php
session_start();

// Include your database connection
include_once("db_connection.php"); // Replace with your actual connection file

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $group_name_prefix = $_POST['group_name_prefix'];
    $num_groups = $_POST['num_groups'];
    $group_names = $_POST['group_names'];
    $group_sizes = $_POST['group_sizes'];

    // Validate input
    if (count($group_names) != $num_groups || count($group_sizes) != $num_groups) {
        echo "Invalid input. Please make sure to provide names and sizes for all groups.";
        exit();
    }

    // Create groups based on the input
    for ($i = 0; $i < $num_groups; $i++) {
        $group_name = $group_name_prefix . ($i + 1);
        $group_size = $group_sizes[$i];

        // Create the group
        createGroup($conn, $course_name, $group_name, $group_size);

        echo "Group '$group_name' created successfully with size $group_size!<br>";
    }
}

// Close the database connection
$conn->close();

// Function to create a group
function createGroup($conn, $course_name, $group_name, $group_size) {
    // Insert group information into the groups table
    $sql = "INSERT INTO groups (name, course_name, size) VALUES ('$group_name', '$course_name', $group_size)";
    $conn->query($sql);

    // Get the ID of the last inserted group
    $group_id = $conn->insert_id;

    // Add members to the group in the group_members table
    for ($i = 1; $i <= $group_size; $i++) {
        $member_name = "Member " . $i;
        $sql = "INSERT INTO group_members (group_id, member_name) VALUES ($group_id, '$member_name')";
        $conn->query($sql);
    }
}
?>
