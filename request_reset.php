<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $role = $_POST['role'];

    $table = ($role == 'user') ? 'users' : (($role == 'admin') ? 'admins' : 'vendors');

    $stmt = $conn->prepare("SELECT email FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        $resetLink = "http://yourdomain.com/reset.php?token=$token";
        mail($email, "Reset your password", "Click this link to reset your password: $resetLink");

        echo "Password reset instructions have been sent to your email.";
    } else {
        echo "Email not found.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-options">
            <h1>Event Management</h1>
            <nav>
                <ul>
                    <li><a href="home.html">Home</a></li>
                    <li><a href="contact_us.html">Contact Us</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <h2>Request Password Reset</h2>
        <form action="request_reset.php" method="post">
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
            <button type="submit" class="btn">Request Reset</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Event Management</p>
    </footer>
</body>
</html>
