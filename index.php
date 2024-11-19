<?php 
session_start();
if(!in_array($_SERVER['REMOTE_ADDR'], require_once('config/whitelist.php'))) {
    header("HTTP/1.0 403 Forbidden");
    exit();
}
if(isset($_SESSION['active'])) {
    header('Location: /pages/admin.php'); // 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Менеджер паролей</title>
    <link rel="stylesheet" href="assets\css\main.css">
    <script src="/assets/scripts/script.js" defer></script>
</head>
<body>
    <main>
        <form id="enter-form" method="POST">
            <label for="login">Логин:</label>
            <input name="login" type="text" class="enter-input" id="login">
            <span class="responseMessage" id="response_login"></span>
            <label for="password">Пароль:</label>
            <input name="password" type="password" class="enter-input" id="password">  
            <span class="responseMessage" id="response_password"></span>
            <input type="submit" value="Войти" id="submit-btn">          
            <p class="responseMessage" id="response_all"></p>
        </form>
        <div class="choose"></div>
    </main>
</body>
</html>