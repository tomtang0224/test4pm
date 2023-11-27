<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Course</title>
    <!-- Include Bootstrap or any other styling framework if needed -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Your navigation bar or header goes here -->

<div class="container">
    <h2>Create New Course</h2>

    <form action="create_course.php" method="post">
        <label for="course_name">Course Name:</label>
        <input type="text" name="course_name" required>

        <!-- Add any additional form fields for specifying the number of groups, etc. -->

        <button type="submit" class="btn btn-primary">Create Course</button>
    </form>

    <!-- You can add additional content or styling here -->

</div>

<!-- Your footer or closing tags go here -->

</body>
</html>
