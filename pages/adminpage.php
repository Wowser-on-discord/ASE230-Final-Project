<?php
session_start();

// Include your database connection file
include('../data/db.php');

// Get user_id from session
$user_ID = $_SESSION['user_ID'];

// Ensure user is logged in
if (!isset($_SESSION['user_ID'])) {
    echo "No user logged in you can't access this.";
    echo '<br>';
    echo '<br>';
    echo '<a href="registersql.php">Register Here</a>';
    exit;
}




// Fetch user_ID by the session username
$stmt = $db->prepare("SELECT adminStatus FROM users WHERE ID = :user_ID");
$stmt->bindParam(':user_ID', $user_ID);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ensure the user is logged in and is an admin before they can enter the admin area
if (!isset($user['adminStatus']) || $user['adminStatus'] != 1) {
    echo "Unauthorized access. This area is intended for admins only.";
    echo '<br><br>';
    echo '<a href="index.php" class="btn btn-primary">Go Back to Main Menu</a>';
    // You can redirect them to another page if needed
    // header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script src="../js/likes.js"></script>
    <script src="../js/replies.js"></script>
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

        .admin-section {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <?php include '../navbar.html'; ?>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
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
                                    <a class="dropdown-item" href="logout.php">Log Out</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="admin-section">
                <h1>Admin Page</h1>

                <!-- Section 1: Delete any post -->
                <a href="feedforAdmin.php" class="btn btn-primary">View Posts as Admin</a>

                <!-- Section 2: Manage admin privileges -->
                <a href="manageadmin.php" class="btn btn-primary">Manage Admin Privileges</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
    <!-- Popup window-->
    <script src="../js/deletepost.js"></script>
</body>
</html>
