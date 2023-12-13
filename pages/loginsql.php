<?php
// NOTE: THIS FILE HANDLES USERS SO ID is the user's username.
session_start();

// Include the database connection file
include '../data/db.php';

// Function to check user credentials for login
function loginUser($username, $password) {
    global $db;

    try {
        $db = new PDO("mysql:host=localhost;dbname=y", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit;
    }

    // SQL query to retrieve user data
    $sql = "SELECT username, Password FROM users WHERE username = :username";
    $stmt = $db->prepare($sql);
    
    if (!$stmt) {
        echo "SQL error: " . $db->errorInfo()[2];
        exit;
    }

    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch user data
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user data is retrieved and verify password
    if ($userData) {
        echo "User data retrieved. ";
        if (password_verify($password, $userData['Password'])) {
            echo "Password verified. Login successful.";
            return true; // Login successful
        } else {
            echo "Password verification failed. ";
        }
    } else {
        echo "User not found. ";
    }
    $enteredPassword = trim($_POST["password"]);
    echo "Entered Password: $enteredPassword<br>";
    echo "Stored Hash: {$userData['Password']}<br>";
    echo "Verification Result: " . (password_verify($enteredPassword, $userData['Password']) ? 'Match' : 'No match') . "<br>";


    return false; // Login failed
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if "username" and "password" keys are set in the $_POST array
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Call the loginUser function feeding the function the username and password given in the form.
        if (loginUser($username, $password)) {
            // If successful, redirect to account customization page.
            $_SESSION['userID'] = $username;
            if (isset($_SESSION['userID'])) {
                echo "Welcome " . $_SESSION['userID'];
            }

            if ($_SESSION['userID']) {
                header("Location: ./profileforLogins.php?userID=" . $_SESSION['userID']);
                exit();
            }

        } else {
            // Handle login failure
            $loginError = "Invalid username or password. Please try again.";
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
                        <h5 class="card-title">Login to your account.</h5>
                        <?php if (isset($loginError)) : ?>
                            <div class="alert alert-danger"><?= $loginError ?></div>
                        <?php endif; ?>
                        <form action="loginsql.php" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>
                            <br>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">Login!</button>
                            <br>
                            <br>
                            <p>Don't have an account? <a href="registersql.php">Register Here!</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="../js/scripts.js"></script>
</body>
</html>
