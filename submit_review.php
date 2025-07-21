<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$game_id = (int)$_POST['game_id'];
$rating = (int)$_POST['rating'];
$comment = trim($_POST['comment']);

if ($rating < 1 || $rating > 5 || empty($comment)) {
    die("Invalid input.");
}


$checkStmt = $conn->prepare("SELECT review_id FROM reviews WHERE user_id = ? AND game_id = ?");
$checkStmt->bind_param("ii", $user_id, $game_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    
    header("Location: My-games.php?already_reviewed=true");
    exit();
}
$checkStmt->close();


$stmt = $conn->prepare("INSERT INTO reviews (user_id, game_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $user_id, $game_id, $rating, $comment);
$stmt->execute();
$stmt->close();

header("Location: My-games.php?review_submitted=true");
exit();
?>
