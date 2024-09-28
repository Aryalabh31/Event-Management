<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Reset Password</title>
</head>
<body>

<div class="container">
    <h1>Reset Password</h1>
    <form action="reset_password_action.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <input type="submit" class="btn btn-success" value="Reset Password">
    </form>
</div>

<footer>
        <p>&copy; 2024 Event Management. All rights reserved.</p>
    </footer>
</body>
</html>
