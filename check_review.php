<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit();
}

if (!isset($_GET['game_id'])) {
    echo "Game ID missing.";
    exit();
}

$game_id = intval($_GET['game_id']);


$stmt = $conn->prepare("SELECT name FROM games WHERE game_id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$stmt->bind_result($game_name);
$stmt->fetch();
$stmt->close();


$review_stmt = $conn->prepare("
    SELECT r.rating, r.comment, u.name 
    FROM reviews r
    JOIN login_info u ON r.user_id = u.id
    WHERE r.game_id = ?
");
$review_stmt->bind_param("i", $game_id);
$review_stmt->execute();
$review_result = $review_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Review</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        .review-wrapper {
            min-width: 1600px;
            max-height: 800px;
            overflow-y: auto;
            margin: 25px auto;
            padding: 30px;
            background: #1e1e1e;
            border-radius: 20px;
            color: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
        }

        .review-wrapper h1 {
            text-align: center;
            font-size: 2.2em;
            margin-bottom: 40px;
            color: orange;
        }

        .review {
            background: #2c2c2c;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            font-size: 1.3em;
        }

        .review img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .review-content {
            flex: 1;
        }

        .review-content h3 {
            margin: 0 0 10px;
            color: #ffa500;
        }

        .stars {
            color: gold;
            font-size: 1.4em;
        }

        .no-review {
            text-align: center;
            font-size: 1.8em;
            color: #ccc;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="spawn">
                <a style="color: #e17b0dc7; text-decoration:none;" href="home.php" class="store-link">
                    Spawn <span class="store">Store</span>
                </a>
            </div>
            <div class="nav-links">
                <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="about.php">About</a>
                <a style="color: rgba(212, 36, 48, 0.843);font-weight: 900;" href="support.php">Support</a>
                <div class="profile-dropdown">
                    <div class="profile-icon">
                        <img src="img/user.png" alt="Profile" class="user-icon">
                        <div class="user-name"><?= htmlspecialchars($_SESSION['username']) ?></div>
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

    <div class="review-wrapper">
        <h1>Reviews for <span style="color: red;"><?= htmlspecialchars($game_name) ?></span></h1>

        <?php if ($review_result->num_rows > 0): ?>
            <?php while ($review = $review_result->fetch_assoc()): ?>
                <div class="review">
                    <img src="img/user.png" alt="User Icon">
                    <div class="review-content">
                        <h3><?= htmlspecialchars($review['name']) ?></h3>
                        <div class="stars">
                            <?= str_repeat("★", $review['rating']) ?>
                            <?= str_repeat("☆", 5 - $review['rating']) ?>
                        </div>
                        <p><?= htmlspecialchars($review['comment']) ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-review">No reviews for this game yet.</p>
        <?php endif; ?>

    </div>
</body>
</html>
