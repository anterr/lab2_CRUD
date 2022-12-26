<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css?ver=<?php echo time()?>">
    <title>lab_2</title>
</head>
<body>

<div class="header">
        <div class="header__content">
            <p><?=$_COOKIE['user']?></p>
            <a class="exit" href="deleteCookie.php">Exit</a>
        </div>
</div>

<div class="offer__button">
    <a href="#add__offer" class="offer__news">
        Поделиться новостью
    </a>
</div>

<div id="add__offer">
    <div id="add__offer_window">
        <form class="add__post__form" action="newPost.php" method="post" enctype="multipart/form-data">
            <textarea placeholder="Enter your message" rows="4" cols="50" id="massage" name="message"></textarea>
            <div class="input__wrapper">
                <input name="file" type="file" id="input__file" class="input input__file" multiple>
            </div>
            <div class="add__offer_buttons">
                <a href="#" class="offer__news">Закрыть</a>
                <button class="offer__news" type="submit">Сообщить</button>
            </div>
        </form>
    </div>
</div>

<?php
$connect = mysqli_connect('localhost', 'root', 'root', 'login_form');
$mysql = mysqli_query($connect, "SELECT * FROM `posts` order by id desc");
$count = 0;

if($mysql->num_rows > 0) {
    while ($row = $mysql->fetch_assoc()) {
        if($count <= 100) {
            $user = $row;
            $user_id = $user['id'];
            $user_id = (int)$user_id;

            if ($user['image'] > 0) {
                $image = base64_encode($user['image']);
                $show_image = '<img src="data:image/*;base64,'.htmlspecialchars($image).'" alt="">';
            } else {
                $show_image = '';
            }

            $post =  '
                            <div class="news">
    <div class="news__avatar">
        <img src="pngegg.png">
    </div>
    <div class="news__content">
        <h3 class="news__user">'.htmlspecialchars($user['user']).'</h3>
        '. $show_image .'
        <p class="news__text">'.htmlspecialchars($user['text']).'</p>
        <div class="feedback">
            <form action="Like.php" method="post">
                <input type="hidden" value="'.$user_id.'" name="post_id">
                <button type="submit" class="like" name="like">
                    <img class="plus" src="pngegg%20(1).png">
                    <p class="counter__like">'.htmlspecialchars($user['likes']).'</p>
                </button>
            </form>
            <form action="dislike.php" method="post">
                <input type="hidden" value="'.$user_id.'" name="post_id_dislike">
                <button type="submit" class="dislike" name="dislike">
                    <img class="minus" src="free-png.ru-39.png" alt="">
                    <p class="counter__dislike">'.htmlspecialchars($user['dislikes']).'</p>
                </button>
            </form>
        </div>
    </div>
</div>
                            '."\n";

            echo $post;
            $count++;
        }
    }
}
?>


</body>
</html>
