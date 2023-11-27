<?php
// Include your database connection
include_once("db_connection.php");

// Check if the user is an admin (you need to implement proper authentication)
$isAdmin = true; // Replace this with your authentication logic

// Check if the user is an admin
if (!$isAdmin) {
    header("Location: dashboard.php"); // Redirect to the dashboard if not an admin
    exit();
}

// Check if the group ID is provided
if (!isset($_GET['id'])) {
    header("Location: dashboard.php"); // Redirect if no group ID is provided
    exit();
}

$groupId = $_GET['id'];

// Fetch group information from the database
$groupSql = "SELECT * FROM groups WHERE id = $groupId";
$groupResult = $conn->query($groupSql);

if ($groupResult->num_rows === 1) {
    $group = $groupResult->fetch_assoc();
} else {
    echo "Group not found.";
    exit();
}

// Handle the form submission for updating group information
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated group information from the form
    // Update the group in the database (you need to implement this)
    $updateSql = "UPDATE groups SET name = '{$_POST['name']}', course_name = '{$_POST['course_name']}', size = {$_POST['size']} WHERE id = $groupId";

    if ($conn->query($updateSql) === TRUE) {
        echo "Group updated successfully";
    } else {
        echo "Error updating group: " . $conn->error;
    }
}

// Handle the form submission for deleting the group
if (isset($_POST['delete'])) {
    // Display a confirmation dialog before deleting
    echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this group?');
            if (confirmDelete) {
                window.location.href = 'delete_group.php?id=$groupId';
            }
        </script>";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group</title>
</head>
<body>
    <h2>Edit Group</h2>
    <form action="edit_group.php?id=<?php echo $groupId; ?>" method="post">
        <label for="name">Group Name:</label>
        <input type="text" name="name" value="<?php echo $group['name']; ?>" required>

        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" value="<?php echo $group['course_name']; ?>" required>

        <label for="size">Size:</label>
        <input type="number" name="size" value="<?php echo $group['size']; ?>" required>

        <button type="submit">Update Group</button>
    </form>

    <form action="edit_group.php?id=<?php echo $groupId; ?>" method="post">
        <!-- Adding a hidden field to check if the delete button was clicked -->
        <input type="hidden" name="delete" value="true">
        <button type="submit" style="color: red;">Delete Group</button>
    </form>
</body>
</html>
