<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate the input (you may need to add more validation)
    if (empty($email) || empty($username) || empty($password) || empty($role)) {
        $error_message = "All fields are required.";
    } else {
       // include_once("db_connection.php");

        // Assume you have a database connection
        // Replace the following with your actual database connection code
        $db_host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "project_management_system";

        $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prevent SQL injection (you may need to use prepared statements)
        $email = $conn->real_escape_string($email);
        $username = $conn->real_escape_string($username);
        $password_hash = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        // Check if the user already exists
        $check_query = "SELECT * FROM users WHERE email='$email'";
        $check_result = $conn->query($check_query);

        if ($check_result->num_rows > 0) {
            $error_message = "User with this email already exists.";
        } else {
            
            // Insert the new user into the database
            $insert_query = "INSERT INTO users (email, username, password, role, password_hash) 
                             VALUES ('$email', '$username', '$password', '$role', '$password_hash')";
                             echo $insert_query;

            if ($conn->query($insert_query) === TRUE) {
                $success_message = "User created successfully.";
            } else {
                $error_message = "Error creating user: " . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <!-- Add any additional styles or scripts here -->
</head>
<body>

<?php
// Display success or error messages
if (isset($success_message)) {
    echo "<p style='color: green;'>$success_message</p>";
} elseif (isset($error_message)) {
    echo "<p style='color: red;'>$error_message</p>";
}
?>

<form action="" method="post" class="container">
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" required>
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <label for="role">Role:</label>
    <select id="role" name="role" required>
        <option value="admin">Admin</option>
        <option value="teacher">Teacher</option>
        <option value="TA">TA</option>
        <option value="student">Student</option>
    </select>
    <button type="submit" class="btn">Register</button>
</form>

<!-- Add any additional content or links here -->

</body>
</html>
