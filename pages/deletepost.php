<?php
session_start();
include('../data/db.php');

$user_ID = $_SESSION['user_ID'];
$selectedPostID = isset($_POST['selectedPostID']) ? intval($_POST['selectedPostID']) : 0;

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteButton'])) {
        //$selectedPostID = isset($_POST['selectedPostID']) ? intval($_POST['selectedPostID']) : 0;

        if ($selectedPostID) {
            // If the post exists, delete it
            $stmtDelete = $db->prepare("DELETE FROM posts WHERE ID = :postID AND user_ID = :user_ID");
            $stmtDelete->bindParam(':postID', $selectedPostID);
            $stmtDelete->bindParam(':user_ID', $user_ID);

            if ($stmtDelete->execute()) {
                // Redirect to the home page after deletion
                header("Location: fypsql.php");
                exit();
            } else {
                // Deletion failed
                echo "Error: Unable to delete the post.";
                exit();
            }
        } else {
            // The selected post does not exist or doesn't belong to the user
            echo "Error: Please select a valid post to delete.";
            echo '<br><br>';
            echo '<a href="./fypsql.php">Back Home</a>';

            exit();
        }
    }
}

// Fetch posts from the database
$stmtFetch = $db->prepare("SELECT ID, user_ID, Content, fileUpload FROM posts WHERE user_ID = :user_ID");
$stmtFetch->bindParam(':user_ID', $user_ID);
$stmtFetch->execute();
$posts = $stmtFetch->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Posts</title>
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
<body class="gradient-background">
<div id="upload-container">

    <h1>Delete Posts:</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <h3>If you are sure you want to delete this item, check this box:</h3>
    <?php foreach ($posts as $post) : ?>
    <label>
    <input type="checkbox" name="selectedPostID" value="<?php echo $post['ID']; ?>" <?php echo (isset($_POST['selectedPostID']) && $_POST['selectedPostID'] == $post['ID']) ? 'checked' : ''; ?>>
        
        <?php if (isset($post['Content'])) : ?>
            Text: <?php echo $post['Content']; ?>
        <?php endif; ?>

        <?php if (isset($post['fileUpload'])) : ?>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;File: <?php echo $post['fileUpload']; ?><br><br>
            <?php endif; ?>
    </label>
<?php endforeach; ?>


        <button type="submit" name="deleteButton">Delete Selected Post</button>
        <br><br>
        <a href="./fypsql.php">Back Home</a>

    </form>
        </div>
</body>
</html>