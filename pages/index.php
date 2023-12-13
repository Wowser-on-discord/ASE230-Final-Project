<?php
session_start();
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
            <?php
                include '../navbar.html';
            ?>
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
                                    } elseif (isset($_SESSION['registeredName'])) { //this may need to be fixed later on
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

                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <!-- Page content-->
                <div class="container-fluid">
                    <br>
                    <h1 class="card text-center" style="max-width: 400px; margin: 0 auto;">Welcome to 𝕪!</h1>
                    <p></p>

                    <!-- Giant Card -->
                    <div class="card text-center" style="max-width: 500px; margin: 0 auto;">
                        <div class="card-body">
                            <h5 class="card-title">Login or Register for an Account</h5>
                            <p class="card-text">Choose an option below:</p>
                            <div class="mb-3">
                                <form action="loginsql.php" method="POST">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">Login</button>
                                </form>
                                <br>OR
                            </div>
                        <div class="mb-3">
                            <form action="registersql.php" method="POST">
                                <button type="submit" class="btn btn-success btn-lg btn-block mt-3">Register</button>
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
