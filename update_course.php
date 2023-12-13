<?php
    // Include your database connection
    include_once("db_connection.php");

    // Check if the user is logged in as an admin
    session_start();
    if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get user input
        $courseId = $_POST['course_id'];
        $courseName = $_POST['course_name'];
        
    
        // Validate input (add more validation as needed)
        if (empty($courseName) || empty($courseId)) {
            echo "Invalid input. Please provide valid values for all fields.";
        } else {
            include_once("db_connection.php"); // Include your database connection code
    
            // Assume you have a 'course' table with columns: id, name, course_member
            $tableName = "courses";
    
            // Update the course in the database
            $updateQuery = "UPDATE $tableName SET name='$courseName' WHERE course_id='$courseId'";
    
            if ($conn->query($updateQuery) === TRUE) {
                echo "Course updated successfully.";
                header("refresh:2;url=admin_dashboard.php");
                exit();
            } else {
                echo "Error updating course: " . $conn->error;
            }
    
            // Close the database connection
            $conn->close();
        }
    }
?>
