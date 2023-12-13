<?php
session_start();

include '../data/db.php';

// Check if the user is logged in
if (isset($_SESSION['userID'])) {
    $loggedInUsername = $_SESSION['userID'];
} else {
    // Redirect to a login page or handle the case where no user is logged in
    header("Location: ./loginsql.php");
    exit();
}

// Function to get the bio value from the 'users' table
function getBioValue($username) {
    global $db;

    $sql = "SELECT Bio FROM users WHERE ID = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['Bio'] : '';
}

// Function to get the profile picture path from the 'users' table
function getProfilePicturePath($username) {
    global $db;

    $sql = "SELECT profilePic FROM users WHERE ID = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['profilePic'] : '';
}

// Function to get the banner path from the 'users' table
function getBannerPath($username) {
    global $db;

    $sql = "SELECT profileBanner FROM users WHERE ID = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['profileBanner'] : '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form data
    $user_bio = $_POST['user_bio'];

    // Check if profile picture is uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $profile_picture_path = 'fileuploads/' . $profile_picture;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture_path);
    } else {
        // Profile picture was not updated, so retrieve the existing path from the 'users' table
        $profile_picture_path = getProfilePicturePath($loggedInUsername);
    }

    // Check if banner is uploaded
    if (!empty($_FILES['banner']['name'])) {
        $banner = $_FILES['banner']['name'];
        $banner_path = 'fileuploads/' . $banner;
        move_uploaded_file($_FILES['banner']['tmp_name'], $banner_path);
    } else {
        // Banner was not updated, so retrieve the existing path from the 'users' table
        $banner_path = getBannerPath($loggedInUsername);
    }

    // SQL query to update user's bio, profile picture, and banner in the 'users' table
    $sql = "UPDATE users SET Bio = :user_bio, profilePic = :profile_picture_path, profileBanner = :banner_path WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_bio', $user_bio);
    $stmt->bindParam(':profile_picture_path', $profile_picture_path);
    $stmt->bindParam(':banner_path', $banner_path);
    $stmt->bindParam(':username', $loggedInUsername);
    
    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the user's profile page
        header("Location: ./profiles.php?username=" . $loggedInUsername);
        exit();
    } else {
        // Handle the case where the update failed
        echo "<div class='alert alert-danger'>Error updating profile. Please try again.</div>";
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
<body>
    <div class="d-flex" id="wrapper">
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Your page content -->
            <div class="container-fluid">
                <?php
                if (isset($_SESSION['userID'])) {
                    $newUsername = $_SESSION['userID'];
                    echo "<br>";
                    echo "Welcome, $newUsername! Let's design your profile!<br><br>";
                } else {
                    echo "No user currently logged in.";
                }
                ?>

                <form action="profileforLogins.php" method="POST" enctype="multipart/form-data">
                    <!-- Hidden field to store the user ID -->
                    <input type="hidden" name="username" value="<?php echo $newUsername; ?>">

                    <div class="mb-3">
                        <label for="user_bio">Bio:</label>
                        <textarea class="form-control" id="user_bio" name="user_bio" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="profile_picture">Profile Picture:</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                    </div>

                    <div class="mb-3">
                        <label for="banner">Banner:</label>
                        <input type="file" class="form-control" id="banner" name="banner">
                    </div>
                    <h5> If you don't want to make any changes click discard changes </h5>
                    <?= '<a class="btn btn-primary btn-lg btn-block" href="profiles.php?username=' . $newUsername . '">Discard Changes</a></li>'; ?>
                    <br><br><br>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Save Changes</button>
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
