<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?ver=<?php echo time()?>">
    <title>LoginForm</title>
</head>
<body>
<?php
if($_COOKIE['user'] == ''):

    ?>

  <div class="container">
    <div class="main">
        <h1>Login</h1>
      <form action="auth.php" method="post">
        <input name="login" type="text" placeholder="Login">
        <input name="pass" type="password" placeholder="Password">
        <button type="submit">Auth</button>
          <a href="registration.php">Нет аккаунта?</a>
      </form>
    </div>
  </div>

<?php
else:
    header('Location: /lab_2_anton/main.php');
    ?>
<?php
endif;
?>

</body>
</html>
