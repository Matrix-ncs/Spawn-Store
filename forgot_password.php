<?php
session_start();
include 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eaddress = $_POST['eaddress'];

    
    $result = $conn->query("SELECT * FROM login_info WHERE eaddress = '$eaddress'");
    $user = $result->fetch_assoc();

    if ($user) {
        
        $reset_code = strval(rand(100000, 999999));
        date_default_timezone_set('Asia/Dhaka');

          
        $expiry_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        

        $conn->query("UPDATE login_info SET reset_code = '$reset_code', reset_expiry = '$expiry_time' WHERE eaddress = '$eaddress'");

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info.spawnstore@gmail.com'; 
            $mail->Password = 'gwimjrolipnhjyve'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('info.spawnstore@gmail.com', 'Spawn Store');
            $mail->addAddress($eaddress);

            $mail->Subject = "Password Reset Code";
            $mail->Body = "Your password reset code is: $reset_code\nThis code will expire in 15 minutes.";

            $mail->send();
            echo "<script>alert('Reset code sent! Check your email.'); window.location='reset_password.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Failed to send email: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Forgot Password</h2>
        <form method="POST">
            <label for="eaddress">Enter your email</label>
            <input type="email" name="eaddress" required>
            <button class="reset" type="submit">Send Reset Code</button>
        </form>
        <button class="back-login1"><a class="back-login2" href="login.php">Back to Login</a></button>
    </div>
</body>
</html>
