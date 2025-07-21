<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM login_info WHERE id = '$user_id'");
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    
    $conn->query("UPDATE login_info SET name='$name', dob='$dob' WHERE id='$user_id'");
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css?v=1">
</head>
<body>
    <div class="profile-container">
        <h2 style="color: rgba(212, 36, 48, 0.843);">Edit Profile</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($user['dob'] ?? '') ?>" required>

            <button type="submit">Update</button>
        </form>

        <h3>Order History</h3>
        <a href="orders.php">View Orders</a>

        <h3>My Cart</h3>
        <a href="cart.php">Go to Cart</a>

        <a href="home.php">Back to Home</a>
    </div>
</body>
</html>
