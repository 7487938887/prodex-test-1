<?php
session_name('prodex-1');
session_start();

require_once 'settings.php';


/*********
 * LOGIN *
 ********/

if (isset($_POST['login']) && isset($_POST['password']) && $_POST['action'] == 'enter') {

    $connect = mysqli_connect(HOST, USER, PASS, DB_NAME) or die('Error database');
    $query = "SELECT `login`, `password` FROM `users` WHERE login = '{$_POST['login']}'";
    $result = mysqli_query($connect, $query);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($connect);

    if (empty($data)) {
        echo '<p id="error">Неправильный логин и/или пароль</p>';
        die;
    }

    if ($data['password'] == $_POST['password']) {
        echo '<p id="success">Успешная авторизация</p>';
        $_SESSION['isLogged'] = true;
    }
}


/*************************
 * CHECK LOGIN AVAILABLE *
 ************************/

if (isset($_POST['login']) && !isset($_POST['action'])) {
    $connect = mysqli_connect(HOST, USER, PASS, DB_NAME) or die('Error database');
    $query = "SELECT `login` FROM `users` WHERE login = '{$_POST['login']}'";
    $result = mysqli_query($connect, $query);
    mysqli_close($connect);

    $login_true = null;

    while ($all_login = mysqli_fetch_assoc($result))
        if ($all_login['login'] == $_POST['login'])
            $login_true = 1;

    if ($login_true == 1) {
        echo '<p id="error">Логин занят</p>';
    } else {
        echo '<p id="success">Логин доступен</p>';
    }
}


/************
 * REGISTER *
 ***********/

if (isset($_POST['login']) && isset($_POST['password']) && $_POST['action'] == 'register') {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $passwordRepeat = $_POST['passwordRepeat'];
    $email = $_POST['email'];

    if (empty($password) && empty($passwordRepeat)) {
        echo '<p id="error">Введите и повторите пароль</p>';
        die;
    }

    if (empty($email)) {
        echo '<p id="error">Вы не ввели почту</p>';
        die;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<p id="error">E-mail указан не верно</p>';
        die;
    }


    if ($password != $passwordRepeat) {
        echo '<p id="error">Пароли не совпадают</p>';
        die;
    }

    $connect = mysqli_connect(HOST, USER, PASS, DB_NAME) or die('Error database');
    $query = "INSERT INTO `users` SET `login` = '{$login}', `password` = '{$password}', `email` = '{$email}'";
    $result = mysqli_query($connect, $query);
    mysqli_close($connect);

    if ($result === true) {
        echo '<p id="success">Вы успешно зарегистрировались</p>';
    } else {
        echo '<p id="error">Ошибка регистрации</p>';
    }
}