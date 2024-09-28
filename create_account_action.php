<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collecting data and sanitizing input
    $role = $_POST['role'] ?? '';
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($role) || empty($fullName) || empty($email) || empty($password)) {
        die("All fields are required!");
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Determine the table based on role
    $table = ($role == 'user') ? 'users' : (($role == 'admin') ? 'admins' : 'vendors');

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $result = $checkEmail->get_result();

    if ($result->num_rows > 0) {
        die("Email is already registered.");
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO $table (fullName, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit; // Ensure no further code is executed after redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $checkEmail->close();
}
$conn->close(); // Close connection
?>
