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


$result = $conn->query("
    SELECT g.* FROM my_games mg
    JOIN games g ON mg.game_id = g.game_id
    WHERE mg.user_id = $user_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Games</title>
    <link rel="stylesheet" href="checkout.css">
    <style>
        .footer-popup {
            display: none; 
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); 
            background-color: rgb(57, 1, 74); 
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 18px;
            z-index: 1000;
            border-radius: 10px; 
            width: 300px; 
        }

        .footer-popup .popup-content {
            max-width: 100%;
        }

        .footer-popup .close-popup-btn {
            background-color: rgba(161, 31, 39, 0.843);
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin-top: 15px;
        }

        .footer-popup .close-popup-btn:hover {
            background-color: rgba(212, 36, 48, 0.843);
        }
    </style>
</head>
<script>
function openReviewModal(gameId) {
    document.getElementById('reviewModal-' + gameId).style.display = 'flex';
}
function closeReviewModal(gameId) {
    document.getElementById('reviewModal-' + gameId).style.display = 'none';
}
</script>

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
        <h1>My Games</h1>
        <div class="order-list">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="order-item">
                    <img src="img/<?= htmlspecialchars($row['name']) ?>.jpg" alt="<?= htmlspecialchars($row['name']) ?>">
                    <div>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <p><strong>Size:</strong> <?= htmlspecialchars($row['size']) ?></p>
                        <a style="text-decoration: none;" href="download.php?game_id=<?= $row['game_id'] ?>">
                            <button>Download</button>
                        </a>&nbsp;
                        <button style="background-color: green;" onclick="openReviewModal(<?= $row['game_id'] ?>)">Review</button>
                    </div>
                </div>

                
                <div class="popup-overlay" id="reviewModal-<?= $row['game_id'] ?>" style="display:none;">
                    <div class="popup-box">
                        <h3>Leave a Review for <span style="color: orange;"><?= htmlspecialchars($row['name']) ?></span></h3>
                        <form action="submit_review.php" method="post">
                            <input type="hidden" name="game_id" value="<?= $row['game_id'] ?>">
                            <label for="rating">Rating:</label><br><br>
                            <select style="background-color: antiquewhite;" name="rating" required>
                                <option value="">Select Stars</option>
                                <option style="color: red;" value="1">★☆☆☆☆</option>
                                <option style="color: red;" value="2">★★☆☆☆</option>
                                <option style="color: red;" value="3">★★★☆☆</option>
                                <option style="color: red;" value="4">★★★★☆</option>
                                <option style="color: red;" value="5">★★★★★</option>
                            </select><br><br>

                            <label for="comment">Comment:</label><br><br>
                            <textarea style="max-width: fit-content; max-height: max-content; min-width: min-content; min-height: min-content" name="comment" rows="4" cols="40" placeholder="Write your review..." required></textarea><br><br>

                            <button style="background-color: green;" type="submit">Submit Review</button>
                            <button type="button" onclick="closeReviewModal(<?= $row['game_id'] ?>)">Cancel</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>

            
        </div>
    </div>
   
    <div id="footerPopup" class="footer-popup" style="display:none;">
        <div class="popup-content">
            <p id="popupMessage">Review Submitted Successfully!</p>
            <button id="closePopupBtn" class="close-popup-btn">Close</button>
        </div>
    </div>
    <script>
        function showReviewPopup(message) {
            const popup = document.getElementById('footerPopup');
            const popupMessage = document.getElementById('popupMessage');
            popupMessage.textContent = message;
            popup.style.display = 'block';
            setTimeout(function () {
                popup.style.display = 'none';
            }, 3000);
        }

        document.getElementById('closePopupBtn').addEventListener('click', function () {
            document.getElementById('footerPopup').style.display = 'none';
        });

        <?php if (isset($_GET['review_submitted']) && $_GET['review_submitted'] === 'true'): ?>
            showReviewPopup("Review Submitted Successfully!");
        <?php endif; ?>
    </script>

    
    <?php if (isset($_GET['already_reviewed'])): ?>
    <div class="popup-overlay" style="display: flex;">
        <div class="popup-box">
            <h2 style="color: red;">Already Reviewed!</h2>
            <button onclick="this.parentElement.parentElement.style.display='none';">Close</button>
        </div>
    </div>
    <?php endif; ?>




</body>
</html>
