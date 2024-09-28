<?php
include 'db_connection.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in users, admins, or vendors tables
    $stmt = $conn->prepare("SELECT email, 'user' as role FROM users WHERE reset_token = ?
                            UNION SELECT email, 'admin' as role FROM admins WHERE reset_token = ?
                            UNION SELECT email, 'vendor' as role FROM vendors WHERE reset_token = ?");
    $stmt->bind_param("sss", $token, $token, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Invalid token.");
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];
    $role = $row['role'];  // Get the role to know where to update the password
} else {
    die("No token provided.");
}

// Process the form when the new password is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password based on the user's role
    if ($role == 'user') {
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE email = ?");
    } elseif ($role == 'admin') {
        $stmt = $conn->prepare("UPDATE admins SET password = ?, reset_token = NULL WHERE email = ?");
    } else {
        $stmt = $conn->prepare("UPDATE vendors SET password = ?, reset_token = NULL WHERE email = ?");
    }
    
    $stmt->bind_param("ss", $hashedPassword, $email);

    if ($stmt->execute()) {
        echo "Password has been reset successfully.";
    } else {
        die("Error resetting password: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        <h2>Reset Password</h2>
        <form action="reset.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
            <div class="form-group">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
    <footer>
        &copy; <?php echo date("Y"); ?> Event Management. All Rights Reserved.
    </footer>
</body>
</html>
