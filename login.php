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

        // Prevent SQL injection (use prepared statements)
        $email = $conn->real_escape_string($email);

        // Query to check if the user exists with the provided email
        $query = "SELECT * FROM users WHERE email=?";
        
        // Prepare the statement
        $stmt = $conn->prepare($query);

        // Bind the parameter
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $users['password_hash'])) {
                // User is authenticated, set session variables and redirect
                session_start();
                $_SESSION['user_email'] = $email;
                $_SESSION['role'] = $user['role'];
                header("Location: admin_dashboard.php");
                exit();
            } else if ($password == $users['password']) {
                    session_start();
                $_SESSION['user_email'] = $email;
                $_SESSION['role'] = $user['role'];
                header("Location: admin_dashboard.php");
            } else {
                // Invalid password

                echo "Invalid password.";
                header("Location: index.php");
            }
        } else {
            // User not found
            echo "User not found.";
        }

        // Close the statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: index.php");
    exit();
}
?>
