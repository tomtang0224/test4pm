<?php
// Include your database connection
include_once("db_connection.php");

// Check if the course_id parameter is set in the GET request
if (isset($_GET['course_id'])) {
    // Get the course ID from the GET request
    $courseId = $_GET['course_id'];

    // Fetch course members for the selected course
    $courseMembersQuery = "SELECT * FROM Course_members WHERE Course_id='$courseId'";
    $courseMembersResult = $conn->query($courseMembersQuery);

    // Output course members as an HTML list
    if ($courseMembersResult->num_rows > 0) {
        echo '<ul>';
        while ($member = $courseMembersResult->fetch_assoc()) {
            echo "<li>{$member['User_email']} <a href='#' onclick='removeMember(\"{$member['User_email']}\")'>Remove</a></li>";
        }
        echo '</ul>';
    } else {
        echo "<p>No members in the selected course.</p>";
    }
} else {
    // Output a message if no course is selected
    echo "<p>Please select a course.</p>";
}
?>
