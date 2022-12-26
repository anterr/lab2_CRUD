<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?ver=<?php echo time()?>">
    <title>LoginForm</title>
</head>
<body>
<div class="container">
    <div class="main">
        <h1>Registration</h1>
        <form action="check.php" method="post">
            <input name="login" type="text" placeholder="Login">
            <input name="pass" type="password" placeholder="Password">
            <input name="repass" type="password" placeholder="Confirm Password">
            <button type="submit">Register</button>
            <a href="login.php">Зарегистрированы?</a>
        </form>
    </div>
</div>
</body>
</html>