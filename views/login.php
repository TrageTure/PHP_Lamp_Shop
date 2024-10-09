<?php 
    function loginAvailable($p_email, $p_password){
        if ($p_email === 'arthur@shop.com' && $p_password === '12345isnotsecure') {
            return true;
        }
        else{
            return false;
        }
    }

    if (!empty($_POST)) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (loginAvailable($email, $password)) {
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            header('location: index.php');
        }
        else{
            $error = true;
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
        <?php if(isset($error)):?>
        <div class="error">
            <p>Email of wachtwoord is onjuist. Probeer opnieuw!</p>
        </div>
        <?php endif?>
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
        </form>
    
        <a href="signup.php">Don't have an account yet? Sign up!</a>
    </div>
</body>
</html>