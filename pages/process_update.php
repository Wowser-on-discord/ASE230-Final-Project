<?php
session_start();

include('../data/db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_ID'])) {
    echo "No user logged in. You can't create or edit a post!";
    echo '<br>';
    echo '<br>';
    echo '<a href="registersql.php">Register Here</a>';
    exit;
}

// Fetch user_ID by the session user_ID
$user_ID = $_SESSION['user_ID'];

// Check if the post already exists for the user
$stmtCheckPost = $db->prepare("SELECT ID FROM posts WHERE user_ID = :user_ID");
$stmtCheckPost->bindParam(':user_ID', $user_ID);
$stmtCheckPost->execute();
$existingPost = $stmtCheckPost->fetch(PDO::FETCH_ASSOC);

// Handle post creation or update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postButton'])) {
    $postContent = isset($_POST['postContent']) ? htmlspecialchars($_POST['postContent']) : '';

    // Validate and handle file upload
    $fileUpload = '';
    if (!empty($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
        $fileUpload = handleFileUpload($_FILES['fileToUpload']);
    }

    if ($existingPost) {
        // If the post exists, update it
        $stmtUpdate = $db->prepare("UPDATE posts SET Content = :content, fileUpload = :fileUpload WHERE ID = :postID AND user_ID = :user_ID");
        $stmtUpdate->bindParam(':postID', $existingPost['ID']);
    } else {
        // If the post doesn't exist, insert a new one
        $stmtUpdate = $db->prepare("INSERT INTO posts (user_ID, Content, fileUpload) VALUES (:user_ID, :content, :fileUpload)");
    }

    // Bind parameters for both update and insert
    $stmtUpdate->bindParam(':user_ID', $user_ID);
    $stmtUpdate->bindParam(':content', $postContent);
    $stmtUpdate->bindParam(':fileUpload', $fileUpload);

    // Execute the query
    if ($stmtUpdate->execute()) {
        echo $existingPost ? 'Post updated successfully!' : 'Post created successfully!';
    } else {
        echo 'Post creation/update failed: ';
    }
}

// Function to handle file upload securely (implement this function)
function handleFileUpload($file) {
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($file["name"]);

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    } else {
        return ''; // Handle upload failure
    }
}
header("Location: fypsql.php");
exit;
?>
