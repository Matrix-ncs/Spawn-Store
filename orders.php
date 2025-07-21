<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT name FROM login_info WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fetched_username);
    if ($stmt->fetch()) {
        $_SESSION['username'] = $fetched_username;
    }
    $stmt->close();
}

$username = $_SESSION['username'];


$query = "
    SELECT g.name, g.description, g.size, g.price, MAX(o.ordered_at) AS ordered_at
    FROM games g 
    JOIN orders o ON g.game_id = o.game_id 
    JOIN my_games mg ON g.game_id = mg.game_id AND mg.user_id = o.user_id
    WHERE o.user_id = ? 
    GROUP BY g.game_id
    ORDER BY ordered_at DESC
";



$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        .order-item {
            background-color: rgba(5, 50, 70, 0.8);
            box-shadow: 0 4px 10px rgba(12, 180, 240, 0.4);
            
        }

        .order-item img {
            border: 3px solid rgba(255, 255, 255, 0.2);
        }

        .order-item h3 {
            color: #ffb347;
        }

        .order-item time {
            font-size: 0.9rem;
            color: #ccc;
        }
        
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="spawn">
                <a style="color: #e17b0dc7; text-decoration:none;" href="home.php" class="store-link">Spawn <span class="store">Store</span></a>
            </div>
            <div class="nav-links">
                <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="about.php" class="nav-link">About</a>
                <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="support.php" class="nav-link">Support</a>
                <div class="profile-dropdown">
                    <div class="profile-icon">
                        <img src="img/user.png" alt="Profile">
                        <div class="user-name"><?= htmlspecialchars($username) ?></div>
                    </div>
                    <div class="profile-menu">
                        <a href="profile.php">My Profile</a>
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
        <h1>Order History</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-item">
                    <img src="img/<?= htmlspecialchars($row['name']) ?>.jpg" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <p><strong>Size:</strong> <?= htmlspecialchars($row['size']) ?></p>
                        <p><strong>Price:</strong> ₹<?= htmlspecialchars($row['price']) ?></p>
                        <p><strong>Purchased Time:</strong> <time><?= htmlspecialchars($row['ordered_at']) ?></time></p>
                        
                        
                        <form method="post" action="invoice.php" style="margin-top: 10px;">
                            <input type="hidden" name="game_name" value="<?= htmlspecialchars($row['name']) ?>">
                            <input type="hidden" name="game_price" value="<?= htmlspecialchars($row['price']) ?>">
                            <input type="hidden" name="purchase_time" value="<?= htmlspecialchars($row['ordered_at']) ?>">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
                            <button type="submit">Get Invoice</button>
                        </form>
                    </div>
                </div><br><br>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have not purchased any games yet.</p>
        <?php endif; ?>

        </div>
    </div>
</body>
</html>
