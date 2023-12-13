<?php
// This file handles the action of liking or unliking a post

session_start();
include('../data/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postId'])) {
    $postId = intval($_POST['postId']);
    $userID = $_SESSION['user_ID'];

    // Check if the user has already liked the post
    $stmtCheck = $db->prepare("SELECT COUNT(*) FROM likes WHERE postID = :postID AND user_ID = :user_ID");
    $stmtCheck->bindParam(':postID', $postId);
    $stmtCheck->bindParam(':user_ID', $userID);
    $stmtCheck->execute();
    $likeCount = $stmtCheck->fetchColumn();

    if ($likeCount > 0) {
        // If the user has already liked the post, unlike it (delete the like)
        $stmtDelete = $db->prepare("DELETE FROM likes WHERE postID = :postID AND user_ID = :user_ID");
        $stmtDelete->bindParam(':postID', $postId);
        $stmtDelete->bindParam(':user_ID', $userID);
        $stmtDelete->execute();
    } else {
        // If the user hasn't liked the post yet, insert the like
        $stmtInsert = $db->prepare("INSERT INTO likes (postID, user_ID) VALUES (:postID, :user_ID)");
        $stmtInsert->bindParam(':postID', $postId);
        $stmtInsert->bindParam(':user_ID', $userID);
        $stmtInsert->execute();
    }
}

// Redirect
header("Location: fypsql.php");
?>
