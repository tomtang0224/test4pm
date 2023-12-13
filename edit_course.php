<?php
    // Include your database connection
    include_once("db_connection.php");
    include("admin_dashboard_header.php");

    // Check if the user is logged in as an admin
    session_start();
    if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    // Fetch all courses
    $sql = "SELECT * FROM courses";
    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Courses</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Courses</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course ID</th>
                    <th>Course Name</th>
                   
                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result->num_rows > 0) {
                        while ($course = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $course['course_id'] . '</td>';
                            echo '<td>' . $course['name'] . '</td>';
                            echo '<td>' . $course['user_email'] . '</td>';
                            echo '<td>';
                            echo '<a href="edit_course_form.php?id=' . $course['course_id'] . '" class="btn btn-primary btn-sm">Edit</a>';
                            echo '<a href="delete_course.php?id=' . $course['course_id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No courses available.</td></tr>';
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
