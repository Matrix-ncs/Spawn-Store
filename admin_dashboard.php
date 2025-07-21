<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_username = $_SESSION['admin_username']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="checkout.css">
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
            padding: 10px 0;
            right: 0;
            z-index: 20;
        }

        .profile-menu a {
            display: block;
            color: rgb(194, 85, 21);
            padding: 8px 20px;
            text-decoration: none;
            font-weight: 600;
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
            flex-direction: column;
            align-items: center;
            color: white;
            font-family: Arial, sans-serif;
        }

        .profile-icon img {
            width: 50px;
            height: 40px;
            border-radius: 50%;
        }

        .user-name {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            color: white;
            user-select: none;
        }

        .dashboard-wrapper {
            margin-top: 50px;
            width: 90%;
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .admin-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            justify-content: space-between;
            margin-top: 130px;
        }

        .admin-actions a {
            background-color: rgba(161, 31, 39, 0.843);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            transition: background 0.3s ease;
        }

        .admin-actions a:hover {
            background-color: rgba(212, 36, 48, 0.843);
        }
    </style>
</head>
<body>
<header>
    <nav>
        <div class="spawn">
            <a href="admin_dashboard.php" style="color: #e17b0dc7; text-decoration:none;">Spawn <span class="store">Store</span></a>
        </div>
        <div class="nav-links">
            <div class="profile-dropdown">
                <div class="profile-icon">
                    <img src="img/user.png" alt="Profile">
                    <div class="user-name"><?= htmlspecialchars($admin_username) ?></div> 
                </div>
                <div class="profile-menu">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="dashboard-wrapper">
    <div class="admin-actions">
        <a href="upload_game_file.php">Upload Game</a>
        <a href="add_game.php">Add Game</a>
        <a href="manage_users.php">Manage Users</a>
        <a href="view_orders.php">View Orders</a>
        <a href="view_reviews.php">View Reviews</a>
        <a href="view_reports.php">View Reports</a>
        <a href="manage_categories.php">Manage Categories</a>
        <a href="manage_promotions.php">Manage Promotions</a>
    </div>
</div>

</body>
</html>
