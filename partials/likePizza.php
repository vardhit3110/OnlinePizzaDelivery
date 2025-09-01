<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '_dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "not_logged_in";
    exit();
}

$userId = $_SESSION['userId'];
$itemId = $_POST['itemId'];

// Validate item existence in the item table
$checkItemSql = "SELECT itemId FROM item WHERE itemId = ?";
$stmt = mysqli_prepare($conn, $checkItemSql);
mysqli_stmt_bind_param($stmt, "i", $itemId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    echo "item_not_found";
    exit();
}

// Check if the user has already liked the item
$checkLikeSql = "SELECT id FROM likes WHERE userId = ? AND itemId = ?";
$stmt = mysqli_prepare($conn, $checkLikeSql);
mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
mysqli_stmt_execute($stmt);
$likeResult = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($likeResult) > 0) {
    // Unlike the item
    $deleteLikeSql = "DELETE FROM likes WHERE userId = ? AND itemId = ?";
    $stmt = mysqli_prepare($conn, $deleteLikeSql);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
    mysqli_stmt_execute($stmt);
    echo "unliked";
} else {
    // Like the item
    $insertLikeSql = "INSERT INTO likes (userId, itemId) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insertLikeSql);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $itemId);
    mysqli_stmt_execute($stmt);
    echo "liked";
}

mysqli_close($conn);
?>
