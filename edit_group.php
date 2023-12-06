<?php
    // Include your database connection
    include_once("db_connection.php");

    // Check if the user is logged in as an admin
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    // Fetch all groups
    $sql = "SELECT * FROM groups";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Groups</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Groups</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course Name</th>
                    <th>Size</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($group = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $group['id'] . '</td>';
                            echo '<td>' . $group['name'] . '</td>';
                            echo '<td>' . $group['course_name'] . '</td>';
                            echo '<td>' . $group['size'] . '</td>';
                            echo '<td>';
                            echo '<a href="edit_group_form.php?id=' . $group['id'] . '" class="btn btn-primary btn-sm">Edit</a>';
                            echo '<a href="delete_group.php?id=' . $group['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No groups available.</td></tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
