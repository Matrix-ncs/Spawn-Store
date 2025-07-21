<?php
session_start();
include 'connect.php';


if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_username = $_SESSION['admin_username'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $game_id = (int)$_POST['game_id'];
    $file = $_FILES['file'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'zip') {
        die("Only ZIP files allowed.");
    }

    $game_stmt = $conn->prepare("SELECT name FROM games WHERE game_id = ?");
    $game_stmt->bind_param("i", $game_id);
    $game_stmt->execute();
    $game_stmt->bind_result($game_name);
    $game_stmt->fetch();
    $game_stmt->close();

    $safe_name = preg_replace("/[^a-zA-Z0-9_-]/", "", $game_name);
    $new_file_name = $safe_name . ".zip";
    $target_dir = "downloads/";
    $target_file = $target_dir . $new_file_name;

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        $update = $conn->prepare("UPDATE games SET file_path = ? WHERE game_id = ?");
        $update->bind_param("si", $new_file_name, $game_id);
        $update->execute();
        $update->close();

        $message = "File uploaded and linked successfully.";
    } else {
        $message = "Upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Game File</title>
    <link rel="stylesheet" href="upload.css">
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 20px;
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
            <a class="nav-link" href="about.php">About</a>
            <a class="nav-link" href="support.php">Support</a>

            <div class="profile-dropdown">
                <div class="profile-icon">
                    <img src="img/user.png" alt="Profile">
                    <div class="user-name"><?= htmlspecialchars($admin_username) ?></div>
                </div>
                <div class="profile-menu">
                    <a href="admin_dashboard.php">Dashboard</a>
                </div>
            </div>
        </div>

    </nav>
</header>

<div class="upload-container">
    <h2>Upload ZIP File for a Game</h2>

    <?php if (!empty($message)): ?>
        <p style="color: #ffae42; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="upload_game_file.php" method="post" enctype="multipart/form-data">
        <label for="game">Select Game:</label>
        <select name="game_id" required>
            <?php
            $result = $conn->query("SELECT game_id, name FROM games");
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['game_id'] . '">' . htmlspecialchars($row['name']) . '</option>';
            }
            ?>
        </select>

        <label for="file">Choose ZIP file:</label>
        <input type="file" name="file" accept=".zip" required>

        <button type="submit" name="upload">Upload</button>
    </form>
</div>
</body>
</html>
