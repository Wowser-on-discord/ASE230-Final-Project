<?php
session_start();

// Include your database connection file
include('../data/db.php');

// Ensure user is logged in
if (!isset($_SESSION['userID'])) {
    echo "No user logged in; you can't view posts!";
    echo '<br>';
    echo '<br>';
    echo '<a href="registersql.php">Register Here</a>';
    exit;
}

function getLikesCount($postId, $db) {
    $stmt = $db->prepare("SELECT COUNT(*) FROM likes WHERE postID = :postID");
    $stmt->bindParam(':postID', $postId);
    $stmt->execute();
    return $stmt->fetchColumn();
}

function getRepliesForPost($postId, $db) {
    $stmt = $db->prepare("SELECT ID, user_ID, Content FROM replies WHERE postID = :postID");
    $stmt->bindParam(':postID', $postId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$userID = $_SESSION['userID']; // username

// Fetch user data, including adminStatus
$stmtUser = $db->prepare("SELECT ID, adminStatus FROM users WHERE username = :username");
$stmtUser->bindParam(':username', $userID);
$stmtUser->execute();
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Check if the user is an admin
if (!$user || !isset($user['adminStatus']) || $user['adminStatus'] != 1) {
    echo "Unauthorized access. This area is intended for admins only.";
    exit;
}

// Use the fetched user ID instead of the session username
$adminUserID = $user['ID'];
$_SESSION['user_ID'] = $adminUserID;

// Fetch posts from the database with username
$stmt = $db->prepare("SELECT p.ID, p.user_ID, p.Content, p.fileUpload, u.username FROM posts p
                    JOIN users u ON p.user_ID = u.ID
                    ORDER BY p.ID DESC");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch user_ID by the session username
$stmt = $db->prepare("SELECT ID FROM users WHERE username = :username");
$stmt->bindParam(':username', $userID);
$stmt->execute();
$userForPosts = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userForPosts) {
    echo "User not found.";
    exit;
}

$user_ID = $userForPosts['ID'];
// Store this in the session to make this easier
$_SESSION['user_ID'] = $user_ID;

shuffle($data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--<script src="../js/replies.js"></script>-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Welcome to y!</title>
    <!-- Favicon-->
    <link rel="icon" type="../image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .post p {
            margin: 0;
            padding: 0;
        }

        .post img {
            max-width: 100%;
        }

        .like-button,
        .reply-button,
        .admin-button {
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .replies {
            margin-left: 20px;
            border-left: 2px solid #007bff;
            padding-left: 10px;
        }

        /* Style the form */
        form {
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            max-width: 600px;
            padding: 10px;
            font-family: Arial, sans-serif;
            resize: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .max-size-image {
            max-width: 400px;
            max-height: 300px;
        }

        .move-right {
            align: right;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar-->
    <div>
        <?php
        include '../navbar.html';
        ?>
    </div>
    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Top navigation-->
        <!-- Your top navigation content here -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="sidebarToggle">☰</button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <img src="../assets/Y.png" alt="site-logo" width="50" height="40" title="y Site Logo">
                        <?php
                        if (isset($_SESSION['userID'])) {
                            $username = $_SESSION['userID'];
                        } elseif (isset($_SESSION['registeredName'])) { //might need some fixing here
                            $username = $_SESSION['registeredName'];
                        } else {
                            $username = '';
                        }

                        if (!empty($username)) {
                            echo '<li class="nav-item"><a class="nav-link" href="profiles.php?username=' . $username . '">Profile</a></li>';
                        } else {
                            echo '<li class="nav-item"><a class="nav-link" href="#">Profile</a></li>';
                        }
                        ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More...</a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="./TOS.php">Terms of Service</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./AboutUs.php">About Us</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="adminpage.php">Admin Page</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Log Out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <h1 style="display: flex; flex-direction: column; align-items: center; text-align: center;">Most Recent Posts</h1>
        <?php
        shuffle($data);

        foreach ($data as $post) {
            // Display the post
            echo '<div class="post">';
            echo '<p><strong>Username:</strong> ' . $post['username'] . '</p>';
            echo '<div class="move-right">';



            echo '</div>';
            echo '<p>' . $post['Content'] . '</p>';

            // Like button
            echo '<form action="likingPost.php" method="POST">';
            echo '<input type="hidden" name="postId" value="' . $post['ID'] . '">&nbsp;&nbsp;';
            echo '<br>';
            echo '<button type="submit" class="btn btn-primary admin-button">♡</button>';
            
            // Check if the logged-in user is an admin or the owner of the post
            if ($user['adminStatus'] == 1 || $post['user_ID'] == $adminUserID) {
                echo '<a href="editItem.php?id=' . $post['ID'] . '" class="btn btn-primary admin-button">✎</a>&nbsp;';
                echo '<a href="deletepost.php?post_id=' . $post['ID'] . '" class="btn btn-primary admin-button">🗑️</a>';

            }
            echo '</form>';

            // Display likes count
            $likesCount = getLikesCount($post['ID'], $db);
            echo '<p>Likes: ' . $likesCount . '</p>';

            if (!empty($post['fileUpload'])) {
                echo '<img src="' . $post['fileUpload'] . '" alt="Uploaded Image" class="max-size-image">';
            }
            echo '</div>';

            // Display replies for the post
            $replies = getRepliesForPost($post['ID'], $db);
            if (!empty($replies)) {
                echo '<div class="replies">';
                foreach ($replies as $reply) {
                    // Fetch the username based on user_ID
                    $stmt = $db->prepare("SELECT username FROM users WHERE ID = :userId");
                    $stmt->bindParam(':userId', $reply['user_ID']);
                    $stmt->execute();
                    $replyUser = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($replyUser) {
                        $replyUsername = $replyUser['username'];

                        // Display the reply with the username
                        echo '<p class="reply">' . htmlspecialchars($reply['Content']) . ' by ' . htmlspecialchars($replyUsername) . '</p>';
                    } else {
                        // Handle the case where the user is not found
                        echo '<p class="reply">' . htmlspecialchars($reply['Content']) . ' by Unknown User</p>';
                    }
                }
                echo '</div>';
            }

            // Form for adding a reply/comment
            echo '<div class="reply-form">';
            echo '<form action="replies.php" method="POST">';
            echo '<input type="hidden" name="postID" value="' . $post['ID'] . '">';
            echo '<textarea name="replyText" rows="1" placeholder="Type your reply"></textarea>';
            echo '<button type="submit">Post Reply</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="../js/likes.js"></script>

    </div>
</div>
</body>
</html>