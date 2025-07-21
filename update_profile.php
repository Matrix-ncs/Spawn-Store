<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$dob = $_POST['dob'];

$conn->query("UPDATE login_info SET name='$name', dob='$dob' WHERE id='$user_id'");
header('Location: profile.php');
exit();
?>
