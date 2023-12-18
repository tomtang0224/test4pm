<?php
// Include your database connection
include_once("db_connection.php");
include_once("admin_dashboard_header.php");

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $courseId = $_POST['course_id'];
    $userEmails = $_POST['user_emails'];
    $action = $_POST['action'];

    // Validate the input (you may need more validation)
    if (empty($courseId) || empty($userEmails) || empty($action)) {
        $error_message = "All fields are required.";
    } else {
        // Perform the requested action
        if ($action === 'add') {
            // Explode the user emails into an array
            $emailsArray = explode(PHP_EOL, $userEmails);

            foreach ($emailsArray as $userEmail) {
                $userEmail = trim($userEmail); // Remove leading/trailing whitespaces

                // Check if the user is already a member of the course
                $checkQuery = "SELECT * FROM Course_members WHERE Course_id='$courseId' AND User_email='$userEmail'";
                $checkResult = $conn->query($checkQuery);

                if ($checkResult->num_rows == 0) {
                    // Add the user to the course
                    $insertQuery = "INSERT INTO Course_members (Course_id, User_email) VALUES ('$courseId', '$userEmail')";
                    $conn->query($insertQuery);
                }
            }

            $success_message = "Users added to the course successfully.";
        } elseif ($action === 'remove') {
            // Remove the user from the course
            $deleteQuery = "DELETE FROM Course_members WHERE Course_id='$courseId' AND User_email='$userEmails'";
            $conn->query($deleteQuery);

            $success_message = "User removed from the course successfully.";
        }
    }
}

// Fetch available courses
$courseQuery = "SELECT * FROM courses";
$courseResult = $conn->query($courseQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course Members</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Course Members</h2>



        <?php

        include_once("db_connection.php");
        include_once("admin_dashboard_header.php");

        // Check if the user is logged in as an admin
        session_start();
        if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
            header("Location: index.php");
            exit();
        }
        // Display success or error messages
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <!-- Add form to edit course members -->
        <form action="" method="post">
            <div class="form-group">
                <label for="course_id">Select Course:</label>
                <select name="course_id" id="course_id">
                    <?php
                    // Display available courses in the dropdown
                    while ($course = $courseResult->fetch_assoc()) {
                        echo "<option value='{$course['course_id']}'>{$course['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="user_emails">User Emails (one per line):</label>
                <textarea id="user_emails" name="user_emails" rows="5" required></textarea>
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Add to Course</button>
        </form>



        <h3>Course Members</h3>
        <div id="courseMembersContainer">
            <!-- Course members will be dynamically loaded here -->
        </div>


        <!-- Include Bootstrap JS and Popper.js -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>

            // Assuming you have a change event handler for the course dropdown
$('#course_id').change(function () {
    var courseId = $(this).val(); // Get the selected course ID

    console.log("Selected Course ID:", courseId); // Log the selected course ID

    // Perform AJAX request to get course members
    $.ajax({
        type: "GET",
        url: "get_course_members.php",
        data: { course_id: courseId },
        success: function (response) {
            console.log("AJAX Response:", response); // Log the AJAX response

            // Update the content with the received HTML
            $('#courseMembersContainer').html(response);
        },
        error: function (error) {
            console.error("Error fetching course members: ", error);
        }
    });
});

            function removeMember(userEmail) {
                var confirmRemove = confirm("Are you sure you want to remove this member?");
                if (confirmRemove) {
                    // Use AJAX to remove the member without reloading the page
                    $.ajax({
                        type: "POST",
                        url: "edit_course_members.php",
                        data: { user_email: userEmail, course_id: $('#course_id').val(), action: "remove" },
                        success: function (response) {
                            // Reload the page to reflect changes
                            location.reload();
                        },
                        error: function (error) {
                            console.error("Error removing member: ", error);
                        }
                    });
                }
            }

            // Periodically update the course members list every 5 seconds
            setInterval(updateCourseMembers, 5000);

            // Assuming you have a change event handler for the course dropdown
            $('#course_id').change(function () {
                var courseId = $(this).val(); // Get the selected course ID
                // Perform AJAX request to get course members
                $.ajax({
                    type: "GET",
                    url: "get_course_members.php",
                    data: { course_id: courseId },
                    success: function (response) {
                        // Update the content with the received HTML
                        $('#course_members_container').html(response);
                    },
                    error: function (error) {
                        console.error("Error fetching course members: ", error);
                    }
                });
            });

        </script>
</body>

</html>