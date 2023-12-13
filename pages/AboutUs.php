<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <style>
        .color {
            color: white;
        }
    </style>
</head>
<body class="gradient-background">
    <div class="color">
    <header>
        <h1>About Us</h1>
    </header>
    <main>
        <section>
            <h2>From Humble Beginnings</h2>
            <?php
            $aboutUsContent = "At 𝕪, we take our customers needs to be the top priority. Our extremely skilled IT team is ready and pleased to assist you anytime. We come from the queen city of Cincinnati, Ohio with strong IT backgrounds from <strong>Great American Insurance Group</strong>, <strong>General Electric Aviation</strong>, and <strong>Western & Southern Financial Group</strong>. Our services offer a similar experience to that of Twitter or also known as X. Here at y we strive to please our clients in every way possible.";
            echo "<p>$aboutUsContent</p>";
            ?>
        </section>
        <section>
            <h2>Our Grade A Team</h2>
            <?php
            $teamMembers = [
                "Nick Vennemann",
                "Noah Conley",
                "Michael Clark",
                "Jacob Doerr",
            ];

            echo "<ul>";
            foreach ($teamMembers as $member) {
                echo "<li>$member</li>";
            }
            echo "</ul>";
            ?>
        </section>
    </main>
    <footer>
        <p>Copyright &copy; <?php echo date("Y"); ?> 𝕪 <br>All rights reserved.</br></p>
    </footer>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
</body>
</html>
