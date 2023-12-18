<?php
// Include your database connection
include_once("db_connection.php");
// Include PhpSpreadsheet library autoload
require 'path/to/vendor/autoload.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $courseId = $_POST['course_id'];
    $action = $_POST['action'];

    // Validate the input (you may need more validation)
    if (empty($courseId) || empty($action)) {
        $error_message = "All fields are required.";
    } else {
        // Handle file upload
        $uploadDir = "uploads/"; // Create a folder named 'uploads' in your project directory
        $uploadFile = $uploadDir . basename($_FILES['excel_file']['name']);

        if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $uploadFile)) {
            // File uploaded successfully, now process the Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($uploadFile);
            $worksheet = $spreadsheet->getActiveSheet();

            // Process each row in the Excel file
            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);

                $userData = [];
                foreach ($cellIterator as $cell) {
                    $userData[] = $cell->getValue();
                }

                // $userData now contains an array of values for each cell in the current row
                $userEmail = $userData[0]; // Assuming the first cell contains the email

                // Add or remove user based on the action
                if ($action === 'add') {
                    // Add user to the course
                    $insertQuery = "INSERT INTO Course_members (Course_id, User_email) VALUES ('$courseId', '$userEmail')";
                    $conn->query($insertQuery);
                } elseif ($action === 'remove') {
                    // Remove user from the course
                    $deleteQuery = "DELETE FROM Course_members WHERE Course_id='$courseId' AND User_email='$userEmail'";
                    $conn->query($deleteQuery);
                }
            }

            $success_message = "Users added/removed from the course successfully.";
        } else {
            $error_message = "Error uploading the file.";
        }
    }
}

// Fetch available courses
$courseQuery = "SELECT * FROM courses";
$courseResult = $conn->query($courseQuery);

// Fetch course members for the selected course
$courseMembersQuery = "SELECT * FROM Course_members WHERE Course_id='$courseId'";
$courseMembersResult = $conn->query($courseMembersQuery);

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
        // Display success or error messages
        if (isset($success_message)) {
            echo "<p style='color: green;'>$success_message</p>";
        } elseif (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <!-- Add form to edit course members -->
        <form action="" method="post" enctype="multipart/form-data">
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
                <label for="excel_file">Upload Excel File:</label>
                <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls" required>
            </div>
            <input type="hidden" name="action" value="add">
            <button type="submit" class="btn btn-primary">Add to Course</button>
        </form>

        <h3>Course Members</h3>
        <ul>
            <?php
            // Display course members for the selected course
            if ($courseMembersResult->num_rows > 0) {
                while ($member = $courseMembersResult->fetch_assoc()) {
                    echo "<li>{$member['User_email']} <a href='#' onclick='removeMember(\"{$member['User_email']}\")'>Remove</a></li>";
                }
            } else {
                echo "<li>No members in the course.</li>";
            }
            ?>
        </ul>
    </div>

    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include the PhpSpreadsheet library JS -->
    <script src="path/to/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php"></script>

    <script>
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
    </script>
</body>
</html>
