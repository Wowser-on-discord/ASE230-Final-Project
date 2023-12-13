<?php
session_start();

// Include your database connection file
include('../data/db.php');

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    echo "No user logged in you can't post!";
    echo '<br>';
    echo '<br>';
    echo '<a href="registersql.php">Register Here</a>';
    exit;
}

// in this case userID is the username right now
$username = $_SESSION['userID'];


// fetch user_ID by the session username
$stmt = $db->prepare("SELECT ID FROM users WHERE username = :username");
$stmt->bindParam(':username', $username);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

$user_ID = $user['ID'];

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['postButton'])) {
        $createPost = isset($_POST['createPost']) ? htmlspecialchars($_POST['createPost']) : '';

        $fileToUpload = '';
        if (!empty($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
            $targetDirectory = "uploads/";
            $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);

            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                $fileToUpload = $targetFile;
                // echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Error: Failed to move the uploaded file.";
            }
        }

        // Check if both content and file upload are not empty before inserting into the database
        if (!empty($createPost) || !empty($fileToUpload)) {
            // Insert data into the database
            $stmt = $db->prepare("INSERT INTO posts (user_ID, Content, fileUpload) VALUES (:userID, :content, :fileUpload)");
            $stmt->bindParam(':userID', $user_ID);
            $stmt->bindParam(':content', $createPost);
            $stmt->bindParam(':fileUpload', $fileToUpload);

            if ($stmt->execute()) {
                // echo "The post has been created successfully!";
            } else {
                // Handle database insert error
                $errorInfo = $stmt->errorInfo();
                echo "Error: Unable to save data. Error message: " . $errorInfo[2];
            }
        } else {
            echo "Error: Both content and file upload are empty.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Create a post</title>
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
</head>
<body>
    <div id="upload-container">
        <h1>Create a post:</h1>
        <form method="post" action="createapost.php" enctype="multipart/form-data">
            <label for="createPost">Post Content:</label>
            <textarea name="createPost" rows="6"></textarea><br />
            <label for="fileToUpload">File Upload:</label>
            <input type="file" name="fileToUpload"><br /><br />
            <button type="submit" name="postButton">Post</button>
            <br><br>
            <a href="./fypsql.php">Back Home</a>
        </form>
    </div>
</body>
</html>
