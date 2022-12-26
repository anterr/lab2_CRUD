<?php

$connect = mysqli_connect('localhost', 'root', 'root', 'login_form'); //Подключаемся к базе данных login_form

$login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING); // Записываем в переменную $login значение из окна ввода логина с приминением фильтров чтобы убрать возможные специальные символы которые формируется HTML например пробелы
$check_user = mysqli_query($connect, "SELECT `login`, `pass` FROM `users` WHERE `login` = '$login'"); // Запрос к таблице users из базы данных login_form где логин это введенный логин


if ($check_user -> num_rows == 1) { // Если найден пользователь с таким логином
    $row = $check_user -> fetch_assoc(); //$row присваивается ассоциативный массив
    if (password_verify($_POST['pass'], $row['pass'])) { // проверяется введенный пароль с хешем взятым из базы данных
        setcookie('user', $row['login'], time() + 3600, "/"); // Устанавливает куки которые будут переданы пользователю вместе с другими HTTP-заголовками

        // первый параметр название куки
        // второй параметр значение элемента login из массива $row
        // третий параметр время жизни куки 3600 секунд  = 1 час
        // четвертый параметр область действия куки (действует на всех страницах)
        header('Location: /lab_2_anton/main.php'); // переадресация
    } else {
        echo 'Пароль или логин неверные';
    }
} else {
    echo 'Пользователь не найден';
}
?>

