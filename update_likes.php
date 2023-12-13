<?php
// Include your database connection file
include('../data/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['postId']) && isset($_POST['action'])) {
        $postId = intval($_POST['postId']);
        $action = $_POST['action'];

        // Assuming $_SESSION['userID'] is already set
        $userID = $_SESSION['userID'];

        // Your database connection file should define $db

        // Check if the user already liked the post
        $stmt = $db->prepare("SELECT * FROM likes WHERE postID = :postID AND user_ID = :userID");
        $stmt->bindParam(':postID', $postId);
        $stmt->bindParam(':userID', $userID);
        $stmt->execute();
        $existingLike = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($action === 'like') {
            // If the user hasn't liked the post, insert a new like
            if (!$existingLike) {
                $stmt = $db->prepare("INSERT INTO likes (user_ID, postID) VALUES (:userID, :postID)");
                $stmt->bindParam(':userID', $userID);
                $stmt->bindParam(':postID', $postId);
                $stmt->execute();
            }
        } elseif ($action === 'unlike') {
            // If the user has liked the post, delete the like
            if ($existingLike) {
                $stmt = $db->prepare("DELETE FROM likes WHERE postID = :postID AND user_ID = :userID");
                $stmt->bindParam(':postID', $postId);
                $stmt->bindParam(':userID', $userID);
                $stmt->execute();
            }
        }

        // Get the updated likes count for the post
        $likesCount = getLikesCount($postId, $db);

        // Send the updated likes count as a response
        echo $likesCount;
    }
}

// Function to get likes count for a post
function getLikesCount($postId, $db) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM likes WHERE postID = :postID");
    $stmt->bindParam(':postID', $postId);
    $stmt->execute();
    return $stmt->fetchColumn();
}
?>
