<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$alreadyOwned = [];
if (isset($_GET['popup']) && isset($_SESSION['popup_games'])) {
    $alreadyOwned = $_SESSION['popup_games'];
    unset($_SESSION['popup_games']);
}

$user_result = $conn->query("SELECT name FROM login_info WHERE id = $user_id");
$user = $user_result->fetch_assoc();
$user_name = $user['name'] ?? 'User';

$cart_items = $_SESSION['cart'] ?? [];
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $already_owned = [];

    if ($total_price == 0) {
        
        foreach ($cart_items as $game_id => $game) {
            $conn->query("INSERT INTO orders (user_id, game_id) VALUES ($user_id, $game_id)");

            $check = $conn->query("SELECT * FROM my_games WHERE user_id = $user_id AND game_id = $game_id");
            if ($check->num_rows === 0) {
                $conn->query("INSERT INTO my_games (user_id, game_id) VALUES ($user_id, $game_id)");
            } else {
                $already_owned[] = $game['name'];
            }
        }

        unset($_SESSION['cart']);

        if (!empty($already_owned)) {
            $_SESSION['popup_games'] = $already_owned;
            header("Location: checkout.php?popup=true");
        } else {
            header("Location: My-games.php?success=true");
        }
        exit();
    } else {
        
        $_SESSION['cart'] = $cart_items; 
        header("Location: payment.php");
        exit();
    }

}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css">
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
        <h1>Checkout</h1>
        <p>User: <strong><?= htmlspecialchars($user_name) ?></strong></p>
        <?php if (!empty($alreadyOwned)): ?>
            <div class="popup-overlay" id="popup">
                <div class="popup-box">
                    <h3>Already Owned</h3>
                    <p>The following game(s) are already in your library:</p>
                    <p><strong><?= htmlspecialchars(implode(', ', $alreadyOwned)) ?></strong></p>
                    <button onclick="closePopupAndRedirect()">OK</button>
                </div>
            </div>

            <script>
                function closePopupAndRedirect() {
                    document.getElementById('popup').style.display = 'none';
                    window.location.href = 'home.php';
                }
            </script>
        <?php endif; ?>




        <div class="order-list">
            <?php foreach ($cart_items as $game): ?>
                <div class="order-item">
                    <img src="img/<?= htmlspecialchars($game['name']) ?>.jpg" alt="<?= htmlspecialchars($game['name']) ?>">
                    <div>
                        <p><strong><?= htmlspecialchars($game['name']) ?></strong></p>
                        <p>Price: $<?= htmlspecialchars($game['price']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div><br><br>

        <h3>Total: $<?= $total_price ?></h3><br><br>

        <form method="POST">
            <button type="submit" name="place_order">Place Order</button>
        </form>
    </div>
</body>
</html>
