<?php
session_start();
include 'connect.php';

$games = $conn->query("SELECT * FROM games");

if ($games->num_rows == 0) {
    $no_games = true;
} else {
    $no_games = false;
}


if (isset($_POST['add_to_cart'])) {
    

    $game_id = $_POST['game_id']; 
    $game_name = $_POST['game_name'];
    $game_image = $_POST['game_image'];
    $game_description = $_POST['game_description'];
    $game_size = $_POST['game_size'];
    $game_price = $_POST['game_price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][$game_id] = [
        'name' => $game_name,
        'image' => $game_image,
        'description' => $game_description,
        'size' => $game_size,
        'price' => $game_price,
    ];

    header("Location: home.php?added_to_cart=true");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <style>
        .profile-dropdown {
            position: relative;
            display: inline-block;
            z-index: 10;
        }

        .profile-menu {
            display: none;
            position: absolute;
            background-color: #222;
            min-width: 180px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 10px;
            right: 0;
            z-index: 20;
        }
        
        .profile-menu a {
            display: block;
            color: white;
            padding: 8px;
            text-decoration: none;
            
            color: rgb(194, 85, 21);
        }

        .profile-menu a:hover {
            background-color: #444;
            color: rgb(244, 104, 22);
        }

        .profile-dropdown:hover .profile-menu {
            display: block;
        }

        .profile-icon {
            cursor: pointer;
            display: flex;
            color: white;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-icon img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
        .user-name {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
            color: white;
        }
        .review-btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 5px;
            margin-left: 5px;
        }
        .review-btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <header>
        <nav>
            <div class="spawn"><a href="home.php" class="store-link">Spawn <span class="store">Store</span></a></div>
            <div class="nav-links">
                <input type="text" class="search-bar" id="gameSearch" placeholder="Search games...">
                <a href="#" class="nav-link">About</a>
                <a href="#" class="nav-link">Support</a>
                <?php if (isset($_SESSION['user_id'])): 
                    $user_id = $_SESSION['user_id'];
                    $result = $conn->query("SELECT name FROM login_info WHERE id = '$user_id'");
                    $user = $result->fetch_assoc();
                ?>
                <div class="profile-dropdown">
                    <div class="profile-icon">
                        <img src="img/user.png" alt="User" class="user-icon"> 
                        <span class="user-name"><?= htmlspecialchars($user['name'] ?? 'User'); ?></span>
                    </div>
                    <div class="profile-menu">
                        <a href="profile.php">My Profile</a>
                        <a href="My-games.php">My Games</a>
                        <a href="orders.php">Order History</a>
                        <a href="cart.php">My Cart</a>
                        <a href="logout.php">Sign Out</a>
                    </div>
                </div>
                <?php else: ?>
                    <button class="signin"><a href="login.php">Sign In</a></button>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <section class="game-carts">
            <?php if ($no_games): ?>
                <p>No games available right now. Please check back later.</p>
            <?php else: ?>
                <?php while ($game = $games->fetch_assoc()): ?>
                <div class="game-cart" data-name="<?= strtolower(htmlspecialchars($game['name'])) ?>">
                    <img src="img/<?= htmlspecialchars($game['name']) ?>.jpg" alt="<?= htmlspecialchars($game['name']) ?>">
                    <h3><?= htmlspecialchars($game['name']) ?></h3>
                    <p><?= htmlspecialchars($game['description']) ?></p>
                    <p>Size: <?= htmlspecialchars($game['size']) ?></p>
                    <p>Price: $<?= htmlspecialchars($game['price']) ?></p>

                    
                    <form method="POST" action="home.php">
                        <input type="hidden" name="game_id" value="<?= $game['game_id'] ?>">
                        <input type="hidden" name="game_name" value="<?= htmlspecialchars($game['name']) ?>">
                        <input type="hidden" name="game_image" value="img/<?= htmlspecialchars($game['name']) ?>.jpg">
                        <input type="hidden" name="game_description" value="<?= htmlspecialchars($game['description']) ?>">
                        <input type="hidden" name="game_size" value="<?= htmlspecialchars($game['size']) ?>">
                        <input type="hidden" name="game_price" value="<?= htmlspecialchars($game['price']) ?>">
                        <button 
                            type="submit" 
                            name="add_to_cart" 
                            class="atc <?= isset($_SESSION['user_id']) ? '' : 'guest' ?>"
                        >Add to Cart</button>
                    </form>
                    <!-- ✅ Check Review Button -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="check_review.php?game_id=<?= $game['game_id'] ?>">
                            <button class="review-btn">Check Review</button>
                        </a>
                    <?php else: ?>
                        <button class="review-btn guest">Check Review</button>
                    <?php endif; ?>

                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>
        
    </main><br>
    

    <footer>
        <div class="social">
            <p>Follow us on social media:</p><br>
            <div class="contacts">
                <a class="fb" href="#"><img src="img/fb.png" alt="Facebook"></a>
                <a class="linkedin" href="#"><img src="img/linkedin.png" alt="Linkedin"></a>
                <a class="instagram" href="#"><img src="img/instagram.png" alt="Instagram"></a>
            </div>
        </div>
        <p class="copy">&copy; 2025 Spawn Store. All Rights Reserved. We provide high-quality games and excellent customer support. Our mission is to deliver the best gaming experiences and make gaming accessible to everyone, everywhere. Stay tuned for new game releases and exciting offers!</p>
        <br></footer>

    
    <div id="footerPopup" class="footer-popup">
        <div class="popup-content">
            <p id="popupMessage">Game added to cart successfully!</p>
            <button id="closePopupBtn" class="close-popup-btn">Close</button>
        </div>
    </div>


    <script>
        
        function showFooterPopup(message) {
            const popup = document.getElementById('footerPopup');
            const popupMessage = document.getElementById('popupMessage');
            popupMessage.textContent = message;  
            popup.style.display = 'block';
            setTimeout(function() {
                popup.style.display = 'none'; 
            }, 3000); 
        }

        
        document.getElementById('closePopupBtn').addEventListener('click', function() {
            document.getElementById('footerPopup').style.display = 'none';
        });

        
        <?php if (isset($_GET['added_to_cart'])): ?>
            showFooterPopup("Game added to cart successfully!");
        <?php endif; ?>

        document.querySelectorAll('.atc.guest').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                showFooterPopup("Please sign in first to add games to your cart.");
                setTimeout(() =>  2500);
            });
        });
        document.querySelectorAll('.review-btn.guest').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                showFooterPopup("Please sign in to check reviews.");
                setTimeout(() =>  2500);
            });
        });

    </script>

    <script>
        document.getElementById('gameSearch').addEventListener('input', function () {
            const searchQuery = this.value.toLowerCase();
            const games = document.querySelectorAll('.game-cart');

            games.forEach(game => {
                const gameName = game.getAttribute('data-name');
                if (gameName.startsWith(searchQuery)) {
                    game.style.display = 'block';
                } else {
                    game.style.display = 'none';
                }
            });
        });
    </script>
    
     
</body>
</html>
