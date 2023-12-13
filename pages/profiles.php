<?php
session_start();

// Include the database connection file
include '../data/db.php';

// Function to get user data from the 'users' table
function getUserData($username) {
    global $db;

    $sql = "SELECT username, Bio, profilePic, profileBanner FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Welcome to y!</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />

    <style>
                    .image-container {
            position: relative;
            width: 100%;
            max-width: 1300px;
            height: auto;
            margin: 0 auto;
        }

        .profile-banner {
            z-index: 1;
        }

        body {
            text-align: center;

            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #profile-picture {
            position: absolute;
            bottom: 50%;
            left: 50%; 
            transform: translate(-50%, 50%); 
            z-index: 2;
            width: 100px;
            height: 100px;
            border-radius: 100px;
        }

        .user-bio {
            color: white;
        }

        .user-name {
            color: white;
        }
    </style>
</head>
<body class="gradient-background">
    <!-- Your HTML content here -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <?php include '../navbar.html'; ?>
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
                                } elseif (isset($_SESSION['registeredName'])) {
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
                                    <a class="dropdown-item" href="logout.php">Sign In/Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        <div class="profile-container">
            <div class="image-container">
            <?php
                if (isset($_GET['username'])) {
                    $username = $_GET['username'];
                    $userData = getUserData($username);

                    if ($userData) {
                        $user_username = $userData['username'];
                        $user_bio = $userData['Bio'];
                        $profile_picture_path = $userData['profilePic'];
                        $banner_path = $userData['profileBanner'];

                        // Display the images using file paths
                        echo "<h1 class='user-name'>Profile of $user_username</h1>";

                        // Display profile picture if set, otherwise display a default image
                        if ($profile_picture_path) {
                            echo "<img id='profile-picture' src='$profile_picture_path' alt='Profile Picture' width='100' height='80'>";
                        } else {
                            echo "<img id='profile-picture' src='default_profile_pic.jpg' alt='Default Profile Picture' width='100' height='80'>";
                        }

                        // Display banner image if set, otherwise display a default image
                        if ($banner_path) {
                            echo "<img class='profile-banner' src='$banner_path' alt='Banner Image' width='1300' height='400'>";
                        } else {
                            echo "<img class='profile-banner' src='default_banner.jpg' alt='Default Banner Image' width='1300' height='400'>";
                        }

                        // Display user bio if set, otherwise display a message
                        if ($user_bio) {
                            echo "<p class='user-bio'>Bio: $user_bio</p>";
                        } else {
                            echo "<p class='user-bio'>No bio set yet.</p>";
                        }
                    } else {
                        echo "User not found.";
                    }
                } else {
                    echo "No user logged in or recently registered.";
                }

                //fetch user_ID by the session username
                $stmt = $db->prepare("SELECT ID FROM users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    echo "User not found.";
                    exit;
                }

                $user_ID = $user['ID'];
                //store this in session to make this easier
                $_SESSION['user_ID'] = $user_ID;
            ?>
            </div>
        </div>
    </div>
</div>
    <!-- Bootstrap core JS and Core theme JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
