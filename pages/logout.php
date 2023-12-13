<?php
session_start();
session_destroy();
header("Location: loginsql.php");
exit();
?>