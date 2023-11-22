<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $groupId = $_GET['id'];

    // Delete the group
    $deleteSql = "DELETE FROM groups WHERE id = $groupId";
    
    if ($conn->query($deleteSql) === TRUE) {
        // Also delete group members
        $deleteMembersSql = "DELETE FROM group_members WHERE group_id = $groupId";
        $conn->query($deleteMembersSql);

        echo "Group deleted successfully";
    } else {
        echo "Error deleting group: " . $conn->error;
    }
}

// Redirect back to the dashboard
header("Location: dashboard.php");
exit();
?>
