<?php
// Include your database connection
include_once("db_connection.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'TA') {
    header("Location: index.php");
    exit();
}

include_once("ta_dashboard_header.php");
// Fetch the group details based on the ID
$groupId = $_GET['id'];
$sql = "SELECT * FROM groups WHERE id = $groupId";
$result = $conn->query($sql);

// Check if the group exists
if ($result->num_rows !== 1) {
    echo "Group not found.";
    exit();
}

$group = $result->fetch_assoc();

// Fetch existing group members
$membersQuery = "SELECT user_email FROM group_members WHERE group_id = $groupId";
$membersResult = $conn->query($membersQuery);

$existingMembers = array();
while ($member = $membersResult->fetch_assoc()) {
    $existingMembers[] = $member['user_email'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>

</head>

<body>
    <div class="container mt-5">
        <h2>Edit Group</h2>
        <form action="update_group_ta.php" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
            <div class="row">
                <div class="col">
                    <label for="course_name">Course ID:</label>
                    <input type="text" class="form-control" name="course_ID" value="<?php echo $group['course_id']; ?>" readonly required>
                </div>
                <div class="col">
                    <label for="group_name">Group Name:</label>
                    <input type="text" class="form-control" name="group_name" value="<?php echo $group['name']; ?>" required>
                </div>
                <div class="col">
                    <label for="size">Size:</label>
                    <input type="number" class="form-control" name="size" value="<?php echo $group['size']; ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="group_member"><br>Group Members (User Email):</label>
                <select class="form-control" name="group_members[]" size="15" multiple required>
                    <?php
                    // Query to fetch user emails
                    $query = "SELECT email FROM users";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = (in_array($row['email'], $existingMembers)) ? 'selected' : '';
                            echo "<option value='{$row['email']}' $selected>{$row['email']}</option>";
                        }
                    } else {
                        echo "<option value=''>No users found</option>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Group</button>
        </form>
    </div>
</body>

</html>