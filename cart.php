<?php
session_start();
include 'connect.php';

if (isset($_POST['remove_from_cart'])) {
    $game_id_to_remove = $_POST['game_id'];
    unset($_SESSION['cart'][$game_id_to_remove]);
    header("Location: cart.php?removed_from_cart=true");
    exit();
}

if (isset($_POST['checkout'])) {
    $total_price = 0;
    $already_owned_games = [];

    foreach ($_SESSION['cart'] as $game_id => $game) {
        $total_price += $game['price'];

        if ($game['price'] == 0) {
            $check = $conn->query("SELECT * FROM my_games WHERE user_id = {$_SESSION['user_id']} AND game_id = $game_id");
            if ($check && $check->num_rows > 0) {
                $already_owned_games[] = $game['name'];
            }
        }
    }

    $_SESSION['total_price'] = $total_price;

    if (!empty($already_owned_games)) {
        $_SESSION['popup_games'] = $already_owned_games;
        header("Location: checkout.php?popup=true"); 
        exit();
    } else {
        header("Location: checkout.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="spawn"><a href="home.php" class="store-link">Spawn <span class="store">Store</span></a></div>
            <div class="nav-links">
                
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
            <?php
            
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $game_id => $game_details) {
                    ?>
                    <div class="game-cart">
                        <img src="<?= htmlspecialchars($game_details['image']); ?>" alt="<?= htmlspecialchars($game_details['name']); ?>">
                        <h3><?= htmlspecialchars($game_details['name']); ?></h3>
                        <p><?= htmlspecialchars($game_details['description']); ?></p>
                        <p>Size: <?= htmlspecialchars($game_details['size']); ?></p>
                        <p>Price: $<?= htmlspecialchars($game_details['price']); ?></p>

                        
                        <form method="POST" action="cart.php" style="display:inline;">
                            <button style="background:rgba(212, 36, 48, 0.843);" type="submit" name="checkout" class="atc">Checkout</button>
                        </form>
                        
                        <form method="POST" action="cart.php" style="display:inline;">
                            <input type="hidden" name="game_id" value="<?= $game_id ?>">
                            <button type="submit" name="remove_from_cart" class="atc">Remove from Cart</button>
                        </form>

                        
                    </div>
                    <?php
                }
            } else {
                echo "<p>Your cart is empty.</p>";
            }
            ?>
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
        <br>
    </footer>

    
    <div id="footerPopup" class="footer-popup">
        <div class="popup-content">
            <p id="popupMessage">Game removed from cart successfully!</p>
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

        
        <?php if (isset($_GET['removed_from_cart'])): ?>
            showFooterPopup("Game removed from cart successfully!");
        <?php endif; ?>

        document.getElementById('closePopupBtn').addEventListener('click', function() {
            document.getElementById('footerPopup').style.display = 'none';
        });
    </script>


</body>
</html>
