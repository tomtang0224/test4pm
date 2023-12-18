<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="admin_dashboard.php">System Project Management (Admin)</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="admin_dashboard.php">Home</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="edit_user.php">User Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="edit_group.php">Grouping</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="task_management.php">Task Management</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="courseDropdown" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Course Management
                </a>
                <!-- Add a dropdown for course management -->
                <div class="dropdown-menu" aria-labelledby="courseDropdown">
                    <a class="dropdown-item" href="edit_course.php">Edit Course</a>
                    <a class="dropdown-item" href="edit_course_member.php">Course Member Management</a>
                    <!-- Add more items as needed -->
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
    </div>
</nav>
