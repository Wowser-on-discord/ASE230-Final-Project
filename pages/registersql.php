<?php
session_start();

// Include the database connection file
include '../data/db.php';

// Function to check if a user already exists
function userExists($username) {
    global $db;

    $sql = "SELECT COUNT(*) FROM users WHERE ID = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetchColumn() > 0;
}

// Function to hash a password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to insert a new user into the database
function insertUser($username, $password) {
    global $db;

    $hashedPassword = hashPassword($password);

    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    return $stmt->execute();
}

if (!empty($_POST)) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the user already exists
    if (userExists($username)) {
        // Handle the case where the user already exists
        echo "<div class='alert alert-danger'>Username already exists. Please choose a different username.</div>";
    } else {
        // User doesn't exist, proceed with registration
        if (insertUser($username, $password)) {
            // Registration successful
            echo "<div class='alert alert-success'>Registration successful. You can now log in.</div>";
            $_SESSION['userID'] = $username;
            header("Location: ./profileforRegister.php?userID=" . $_SESSION['userID']);
            
        } else {
            // Handle other registration errors
            echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
        }
    }
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
</head>
<body class="gradient-background">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        

        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <img src="../assets/Y.png" alt="site-logo" width="50" height="40" title="y Site Logo">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More...</a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="./TOS.php">Terms of Service</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./AboutUs.php">About Us</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Giant Card -->
            <div class="d-flex justify-content-center align-items-center" style="min-height: 90vh;">
                <div class="card text-center" style="max-width: 1000px;">
                    <div class="card-body">
                        <h5 class="card-title">Register for an account.</h5>
                        <p class="card-text">Enter a username and password:</p>
                        <form action="registersql.php" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <br>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Register!</button>
                            <br>
                            <br>
                            <p>Already have an account?</p>
                            <a href="loginsql.php">Login Here!</a>
                        </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
</body>
</html>
