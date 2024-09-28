<?php
include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Select the table based on role
    $table = ($role == 'user') ? 'users' : (($role == 'admin') ? 'admins' : 'vendors');

    $stmt = $conn->prepare("SELECT email FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a token and update it in the respective table
        $token = bin2hex(random_bytes(32));
        $stmt = $conn->prepare("UPDATE $table SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        if (!$stmt->execute()) {
            die("Error updating token: " . $stmt->error);
        }

        // Reset link with the token
        $resetLink = "http://localhost/EventManagement/reset.php?token=" . urlencode($token);

        // Send reset link via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ajaym4654@gmail.com'; // Your email
            $mail->Password = 'ypkx cpio xgos flbh'; // Your app-specific password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ajaym4654@gmail.com', 'Event Management');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset';
            $mail->Body    = "Click here to reset your password: <a href='$resetLink'>Reset Password</a>";

            $mail->send();
            echo "Password reset instructions have been sent to your email.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found in the selected role.";
    }

    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo"><h1>Event Management</h1></div>
        <div class="header-options">
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="contact_us.html">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Forgot Password</h2>
        <form action="forgot_password.php" method="post">
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                    <option value="vendor">Vendor</option>
                </select>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>
        <p>Remembered your password? <a href="login.php">Login here</a></p>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Event Management. All Rights Reserved.
    </footer>
</body>
</html>
