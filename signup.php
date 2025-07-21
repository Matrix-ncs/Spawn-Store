<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $eaddress = $_POST['eaddress'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM login_info WHERE eaddress = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $eaddress);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['email_error'] = 'Email is already registered.';
    } else {
        
        $query = "INSERT INTO login_info (name, eaddress, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $eaddress, $password);

        if ($stmt->execute()) {
            header('Location: login.php');
            exit();
        } else {
            echo "<script>alert('Error in registration. Try again!');</script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="login.css">
    
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/spawn store logo.png" alt="Spawn Store"><br>
            <h1><span class="spawn">Spawn</span> <span class="store">Store</span></h1>
        </div>
        <h2>Sign Up</h2>
        <form method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address</label>
            <input type="email" id="eaddress" name="eaddress" required>

            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" required>
                <span class="eye-icon" id="togglePassword1">👁</span>
            </div>

            <label for="cpassword">Confirm Password</label>
            <div class="password-container">
                <input type="password" id="cpassword" name="cpassword" required>
                <span class="eye-icon" id="togglePassword2">👁</span>
            </div><br>

            <button type="submit" class="signin-btn">Sign Up</button>
        </form>

        <p><h5>Already have an account?<a href="login.php" class="signin"> Sign In</a></h5><br>
        <a href="#" class="privacy-policy">Privacy Policy</a></p><br><br>
        <p class="copyright">© 2025, Spawn Store, Inc. All rights reserved.</p>
    </div>

    
    <div id="emailErrorPopup" class="popup" style="display: <?php echo isset($_SESSION['email_error']) ? 'block' : 'none'; ?>;">
        <div class="popup-content">
            <p><?php echo isset($_SESSION['email_error']) ? $_SESSION['email_error'] : ''; ?></p>
            <button onclick="closeEmailPopup()">OK</button>
        </div>
    </div>

    <div id="passwordPopup" class="popup">
        <div class="popup-content">
            <p>Passwords do not match!</p>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

    <style>
        .popup {
            display: none;
            position: fixed;
            zoom: 1.5;
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
        document.querySelector("form").addEventListener("submit", function (event) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("cpassword").value;

            if (password !== confirmPassword) {
                event.preventDefault();
                document.getElementById("passwordPopup").style.display = "block"; 
            }
        });

        function closePopup() {
            document.getElementById("passwordPopup").style.display = "none";
        }

        function closeEmailPopup() {
            document.getElementById("emailErrorPopup").style.display = "none";
            <?php unset($_SESSION['email_error']); ?>
        }

        const passwordInput1 = document.getElementById('password');
        const passwordInput2 = document.getElementById('cpassword');
        const togglePassword1 = document.getElementById('togglePassword1');
        const togglePassword2 = document.getElementById('togglePassword2');

        togglePassword1.addEventListener('click', function () {
            const type = passwordInput1.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput1.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁';
        });

        togglePassword2.addEventListener('click', function () {
            const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput2.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁' : '👁';
        });
    </script>
</body>
</html>
