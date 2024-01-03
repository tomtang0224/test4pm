<?php
// Include your database connection
include_once("db_connection.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'TA') {
    header("Location: index.php");
    exit();
}


// Assume you have a function to fetch group details by ID
function getGroupDetails($groupId) {
    global $conn;

    $query = "SELECT groups.*, group_members.user_email 
              FROM groups
              LEFT JOIN group_members ON groups.id = group_members.group_id
              WHERE groups.id = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $groupId);
    $stmt->execute();

    $result = $stmt->get_result();
    $groupDetails = $result->fetch_assoc();

    return $groupDetails;
}

// Example usage
$groupId = 1; // Replace with the actual group ID
$groupDetails = getGroupDetails($groupId);

// Print group details and members
print_r($groupDetails);


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get group information from the form
    $groupId = $_POST['group_id'];
    $groupName = $_POST['group_name'];
    $courseID = $_POST['course_ID'];
    $size = $_POST['size'];
    $groupMembers = isset($_POST['group_members']) ? $_POST['group_members'] : array();

    // Validate input (add more validation as needed)
    if (empty($groupName) || empty($courseID) || empty($size)) {
        echo "Invalid input. Please provide valid values for all fields.";
    } else {
        // Update the group in the 'groups' table
        $updateQuery = "UPDATE groups SET name='$groupName', course_id='$courseID', size='$size' WHERE id='$groupId'";
        if ($conn->query($updateQuery) === TRUE) {
            // Delete existing group members
            $deleteMembersQuery = "DELETE FROM group_members WHERE group_id='$groupId'";
            $conn->query($deleteMembersQuery);

            // Insert new group members
            foreach ($groupMembers as $member) {
                $insertMemberQuery = "INSERT INTO group_members (group_id, user_email) VALUES ('$groupId', '$member')";
                $conn->query($insertMemberQuery);
            }

            header("refresh:1;url=group_manage_ta.php?course_id=$courseID");
            exit();
        } else {
            echo "Error updating group: " . $conn->error;
        }
    }
} else {
    // If the form is not submitted, redirect to the index page
    header("Location: index.php");
    exit();
}

// Close the database connection
$conn->close();


?>