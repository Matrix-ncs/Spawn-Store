<?php
session_start();
include 'connect.php';


$default_username = 'admin1';
$default_password = 'admin';
$hashed_password = password_hash($default_password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("SELECT COUNT(*) FROM admin_info WHERE username = ?");
$stmt->bind_param("s", $default_username);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count == 0) {
    $insert = $conn->prepare("INSERT INTO admin_info (username, password) VALUES (?, ?)");
    $insert->bind_param("ss", $default_username, $hashed_password);
    $insert->execute();
    $insert->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT admin_id, password FROM admin_info WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($admin_id, $hashed_password);
    
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['admin_id'] = $admin_id;
        $_SESSION['admin_username'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid login.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Spawn Store</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('img/bg.jpg');
            background-size: cover;
            background-position: center;
        }
        .checkout-container {
            width: 350px;
            max-width: 90%;
            padding: 40px 30px;
            background-color: rgba(0, 0, 0, 0.85);
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(212, 36, 48, 0.8);
            text-align: center;
        }
        .store-name {
            font-family: 'Apple Chancery', cursive;
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 25px;
        }
        .store-name .spawn {
            color: #e17b0dc7;
        }
        .store-name .store {
            color: rgba(212, 36, 48, 0.9);
        }
        form {
            text-align: left;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #f1f1f1;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 18px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
        }
        button {
            width: 100%;
            font-size: 1.1rem;
        }
        p.error {
            color: #f44336;
            font-weight: bold;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="store-name">
            <span class="spawn">Spawn</span> <span class="store">Store</span>
        </div>
        <h1>Admin Login</h1>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required autofocus>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
