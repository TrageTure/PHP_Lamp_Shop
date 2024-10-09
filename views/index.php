<?php
    session_start();
    if ($_SESSION['loggedin'] !== true) {
        header('location: login.php');
    }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <?php include_once("../components/nav.php"); ?> 
</body>
</html>