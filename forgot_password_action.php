<?php
include 'db_connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? UNION SELECT * FROM admins WHERE email = ? UNION SELECT * FROM vendors WHERE email = ?");
    $stmt->bind_param("sss", $email, $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a reset token (for simplicity, using email hash)
        $token = bin2hex(random_bytes(50)); // Generate a secure random token

        // Here you should store the token in the database with an expiration date (not implemented for simplicity)

        // Prepare the reset link
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $token; // Change to your domain
        
        // Send the email (using mail function; consider using a library for production)
        $subject = "Password Reset Request";
        $message = "Click on the link to reset your password: " . $resetLink;
        $headers = "From: no-reply@yourdomain.com"; // Change to your domain

        if (mail($email, $subject, $message, $headers)) {
            echo "A reset link has been sent to your email.";
        } else {
            echo "Failed to send email. Please try again.";
        }
    } else {
        echo "Email not found in our records.";
    }

    $stmt->close();
}
$conn->close();
?>
