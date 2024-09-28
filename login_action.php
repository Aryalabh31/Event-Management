<?php
session_start();
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $table = ($role == 'user') ? 'users' : (($role == 'admin') ? 'admins' : 'vendors');
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['role'] = $role;
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
}
$conn->close(); // Close connection
?>
