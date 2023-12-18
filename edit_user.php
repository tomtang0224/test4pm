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

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Users</title>
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
        if ($searchType === 'email') {
            $sql = "SELECT * FROM users WHERE email LIKE '%$searchValue%'";
        } elseif ($searchType === 'username') {
            $sql = "SELECT * FROM users WHERE username LIKE '%$searchValue%'";
        } else {
            // Invalid search type, handle accordingly
            echo "Invalid search type.";
            exit();
        }
    } else {
        // Default SQL query to fetch all users
        $sql = "SELECT * FROM users";
    }

    // Execute the SQL query
    $result = $conn->query($sql);
    ?>

    <div class="container mt-5">
        <h2 class="mb-4">Search User</h2>
        <form method="GET" action="">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="searchType">Search by:</label>
                    <select name="searchType" class="form-control" id="searchType">
                        <option value="email">Email</option>
                        <option value="username">Username</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="searchValue">Search Value:</label>
                    <input type="text" name="searchValue" class="form-control" id="searchValue"
                        placeholder="Enter search value">
                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
            <!-- Add Reset button to clear search filters -->
            <a href="edit_user.php" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Edit Users</h2>

        <!-- Add button to navigate to Create User page -->
        <a href="create_user.php" class="btn btn-success mb-3">Create New User</a>

        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($user = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $user['email'] . '</td>';
                        echo '<td>' . $user['username'] . '</td>';
                        echo '<td>';
                        echo '<a href="edit_user_form.php?email=' . $user['email'] . '" class="btn btn-primary btn-sm">Edit</a>';
                        echo '<a href="delete_user.php?email=' . $user['email'] . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3">No users available.</td></tr>';
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
