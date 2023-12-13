<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service</title>
    <style>
        .accept-container {
            border: 2px solid #333; 
            padding: 5px; 
            display: inline-block; 
            margin-right: 10px; 
        }

        .color {
            color: white;
        }
    </style>
    <link href="../css/styles.css" rel="stylesheet" />
</head>
<body class="gradient-background">
    <h2 class="color">Terms of Service</h2>
    <div class="color">
    <?php
    
    $terms = "
        <p>Welcome to 𝕪. By accessing y's services, you must consent to  the following terms and conditions:</p>
        <ol>
            <li>You must be 18+ to use y.</li>
            <li>y is not responsible for any damage to one's reputation.</li>
            <li>We have ownership of your account, and there are usage fees associated with it.</li>
            <li>If you desire to delete your account, you must give a 30 day notice.</li>
            <li>You also agree that you will not use these products for any purposes prohibited by United States law, including, without limitation, the development, design, manufacture or production of nuclear missiles, chemical or biological weapons.</li>
            <li>We reserve the right to suspend your account if we find you misusing our services.</li>
            <li>You do not reserve the right to use y's logo's, trademarks, etc.</li>
            <li>Your account will be suspended if it appears you have copyrighted material on it.</li>
            <li>If you have complaints towards us, contact y's legal department. DO NOT message y directly.</li>
            <li>y reserves the right to use or modify anything you post.</li>
            <li>y reserves the right to disclose your information</li>
            <li>y will take legal action if you post on an alternate account if your original is banned.</li>
            <li>You can't rent out your y account.</li>
            <li>We are not responsible if someone files a lawsuit against you.</li> 
        </ol>
    ";
    echo $terms;
    ?>
    <form action="accept_terms.php" method="post">
        <label for="accept_terms" class="accept-container">
            
                <input type="checkbox" id="accept_terms" name="accept_terms" required>
                I Accept the Terms of Service
            
        </label>
        <br><br>
        <a href="index.php">Start browsing y!</a>
    </form>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    </div>
</body>
</html>
