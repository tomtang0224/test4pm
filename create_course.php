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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get course details from the form
    $courseId = $_POST['course_id'];
    $courseName = $_POST['course_name'];

    // Validate input (you may need to add more validation)
    if (empty($courseId) || empty($courseName)) {
        $error_message = "All fields are required.";
    } else {
        // Prevent SQL injection (you may need to use prepared statements)
        $courseId = $conn->real_escape_string($courseId);
        $courseName = $conn->real_escape_string($courseName);

        // Check if the course already exists
        $checkQuery = "SELECT * FROM courses WHERE course_id='$courseId'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            $error_message = "Course with this ID already exists.";
        } else {
            // Insert the new course into the database
            $insertQuery = "INSERT INTO courses (course_id, name) VALUES ('$courseId', '$courseName')";

            if ($conn->query($insertQuery) === TRUE) {
                $success_message = "Course created successfully.";
            } else {
                $error_message = "Error creating course: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Create Course</h2>

        <?php
        // Display success or error messages
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="course_id">Course ID:</label>
                <input type="text" id="course_id" name="course_id" required>
            </div>

            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Course</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
