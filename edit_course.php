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
    <?php
    // Check if the search form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['searchType']) && isset($_GET['searchValue'])) {
        $searchType = $_GET['searchType'];
        $searchValue = $_GET['searchValue'];

        // Define the SQL query based on the search type
        if ($searchType === 'courseId') {
            $sql = "SELECT * FROM courses WHERE course_id LIKE '%$searchValue%'";
        } elseif ($searchType === 'courseName') {
            $sql = "SELECT * FROM courses WHERE name LIKE '%$searchValue%'";
        } else {
            // Invalid search type, handle accordingly
            echo "Invalid search type.";
            exit();
        }
    } else {
        // Default SQL query to fetch all courses
        $sql = "SELECT * FROM courses";
    }

    // Execute the SQL query
    $result = $conn->query($sql);
    ?>

    <div class="container mt-5">
        <h2>Search Course</h2>
        <form method="GET" action="">
            <div class="form-group">
                <label for="searchType">Search by:</label>
                <select name="searchType" class="form-control" id="searchType">
                    <option value="courseId">Course ID</option>
                    <option value="courseName">Course Name</option>
                </select>
            </div>
            <div class="form-group">
                <label for="searchValue">Search Value:</label>
                <input type="text" name="searchValue" class="form-control" id="searchValue"
                    placeholder="Enter search value">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>

            <!-- Add Reset button to clear search filters -->
            <a href="edit_course.php" class="btn btn-secondary">Reset</a>
        </form>
        </div>

        <div class="container mt-5">
            <h2>Edit Courses</h2>

            <!-- Add button to navigate to Create Course page -->
            <a href="create_course.php" class="btn btn-success mb-3">Create New Course</a>

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