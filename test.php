<?php
// Include your database connection
include("db_connection.php");

// Check if the user is logged in as a student
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}

// Fetch the courses the student is registered for
$userEmail = $_SESSION['user_email'];
$coursesQuery = "SELECT course_id FROM courses WHERE user_email='$userEmail'";
$coursesResult = $conn->query($coursesQuery);

// Check if the query was successful
if ($coursesResult) {
    echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Group ID</th>
                    <th>Group Name</th>
                    <th>Course ID</th>
                    <th>Group Size</th>
                    <th>Current Members</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

    // Fetch all rows, not just one
    while ($course = $coursesResult->fetch_assoc()) {
        $courseId = $course['course_id'];
        $groupsQuery = "SELECT * FROM groups WHERE course_id='$courseId'";
        $groupsResult = $conn->query($groupsQuery);

        while ($group = $groupsResult->fetch_assoc()) {
            // Check if the group is not full
            $groupId = $group['id'];
            $groupMembersQuery = "SELECT COUNT(*) as count FROM group_members WHERE group_id='$groupId'";
            $groupMembersResult = $conn->query($groupMembersQuery);
            $groupMembersCount = $groupMembersResult->fetch_assoc()['count'];

            // Include the member count in the group array
            $group['current_members'] = $groupMembersCount;

            echo '<tr>';
            echo '<td>' . $group['id'] . '</td>';
            echo '<td>' . $group['name'] . '</td>';
            echo '<td>' . $group['course_id'] . '</td>';
            echo '<td>' . $group['size'] . '</td>';
            echo '<td>' . $group['current_members'] . '</td>';
            echo '<td>';
            echo '<form action="join_group.php" method="post">';
            echo '<input type="hidden" name="group_id" value="' . $group['id'] . '">';
            echo '<button type="submit" class="btn btn-primary" ' . (($group['current_members'] >= $group['size']) ? 'disabled' : '') . '>';
            echo 'Join Group';
            echo '</button>';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
    }

    echo '</tbody></table>';
} else {
    echo "Error executing query: " . $conn->error;
}
?>
