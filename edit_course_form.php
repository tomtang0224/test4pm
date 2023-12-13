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

// Fetch the course details based on the ID
$courseId = $_GET['id'];
// $sql = "SELECT * FROM courses WHERE course_id = $courseId";
// $result = $conn->query($sql);
$escapedCourseId = $conn->real_escape_string($courseId);
$sql = "SELECT * FROM courses WHERE course_id = '$escapedCourseId'";
$result = $conn->query($sql);

// Check if the course exists
if ($result->num_rows !== 1) {
    echo "Course not found.";
    exit();
}

$course = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Course</h2>
        <form action="update_course.php" method="post">
            <div class="form-course">
                <label for="course_name">Course ID:</label>
                <input type="text" name="course_id" value="<?php echo $course['course_id']; ?>">
            </div>
            <div class="form-course">
                <label for="course_name">Course Name:</label>
                <input type="text" class="form-control" name="course_name" value="<?php echo $course['name']; ?>"
                    required>
            </div>
            <br>

            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>