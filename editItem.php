<?php
session_start();

// Include your database connection file
include('../data/db.php');

// Ensure user is logged in
if (!isset($_SESSION['user_ID'])) {
    echo "No user logged in. You can't edit a post!";
    echo '<br>';
    echo '<br>';
    echo '<a href="registersql.php">Register Here</a>';
    exit;
}

// Fetch user_ID by the session user_ID
$user_ID = $_SESSION['user_ID'];

// Check if an edit request is made
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);

    // Fetch post data by post ID
    $stmtPost = $db->prepare("SELECT ID, user_ID, Content, fileUpload FROM posts WHERE ID = :post_id AND user_ID = :user_ID");
    $stmtPost->bindParam(':post_id', $post_id);
    $stmtPost->bindParam(':user_ID', $user_ID);
    $stmtPost->execute();
    $post = $stmtPost->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        echo "Post not found or you don't have permission to edit.";
        exit;
    }

    // Display the form for editing
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Edit Post</title>
    </head>
    <body>
        <h1>Edit Post:</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $post['ID']; ?>">

            <label for="editPostContent">Content:</label>
            <textarea id="editPostContent" name="editPostContent"><?php echo htmlspecialchars($post['Content']); ?></textarea>

            <label for="editPostFile">File:</label>
            <input type="file" id="editPostFile" name="editPostFile">

            <button type="submit" name="editButton">Save Changes</button>
        </form>
    </body>
    </html>
    <?php
}

// Handle post edit/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editButton'])) {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
    $editPostContent = isset($_POST['editPostContent']) ? htmlspecialchars($_POST['editPostContent']) : '';

    // Validate and handle file upload
    $fileUpload = '';
    if (!empty($_FILES['editPostFile']) && $_FILES['editPostFile']['error'] === UPLOAD_ERR_OK) {
        // Handle file upload securely (implement this function)
        $fileUpload = handleFileUpload($_FILES['editPostFile']);
    }

    // Update data in the database
    $stmtUpdate = $db->prepare("UPDATE posts SET Content = :content, fileUpload = :fileUpload WHERE ID = :post_id AND user_ID = :user_ID");
    $stmtUpdate->bindParam(':content', $editPostContent);
    $stmtUpdate->bindParam(':fileUpload', $fileUpload);
    $stmtUpdate->bindParam(':post_id', $post_id);
    $stmtUpdate->bindParam(':user_ID', $user_ID);

    // Execute the update query
    if ($stmtUpdate->execute()) {
        echo 'Update successful!';
    } else {
        echo 'Update failed:';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Post Form</title>
</head>
<style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #0d0c0c, #b0b0b6);
            color: white; /* Set the font color to white */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        #upload-container {
            background-color: #fff;
            color: black; /* Set the font color inside the container */
            padding: 20px;
            padding-right: 42px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        input[type="file"] {
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>

<body>
    <div id="upload-container">
        <h1>Edit a Post:</h1>
        <form method="post" action="process_update.php" enctype="multipart/form-data">
            <label for="postContent">Edit Content:</label>
            <textarea id="postContent" name="postContent" rows="7" cols="50"></textarea>
            <label for="fileToUpload">Edit Upload:</label>
            <input type="file" id="fileToUpload" name="fileToUpload">
            <br><br>
            <button type="submit" name="postButton">Post</button>
            <br><br>
            <a href="./fypsql.php">Back Home</a>
        </form>
    </div>
</body>
</html>