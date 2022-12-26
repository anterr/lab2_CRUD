# Лабораторная работа №2

Задание

![image](https://user-images.githubusercontent.com/121386333/209470657-b8ab0736-f101-4fe1-81f1-e67f460de1c2.png)

Ход работы

![image](https://user-images.githubusercontent.com/121386333/209470672-50b5c30b-5e27-4e13-b450-767f76a36f86.png)

Интерфейс
. Пользовательский сценарий работы

Пользовательский сценарий

    Пользователь попадает на страницу login.php на которой он может войти либо перейти на страницу registration.php на которой он может зарегистрироваться.
    Дальше происходит редирект на main.php, где изображена лента постов.
    Пользователь может добавить свой пост, который может содердать как текст, так и изображение.
    Есть возможность ставить лайки/дизлайки.

API сервера

В основе приложения использована клиент-серверная архитектура. Обмен данными между клиентом и сервером осуществляется с помощью HTTP POST запросов. В теле POST запроса отправки поста используются следующие поля: comment. Для увеличения счётчика реакции используется форма с POST запросом

Хореография

    Отправка сообщения. Принимается введенное сообщение. Отправляется запрос на добавление сообщения в базу данных. Затем происходит обновление страницы. Из базы данных выводится данный пост с картинкой если она была добавлена.
    Просмотр и оценка сообщений. Кнопка like вызывает отправление запроса в базу данных на изменение количества лайков на определенном id сообщения.


Хореография
Структура базы данных


    "cid" типа int с автоинкрементом для выдачи уникальных id всем сообщениям
    "uid" типа varchar для хранения никнеймов пользователей
    "date" типа datetime для хранения даты и времени отправления сообщения
    "message" типа text для хранения сообщений
    "likes" типа int для хранения количества лайков
    "dislikes" типа int для хранения количества дизлайков
    "image_id" типа varchar для хранения картинок


Значимые фрагменты кода


Отправка сообщения

```
$text = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);
$user = $_COOKIE['user'];
//addslashes  Возвращает строку с обратным слешом перед символами, которые нужно экранировать.
//file_get_contents() возвращает содержимое файла в строке, начиная с указанного смещения offset и до length байт

if ($_FILES && $_FILES['file']['error']== UPLOAD_ERR_OK) {
    $image = addslashes(file_get_contents($_FILES['file']['tmp_name']));

    $mysql_posts = new mysqli('localhost', 'root', 'root', 'login_form');
    $mysql_posts->query("INSERT INTO `posts` (`user`,`text`, `likes`, `dislikes`, `image`) VALUES ('$user', '$text', '0', '0', '$image')");

    header('Location: /lab_2_anton/main.php');

} else {
    $mysql_posts = new mysqli('localhost', 'root', 'root', 'login_form');
    $mysql_posts->query("INSERT INTO `posts` (`user`,`text`, `likes`, `dislikes`, `image`) VALUES ('$user', '$text', '0', '0', '')");

    header('Location: /lab_2_anton/main.php');
}
```

Добавление лайка

``` 
$post_id = filter_var(trim($_POST['post_id']), FILTER_SANITIZE_STRING);

$mysql = new mysqli('localhost', 'root', 'root', 'login_form');
$mysql_user = $mysql->query("SELECT * FROM `users` WHERE login = '{$_COOKIE['user']}'");
$mysql_user = $mysql_user->fetch_assoc();
$user_id = $mysql_user['id'];

$mysql1 = new mysqli('localhost', 'root', 'root', 'login_form');
$mysql_post = $mysql1->query("SELECT COUNT(*) as count FROM posts_like where id_post = $post_id ");
$mysql_post = $mysql_post->fetch_assoc();
$like_count = $mysql_post["count"];

$mysql2 = new mysqli('localhost', 'root', 'root', 'login_form');
$mysql_date = $mysql2->query("SELECT * FROM posts_like WHERE id_post=$post_id AND id_user=$user_id");
$mysql_user = $mysql_date->fetch_assoc();


if($mysql_date -> num_rows > 0) {
    $like_count = $like_count - 1;

    $mysql2->query("DELETE FROM posts_like WHERE id_post = $post_id AND id_user = $user_id");
    $mysql1->query("UPDATE posts SET likes = $like_count WHERE id = $post_id");
    header('Location: /lab_2_anton/main.php');

} else {

    $mysqlLike = new mysqli('localhost', 'root', 'root', 'login_form');
    $mysql_post_like = $mysqlLike->query("SELECT COUNT(*) as count FROM posts_dislike where id_post = $post_id ");
    $mysql_post_like = $mysql_post_like->fetch_assoc();
    $like_count_like = $mysql_post_like["count"];

    $mysql2_like = new mysqli('localhost', 'root', 'root', 'login_form');
    $mysql_date_like = $mysql2_like->query("SELECT * FROM posts_dislike WHERE id_post=$post_id AND id_user=$user_id");

    if($mysql_date_like -> num_rows > 0) {
        $like_count_like = $like_count_like - 1;

        $mysql2_like->query("DELETE FROM posts_dislike WHERE id_post = $post_id AND id_user = $user_id");
        $mysqlLike->query("UPDATE posts SET dislikes = $like_count_like WHERE id = $post_id");

        $like_count = $like_count + 1;

        $mysql2->query("INSERT INTO posts_like (id_post, id_user) VALUES ($post_id, $user_id)");
        $mysql1->query("UPDATE posts SET likes = $like_count WHERE id = $post_id");
        header('Location: /lab_2_anton/main.php');
    }
    else {
        $like_count = $like_count + 1;

        $mysql2->query("INSERT INTO posts_like (id_post, id_user) VALUES ($post_id, $user_id)");
        $mysql1->query("UPDATE posts SET likes = $like_count WHERE id = $post_id");
        header('Location: /lab_2_anton/main.php');
    }
}
```

Вывод сообщений

``` 
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
```

Вывод

В этой лабораторной работе был реализован простейший алгоритм работы CRUD клиент-серверной системы.