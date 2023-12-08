<?php
// Include your database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_management_system"; 

$conn = new mysqli($servername, $username, $password, $dbname);
// Create connection


// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Datebase Connected successfully";

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
print_r($coursesResult->fetch_assoc()); // add this line

// Fetch the groups for each course
$groups = array();

while ($course = $coursesResult->fetch_assoc()) {
   // $courseId = $course['course_id'];
    //$groupsQuery = "SELECT * FROM groups WHERE course_id='$courseId'";
    $groupsQuery = "SELECT * FROM groups";
    $groupsResult = $conn->query($groupsQuery);
    print_r($groupsResult->fetch_assoc());

    while ($group = $groupsResult->fetch_assoc()) {
        // Check if the group is not full
        $groupId = $group['group_id'];
        $groupMembersQuery = "SELECT COUNT(*) as count FROM group_members WHERE group_id='$groupId'";
        $groupMembersResult = $conn->query($groupMembersQuery);
        $groupMembersCount = $groupMembersResult->fetch_assoc()['count'];

        // Include the member count in the group array
        $group['current_members'] = $groupMembersCount;

        if ($groupMembersCount < $group['size']) {
            $groups[] = $group;
        }
    }
}

print_r($groups);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grouping</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include custom CSS -->
    <style>
        /* Add your custom CSS styles here */
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Available Groups</h2>
        <div class="row">
            <?php foreach ($groups as $group): ?>
                <?php print_r($group);?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $group['name']; ?></h5>
                            <p class="card-text">Course: <?php echo $group['course_id']; ?></p>
                            <p class="card-text">Group Size: <?php echo $group['size']; ?></p>
                            <p class="card-text">Current Members: <?php echo $group['current_members']; ?></p>
                            <form action="join_group.php" method="post">
                                <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
                                <button type="submit" class="btn btn-primary" <?php echo ($group['current_members'] >= $group['size']) ? 'disabled' : ''; ?>>
                                    Join Group
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>