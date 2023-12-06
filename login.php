<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the input (you may need to add more validation)
    if (empty($email) || empty($password)) {
        echo "Both email and password are required.";
    } else {
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
        $password = $conn->real_escape_string($password);

        // Query to check if the user exists with the provided email and password
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // User is authenticated, set session variable and redirect
            session_start();
            $_SESSION['user_email'] = $email; // Store the user's email in the session
            header("Location: admin_dashboard.php");
            exit(); // Make sure to exit after sending a header location
        } else {
            // Invalid credentials
            echo "Invalid email or password.";
        }

        // Close the database connection
        $conn->close();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: index.php");
    exit();
}

?>
