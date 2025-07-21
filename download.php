<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['game_id'])) {
    die("Missing game ID.");
}

$game_id = (int)$_GET['game_id'];


$stmt = $conn->prepare("
    SELECT g.name FROM my_games mg
    JOIN games g ON mg.game_id = g.game_id
    WHERE mg.user_id = ? AND mg.game_id = ?
");
$stmt->bind_param("ii", $user_id, $game_id);
$stmt->execute();
$stmt->bind_result($game_name);

if ($stmt->fetch()) {
    $stmt->close();

    $file_stmt = $conn->prepare("
        SELECT file_path FROM games WHERE game_id = ?
    ");
    $file_stmt->bind_param("i", $game_id);
    $file_stmt->execute();
    $file_stmt->bind_result($file_path);
    $file_stmt->fetch();
    $file_stmt->close();

    $file = "downloads/" . $file_path;


    if (file_exists($file)) {
        
        $log = $conn->prepare("INSERT INTO downloads (user_id, game_id) VALUES (?, ?)");
        $log->bind_param("ii", $user_id, $game_id);
        $log->execute();
        $log->close();

        
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        flush();
        readfile($file);
        exit();
    } else {
        die("File not found.");
    }

} else {
    die("Unauthorized access.");
}
?>
