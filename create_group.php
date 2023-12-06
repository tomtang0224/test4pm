<?php
include("db_connection.php");

   // Check if the user is logged in as an admin
   session_start();
   if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
       header("Location: index.php");
       exit();
   }

   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input
    $courseId = $_POST['course_id'];
    $numGroups = $_POST['num_groups'];
    $groupSize = $_POST['group_size'];

    // Validate input (add more validation as needed)
    if (empty($courseId) || empty($numGroups) || empty($groupSize) || !is_numeric($numGroups) || !is_numeric($groupSize) || $numGroups <= 0 || $groupSize <= 0) {
        echo "Invalid input. Please provide valid values for course ID, number of groups, and max group size.";
    } else {
        include_once("db_connection.php"); // Include your database connection code

        // Assume you have a 'groups' table with columns: id, course_id, group_name, max_group_size
        $tableName = "groups";

        // Generate and insert groups
        for ($i = 1; $i <= $numGroups; $i++) {
            $groupName = "grp" . $i;
            
            // Insert into the database
            $insertQuery = "INSERT INTO $tableName (course_id, name, size) VALUES ('$courseId', '$groupName', '$groupSize')";
            
            if ($conn->query($insertQuery) === FALSE) {
                echo "Error creating group: " . $conn->error;
                break; // Exit the loop if an error occurs
            }
        }

        echo "Groups created successfully.";
        
        // Close the database connection
        $conn->close();
    }
}

?>

<?php
// Redirect back to the dashboard after a delay
header("refresh:1;url=admin_dashboard.php");
exit();
?>
