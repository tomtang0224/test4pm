<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $groupId = $_GET['id'];

    // Fetch group information
    $sql = "SELECT * FROM groups WHERE id = $groupId";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $group = $result->fetch_assoc();
    } else {
        // Redirect to the dashboard if the group is not found
        header("Location: dashboard.php");
        exit();
    }
} else {
    // Redirect to the dashboard if the ID is not provided
    header("Location: dashboard.php");
    exit();
}
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

    <form action="update_group.php" method="post">
        <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
        <label for="group_name">Group Name:</label>
        <input type="text" name="group_name" value="<?php echo $group['name']; ?>" required>
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" value="<?php echo $group['course_name']; ?>" required>
        <label for="size">Size:</label>
        <input type="text" name="size" value="<?php echo $group['size']; ?>" required>
        <button type="submit">Update Group</button>
    </form>

</body>
</html>
