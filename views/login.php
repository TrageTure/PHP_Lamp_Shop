<?php 
include_once('../classes/User.php');
session_start();
include_once('../classes/User.php');
if (!empty($_POST)) {
    try {
        $user = new User();
        $user->setEmail($_POST['email']);
        if ($user->loginAvailable($_POST['password'])) {
            header('Location: index.php'); 
            exit();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/font.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Login</title>
</head>
<body>
    <div class="background">
        <div class="darken"></div>
    </div>

    <div class="form_container">
        <h1>Login</h1>
        <form action="" method="post" class="form">
            <div class="login_form">
                <label for="email">Email:</label>
                <input type="text" name="email" class="input_field">
            </div>
            
            <div class="login_form">
                <label for="password">Password:</label>
                <input type="password" name="password" class="input_field password">
            </div>
    
            <div class="login_form btn">
                <input type="submit" value="Login" class="btn">
            </div>
            <?php if(isset($error)):?>
            <div class="error">
                <p><?php echo $error?></p>
            </div>
            <?php endif?>
        </form>
    
        <a href="signup.php">Don't have an account yet? Sign up!</a>
    </div>
</body>
</html>