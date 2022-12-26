<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css?ver=<?php echo time()?>">
    <title>Title</title>
</head>
<body>
  <div class="container">
      <div class="main">
          <h1 class="title">Привет <?=$_COOKIE['user']?></h1>
          <a class="link" href="deleteCookie.php">  Exit</a>
      </div>
  </div>

</body>
</html>