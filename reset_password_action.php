<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Here, validate the token and check for expiration (not implemented for simplicity)

    if ($newPassword === $confirmPassword) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database for the corresponding user
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = (SELECT email FROM password_resets WHERE token = ?)");
        $stmt->bind_param("ss", $hashedPassword, $token);

        if ($stmt->execute()) {
            echo "Password has been reset successfully.";
        } else {
            echo "Failed to reset password. Please try again.";
        }

        $stmt->close();
    } else {
        echo "Passwords do not match.";
    }
}
$conn->close();
?>
