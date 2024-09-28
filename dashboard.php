<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.html");
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Technical Event Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container text-center mt-5">
        <h1>Welcome to the Dashboard</h1>
        <p>You are logged in as a <strong><?php echo ucfirst($role); ?></strong></p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

<footer>
        <p>&copy; 2024 Event Management. All rights reserved.</p>
    </footer>
</body>
</html>

