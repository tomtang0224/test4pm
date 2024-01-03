<?php
session_start();
include('db_connection.php');

// Redirect to the login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'TA') {
    header("Location: index.php");
}

// Include your common header, navigation, and other dashboard content
include('ta_dashboard_header.php');
// Include your common footer
include('dashboard_footer.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        <h2>Courses</h2>

        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "project_management_system";
        $email = $_SESSION['user_email'];

        $conn = new mysqli($servername, $username, $password, $dbname);

        $searchType = $_GET['searchType'] ?? '';
        $searchValue = $_GET['searchValue'] ?? '';

        $sql = "SELECT cm.Course_id, c.name FROM course_members cm
                INNER JOIN courses c ON cm.Course_id = c.course_id
                WHERE cm.User_email = '$email'";

        if (!empty($searchValue)) {
            if ($searchType === 'courseId') {
                $sql .= " AND c.course_id = '$searchValue'";
            } elseif ($searchType === 'courseName') {
                $sql .= " AND c.name = '$searchValue'";
            }
        }

        $result = $conn->query($sql);
        ?>

        <form method="GET" action="ta_dashboard.php">
            <div class="form-row align-items-center mb-3">
                <div class="col-auto">
                    <select class="form-control" name="searchType">
                        <option value="courseId">Course ID</option>
                        <option value="courseName">Course Name</option>
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" class="form-control" name="searchValue" placeholder="Search">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-auto">
                    <a href="ta_dashboard.php" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

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
                        echo '<td>' . $course['Course_id'] . '</td>';
                        echo '<td>' . $course['name'] . '</td>';
                        echo '<td><a href="group_process_ta.php?course_id=' . $course['Course_id'] . '" class="btn btn-primary btn-sm">View Groups</a> ';
                        echo '<a href="group_manage_ta.php?course_id=' . $course['Course_id'] . '" class="btn btn-primary btn-sm">Manage Groups</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No courses available.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>