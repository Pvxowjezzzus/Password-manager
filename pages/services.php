<?php 
session_start();
if (!isset($_SESSION['active']) ) {
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
    <title>Сервисы</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="/assets/scripts/services.js" defer></script>
    <script src="/assets/scripts/addService.js"></script>
</head>
<body>
<? if(empty($services)): ?>
<p>Сервисов нет</p>
<? else:?>
<table class="services__table">
                <thead>
                    <tr class="tr">
                        <th scope="col">ID</th>
                        <th scope="col">ID организации</th>
                        <th scope="col">Название сервиса</th>
                        <th scope="col">Создан</th>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($services as $service): ?>
                    <tr>
                        <td id="service_id" data-label="ID"><?= $service['id']?></td>
                        <td data-label="ID организации"><?= $service['org_id']?></td>
                        <td data-label="Название сервиса"><?= $service['service_name']?></td>
                        <td data-label="Создан"><?= $service['created_at']?></td>
                        <td><button class="delete_service" id="del_service">Удалить</button></td>
                    </tr>
                    <? endforeach;?>
                </tbody>
    </table>
    <button type="button" id="show_pswd">Показать пароль</button>
    <div class="show__modal" id="show__modal">
    <div class="modal-content">
      <span onclick="hideModal()">&times</span>
      <form id="showPassword" class="show-password" method="POST" onsubmit="return showPassword(event)">
         <input type="text" class="password_hash" name="password_hash" id="password_hash" autocomplete="off">
         <input type="submit" id="submit__pswd" value="Показать пароль">
      </form>
     <p class="password"></p>
    </div>
  </div>
  <? endif;?>
    <a href="/pages/createService.php?id=<?=$checkorgID?>">Добавить сервис</a>
    <a href="/pages/admin.php">Назад в ПА</a>
</body>
</html>