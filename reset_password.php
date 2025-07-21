<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eaddress = $_POST['eaddress'];
    $reset_code = $_POST['reset_code'];
    $new_password = $_POST['new_password'];
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Check if reset code is valid and not expired
    $result = $conn->query("SELECT * FROM login_info WHERE eaddress = '$eaddress' AND reset_code = '$reset_code' AND reset_expiry > NOW()");

    $user = $result->fetch_assoc();

    if ($user['reset_code'] === $reset_code) {
        // Update the password
        $conn->query("UPDATE login_info SET password = '$hashed_password', reset_code = NULL, reset_expiry = NULL WHERE eaddress = '$eaddress'");
        echo "<script>alert('Password reset successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Invalid or expired reset code!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Reset Password</h2>
        <form method="POST">
            <label for="eaddress">Email Address</label>
            <input type="email" name="eaddress" required>

            <label for="reset_code">Enter Reset Code</label>
            <input type="text" name="reset_code" required>

            <label for="new_password">New Password</label>
            <input type="password" name="new_password" required>

            <button class="reset" type="submit">Reset Password</button>
        </form>
        
        <button class="back-login1"><a class="back-login2" href="login.php">Back to Login</a></button>
    </div>
</body>
</html>
