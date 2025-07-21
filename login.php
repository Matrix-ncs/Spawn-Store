<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eaddress = $_POST['eaddress'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM login_info WHERE eaddress = '$eaddress'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['eaddress'] = $user['eaddress'];
        header('Location: home.php');
        exit();
    } else {
        echo '<div id="passwordPopup" class="popup">
                <div class="popup-content">
                    <p>Email or Password wrong!</p>
                    <button onclick="closePopup()">OK</button>
                </div>
              </div>';
    }
}

require_once 'vendor/autoload.php';

$client = new Google\Client();
$client->setClientId("9306581247-h1o2ckl2vshq9655jfq1gad90oeq4159.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost:8080/game%20store/home.php");

$client->addScope("email");
$client->addScope("profile");

$googleAuthUrl = $client->createAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/spawn store logo.png" alt="Spawn Store"><br>
            <h1><span class="spawn">Spawn</span> <span class="store">Store</span></h1>
        </div>
        <h2>Sign In</h2>
        <form class="logged" method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="eaddress" name="eaddress" required>

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <span class="eye-icon" id="togglePassword">👁</span>
            </div><br>

            <a href="forgot_password.php" class="forgot-password">Forgot password?</a>



            <button type="submit" class="signin-btn">Sign in</button>
        </form>

        <p class="or-text">or sign in with</p>

        <div class="social-login">
            <a href="<?= htmlspecialchars($googleAuthUrl) ?>" class="social-btn-google">
                <img src="img/google.png" alt="Google">
            </a>
        </div>

        <p><a href="signup.php" class="create-account">Create account</a> | <a href="#" class="privacy-policy">Privacy Policy</a></p><br><br>
        <p class="copyright">© 2025, Spawn Store, Inc. All rights reserved.</p>
    </div>

    <style>
        .popup {
            display: none;
            position: fixed;
            zoom: 1.35;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #121212;
            padding: 20px;
            border: red solid 1px;
            box-shadow: 0 0 5px rgba(199, 42, 6, 0.86);
            border-radius: 10px;
            z-index: 1000;
        }
        
        .popup-content {
            text-align: center;
            color: #fff;
            font-weight: bold;
            font-size: small;
        }

        .popup button {
            margin-top: 10px;
            padding: 8px 16px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button:hover {
            background: #0056b3;
        }
    </style>

    <script>
        window.onload = function () {
            const popup = document.getElementById("passwordPopup");
            if (popup) {
                popup.style.display = "block";
            }
        };

        function closePopup() {
            document.getElementById("passwordPopup").style.display = "none";
        }

        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁';
        });
    </script>
</body>
</html>
