<?php
include_once("db_connection.php");
include("admin_dashboard_header.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email'])) {
    $email = $_GET['email'];

    // Fetch user details for editing
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
?>

<!-- Your HTML form for editing user details -->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Additional styles as needed -->
</head>

<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form method="POST" action="update_user.php">
            <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" class="form-control">
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="teacher" <?php echo ($user['role'] == 'teacher') ? 'selected' : ''; ?>>Teacher</option>
                    <option value="TA" <?php echo ($user['role'] == 'TA') ? 'selected' : ''; ?>>TA</option>
                    <option value="student" <?php echo ($user['role'] == 'student') ? 'selected' : ''; ?>>Student</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
    $stmt->close();
} else {
    // Handle the case when no email is provided
    echo "Invalid request. Please provide a valid email.";
}
?>
