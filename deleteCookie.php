<?php
setcookie('user', $user['login'], time() - 3600, "/");
header('Location: /lab_2_anton/login.php');
?>
