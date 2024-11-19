<?php 
session_start();
if (!isset($_SESSION['active'])) {
    // Если сессия не существует, перенаправляем на страницу входа
    header('Location: /'); 
}
require_once('../handler/services.php');
require '../handler/admin.php';
if(!checkAccess($db,$_SESSION['org_id'])){
    header('Location: /'); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание сервиса</title>
    <script src="/assets/scripts/addService.js" defer></script>
</head>
<body>
<h1>Создание сервиса</h1>
    <form id="addServiceForm" class="add-form"  method="POST">
        <p id="responseMessage"></p>
                <div class="input-block">
                    <div class="text-part">
                       <label for="item-name">Название сервиса</label> 
                    </div>
                    
                    <input style="margin-top: 10px" type="text" id="service" name="service" class="input add-input" required>
                </div>
                <div  style="margin-top: 30px" class="input-block">
                    <div class="text-part">
                      <label for="password">Пароль</label>  
                    </div>
                    <input  style="margin-top: 10px" type="password" id="password" name="password" class="input add-input" required>
                </div>
                <input style="margin-top: 10px"class="btn add-btn submit-btn" type="submit" value="Создать сервис">
            </form>
    <a style="display:block;margin-top: 25px" href="/pages/services.php?id=<?=$checkorgID?>">Назад в Сервисы</a>
</body>
</html>