<?php
session_name('prodex-1');
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    unset($_SESSION['isLogged']);
    unset($_SESSION);
    header('Location: index.php');
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Prodex test 1</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="content">

    <?php if (!isset($_SESSION['isLogged']) && !isset($_GET['action'])) { ?>

        <form id="form" action="index.php" method="post">
            <input type="text" name="login" id="login" placeholder="login" required><br>
            <input type="password" name="password" id="password" placeholder="password" required><br>
            <input type="button" id="submitButton" value="Войти" onclick="ajaxLogin()">
            <input type="hidden" id="action" name="action" value="enter">
        </form>

        <div id="resp"></div>

        <div id="register" class="registerHolder">
            <a href="index.php?action=register">
                <button>Регистрация</button>
            </a>
        </div>

    <?php } elseif (isset($_SESSION['isLogged'])) { ?>

        <div id="btnHolder" class="btnHolder">
            <a href="index.php?action=logout">
                <button>Выйти</button>
            </a>
        </div>

    <?php } ?>

    <div id="btnHolder" class="btnHolder" style="display: none">
        <a href="index.php?action=logout">
            <button>Выйти</button>
        </a>
    </div>

    <?php if (isset($_GET['action']) && $_GET['action'] == 'register') { ?>

        <form action="">
            <input type="text" name="login" id="login" placeholder="login" onblur="checkAvaibleLogin()" required><br>
            <input type="password" name="password" id="password" placeholder="password" required><br>
            <input type="password" name="passwordRepeat" id="passwordRepeat" placeholder="repeat password" required><br>
            <input type="email" name="email" id="email" placeholder="email" required><br>
            <input type="button" id="submitButton" value="Регистрация" onclick="ajaxRegister()" disabled>
            <input type="hidden" id="action" name="action" value="register">
        </form>

        <div class="backBtn">
            <a href="index.php">
                <button>&lArr; Назад</button>
            </a>
        </div>

        <div id="resp"></div>

    <?php } ?>

</div>

<script>

    function ajaxLogin() {
        var login = document.getElementById('login').value;
        var password = document.getElementById('password').value;
        var action = document.getElementById('action').value;
        var xhr = new XMLHttpRequest();
        var sendData = 'login=' + encodeURIComponent(login) + '&password=' + encodeURIComponent(password) + '&action=' + encodeURIComponent(action);
        xhr.open('POST', 'auth.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(sendData);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    document.getElementById("resp").innerHTML = xhr.responseText;
                    if (xhr.responseText === '<p id="success">Успешная авторизация</p>') {
                        document.getElementById('btnHolder').style.display = 'block';
                        document.getElementById('register').style.display = 'none';
                        document.getElementById('form').style.display = 'none';
                    }
                }
            }
        };
    }

    function checkAvaibleLogin() {
        var login = document.getElementById('login').value;
        var xhr = new XMLHttpRequest();
        var sendData = 'login=' + encodeURIComponent(login);
        xhr.open('POST', 'auth.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(sendData);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    if (xhr.responseText === '<p id="success">Логин доступен</p>') {
                        document.getElementById('submitButton').disabled = false;
                    }
                    if (xhr.responseText === '<p id="error">Логин занят</p>') {
                        document.getElementById('submitButton').disabled = true;
                    }
                    document.getElementById("resp").innerHTML = xhr.responseText;
                }
            }
        };
    }
	
	function ajaxRegister() {
        var login = document.getElementById('login').value;
        var password = document.getElementById('password').value;
        var passwordRepeat = document.getElementById('passwordRepeat').value;
        var email = document.getElementById('email').value;
        var action = document.getElementById('action').value;
        var xhr = new XMLHttpRequest();
        var sendData = 'login=' + encodeURIComponent(login) + '&password=' + encodeURIComponent(password) + '&passwordRepeat=' + encodeURIComponent(passwordRepeat) + '&email=' + encodeURIComponent(email) + '&action=' + encodeURIComponent(action);
        xhr.open('POST', 'auth.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(sendData);
        xhr.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    document.getElementById("resp").innerHTML = xhr.responseText;
                    if (xhr.responseText === '<p id="success">Вы успешно зарегистрировались</p>') {
                        setTimeout(function () {
                                document.location.href = 'index.php';
                            },
                            2000
                        )
                    }
                }
            }
        };
    }

</script>

</body>
</html>
