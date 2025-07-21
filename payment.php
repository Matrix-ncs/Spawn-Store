<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
    header("Location: checkout.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

$user_result = $conn->query("SELECT name FROM login_info WHERE id = $user_id");
$user = $user_result->fetch_assoc();
$user_name = $user['name'] ?? 'User';

$payment_message = ''; 
$redirect_after_payment = false; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'] ?? ''; 

    
    if ($payment_method === 'card') {
        
        foreach ($cart_items as $game_id => $game) {
            $conn->query("INSERT INTO orders (user_id, game_id) VALUES ($user_id, $game_id)");

            
            $check = $conn->query("SELECT * FROM my_games WHERE user_id = $user_id AND game_id = $game_id");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO my_games (user_id, game_id) VALUES ($user_id, $game_id)");
            }
        }

        unset($_SESSION['cart']);
        $payment_message = "Payment successful! Your games have been added to your library.";
        $redirect_after_payment = true;
    } else {
        $payment_message = "Payment failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        .payment-method {
            margin-top: 20px;
        }

        .payment-method label {
            display: inline-block;
            margin-right: 20px;
        }

        .payment-fields {
            margin-top: 20px;
        }

        .payment-fields input {
            display: block;
            margin-bottom: 10px;
            padding: 8px;
            width: 100%;
            max-width: 300px;
        }

        .hidden {
            display: none;
        }

        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="spawn"><a style="color: #e17b0dc7; text-decoration:none;" href="home.php" class="store-link">Spawn <span class="store">Store</span></a></div>
        <div class="nav-links">
            <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="about.php" class="nav-link">About</a>
            <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="support.php" class="nav-link">Support</a>
            <div class="profile-dropdown">
                <div class="profile-icon">
                    <img src="img/user.png" alt="Profile">
                    <span class="user-name"><?= htmlspecialchars($user_name) ?></span>
                </div>
                <div class="profile-menu">
                    <a href="profile.php">Profile</a>
                    <a href="My-games.php">My Games</a>
                    <a href="orders.php">Order History</a>
                    <a href="cart.php">My Cart</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="checkout-container">
    <h1>Payment</h1>
    <p>User: <strong><?= htmlspecialchars($user_name) ?></strong></p>
    <h3>Total Amount: $<?= $total_price ?></h3>

    
    <?php if ($payment_message): ?>
        <div class="success-message"><?= $payment_message ?></div>
    <?php endif; ?>

    <form method="POST" id="paymentForm">
        <div class="payment-method">
            
            <label><input type="radio" name="payment_method" value="card" checked> Card</label>
        </div>

        <div class="payment-fields" id="card-fields">
            <input type="text" name="card_name" placeholder="Cardholder Name" required>
            <input type="text" name="card_number" placeholder="Card Number" required>
            <input type="text" name="expiry" placeholder="MM/YY" required>
            <input type="text" name="cvv" placeholder="CVV" required>
        </div>

        <br>
        <button type="submit">Confirm Payment</button>
    </form>
</div>

<script>
    
    <?php if ($redirect_after_payment): ?>
        setTimeout(function() {
            window.location.href = "My-games.php";
        }, 3000);
    <?php endif; ?>
</script>
</body>
</html>
