<?php
    // Include your database connection
    include_once("db_connection.php");

    // Check if the user is logged in as an admin
    session_start();
    if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Group</h2>
        <form action="update_group.php" method="post">
            <input type="hidden" name="group_id" value="<?php echo $group['id']; ?>">
            <div class="form-group">
                <label for="group_name">Group Name:</label>
                <input type="text" class="form-control" name="group_name" value="<?php echo $group['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" class="form-control" name="course_name" value="<?php echo $group['course_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="size">Size:</label>
                <input type="number" class="form-control" name="size" value="<?php echo $group['size']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Group</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
