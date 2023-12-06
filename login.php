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
        include_once("db_connection.php");
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

        // Query to check if the user exists with the provided email
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($query);

        if ($result) { // Check if the query was successful
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password_hash'])) {
                    // User is authenticated, set session variable and redirect
                    session_start();
                    $_SESSION['user_email'] = $email; 
                    $_SESSION['role'] = $user['role'];
                    header("Location: admin_dashboard.php");
                    exit(); // Make sure to exit after sending a header location
                } else {
                    // Invalid password
                    echo "Invalid password.";
                }
            } else {
                // User not found
                echo "User not found.";
            }
        } else {
            // Error in the query
            echo "Error: " . $conn->error;
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
