<?php
$host = "localhost";  // usually "localhost"
$username = "root";
$password = "";
$dbname = "y";

//Specify options
$opt = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false
];

try {
    // Establish a connection to the db
    $db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4', $username, $password, $opt);
} catch (PDOException $e) {
    header('Location: error.php');
    exit; // Stop further execution after redirecting
}
?>
