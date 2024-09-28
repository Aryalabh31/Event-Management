<?php
include 'db_connection.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the token and new password from the form
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password and confirm password match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in the database using the token
    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $hashed_password, $token);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Password updated successfully. You can now <a href='login.php'>login</a>.";
    } else {
        echo "Error updating password. Please ensure your token is valid.";
    }

    $stmt->close();
} else {
    // If the request method is not POST, redirect or show an error
    echo "Invalid request.";
}

$conn->close();
?>
