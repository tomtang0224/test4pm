<?php
include_once("db_connection.php");

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Prepare and execute the query to update the user
    $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE email = ?");
    $stmt->bind_param("sss", $username, $role, $email);

    if ($stmt->execute()) {
        // User updated successfully
        echo "User updated successfully.";
        header("refresh:2;url=admin_dashboard.php");
    } else {
        // Error during update
        echo "Error during update: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
