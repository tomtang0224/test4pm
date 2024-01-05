<?php
session_start();
include('db_connection.php');

// Redirect to the login page if not logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['role'] != 'TA') {
    header("Location: index.php");
}

// Include your common header, navigation, and other dashboard content
include('ta_dashboard_header.php');
// Include your common footer
include('dashboard_footer.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Groups</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h2><?php echo $_GET['course_id'] ?></h2>

        <form action="create_group_ta.php" method="post">
            <div class="row">
                <div class="col">
                    <label for="course_id">Course ID:</label>
                    <input type="text" class="form-control" id="course_id" name="course_id" value="<?php echo $_GET['course_id'] ?>" readonly required>
                </div>
                <div class="col">
                    <label for="num_groups">Number of Groups:</label>
                    <input type="number" class="form-control" id="num_groups" name="num_groups" min="1" required>
                </div>
                <div class="col">
                    <label for="group_size">Max. Group Members Size:</label>
                    <input type="number" class="form-control" id="group_size" name="group_size" min="1" required>
                </div>

                <div class="col text-centre">
                    <br>
                    <button type="submit" class="btn btn-success btn-sm">Create Groups</button>
                </div>
            </div>
        </form>


        <div class="container mt-5">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Group Name</th>
                        <th>Size</th>
                        <th>Group members</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Assuming you have a function to fetch group details with members
                    function getGroupDetailsWithMembers($groupId)
                    {
                        global $conn;

                        $query = "SELECT groups.*, group_members.user_email, users.username
                          FROM groups
                          LEFT JOIN group_members ON groups.id = group_members.group_id
                          JOIN users ON group_members.user_email = users.email
                          WHERE groups.id = ?";

                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("i", $groupId);
                        $stmt->execute();

                        $result = $stmt->get_result();

                        $groupDetails = array(
                            'id' => null,
                            'course_id' => null,
                            'name' => null,
                            'size' => null,
                            'username' => array(), // Store user emails in an array
                        );

                        while ($row = $result->fetch_assoc()) {
                            $groupDetails['id'] = $row['id'];
                            $groupDetails['course_id'] = $row['course_id'];
                            $groupDetails['name'] = $row['name'];
                            $groupDetails['size'] = $row['size'];

                            if ($row['username'] !== null) {
                                $groupDetails['username'][] = $row['username'];
                            }
                        }

                        return $groupDetails;
                    }

                    // Check if the course ID is provided
                    if (!isset($_GET['course_id'])) {
                        echo "Course ID not provided.";
                        exit();
                    }

                    $courseId = $_GET['course_id'];

                    // Fetch the groups for the given course ID
                    $query = "SELECT * FROM groups WHERE course_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("s", $courseId);
                    $stmt->execute();

                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($group = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $group['name'] . '</td>';
                            echo '<td>' . $group['size'] . '</td>';

                            // Fetch group members for the current group
                            $groupDetails = getGroupDetailsWithMembers($group['id']);

                            // Display group members
                            echo '<td>';
                            if (!empty($groupDetails['username'])) {
                                echo implode(', ', $groupDetails['username']);
                            } else {
                                echo 'No members';
                            }
                            echo '</td>';

                            echo '<td>';
                            echo '<a href="edit_group_form_ta.php?id=' . $group['id'] . '" class="btn btn-primary btn-sm">Edit</a> ';
                            echo '<a href="delete_group_ta.php?course_id=' . $courseId . '&id=' . $group['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">No groups available.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
</body>

</html>