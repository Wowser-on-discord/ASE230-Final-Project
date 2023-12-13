<?php
session_start();

include('../data/db.php');

// Fetch user_ID by the session username
$stmt = $db->prepare("SELECT ID FROM users WHERE username = :username");
$stmt->bindParam(':username', $_SESSION['userID']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

$user_ID = $user['ID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postID'], $_POST['replyText'])) {
    $postId = $_POST['postID'];
    $replyText = htmlspecialchars($_POST['replyText']);

    // Insert the reply into the database
    $stmt = $db->prepare("INSERT INTO replies (user_ID, postID, Content) VALUES (:user_ID, :postID, :content)");
    $stmt->bindParam(':user_ID', $user_ID);
    $stmt->bindParam(':postID', $postId);
    $stmt->bindParam(':content', $replyText);
    
    if ($stmt->execute()) {
        // Redirect back to the original page or do something else
        header("Location: fypsql.php");
        exit();
    } else {
        echo "Error inserting reply.";
    }
} else {
    echo "Invalid request.";
    exit;
}
?>
