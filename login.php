<?php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate the input (you may need to add more validation)
    if (empty($email) || empty($password)) {
        header("Location: index.php?error=Both email and password are required.");
        exit();
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

            // Verify the password using password_verify
            if (password_verify($password, $user['password_hash'])) {
                // User is authenticated, set session variables and redirect based on role
                session_start();
                $_SESSION['user_email'] = $email;
                $_SESSION['role'] = $user['role'];
                $_SESSION['username'] = $user['username'];

                // Redirect based on the user's role
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } elseif ($user['role'] === 'teacher') {
                    header("Location: teacher_dashboard.php");
                } elseif ($user['role'] === 'TA') {
                    header("Location: ta_dashboard.php");
                } elseif ($user['role'] === 'student') {
                    header("Location: stu_dashboard.php");
                } else {
                    // Handle other roles if needed
                    header("Location: index.php");
                }

                exit();
            } else {
                // Invalid password
                header("Location: index.php?error=Invalid password.");
                exit();
            }
        } else {
            // User not found
            header("Location: index.php?error=Invalid email.");
            exit();
        }

        // Close the statement
        $stmt->close();

        // Close the database connection
        $conn->close();
    }
}
?>