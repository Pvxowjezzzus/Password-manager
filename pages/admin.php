<?php
session_start();

// Проверяем, существует ли указанная переменная сессии
if (!isset($_SESSION['active'])) {
    // Если сессия не существует, перенаправляем на страницу входа
    header('Location: /'); 
}

require_once('../handler/admin.php');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель Администратора </title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <script src="/assets/scripts/admin.js"></script>
</head>
<body>
    <h1>Панель администратора</h1>
    <p>Добро пожаловать, <?=$_SESSION['login']?></p>
    <button id="logout-btn">Выйти</button>
    <table class="users__table">
                <thead>
                    <tr class="tr">
                        <th scope="col">ID</th>
                        <th scope="col">Логин</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Права доступа</th>
                        <th scope="col">Создан</th>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($users as $user): ?>
                    <tr>
                        <td data-label="ID"><?= $user['id']?></td>
                        <td data-label="Логин"><?= $user['login']?></td>
                        <td data-label="Роль"><?= $user['role']?></td>
                        <td data-label="Права доступа"><?= $user['permissions']?></td>
                        <td data-label="Создан"><?= $user['created_at']?></td>

                    </tr>
                    <? endforeach;?>
                </tbody>
    </table>
    <a href="/pages/addUser.php">Добавить пользователя</a>
    <table class="org__table">
                <thead>
                    <tr class="tr">
                        <th scope="col">ID</th>
                        <th scope="col">Название организации</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">Права доступа (ID)</th>
                        <th scope="col">Создана</th>
                        
                    </tr>
                </thead>
                <tbody>
                <? foreach ($organizations as $org): ?>
                    <tr class="organization">
                        <td class="org_id" id="org_id" data-label="ID"><?= $org['id']?></td>
                        <td data-label="Название организации"><?= $org['organization_name']?></td>
                        <td data-label="User ID"><?= $org['user_id']?></td>
                        <td data-label="Владельцы"><?= getAccess($db,$org['id'])?></td>
                        <td data-label="Создана"><?= $org['created_at']?></td>
                        <td><a href="/pages/services.php?id=<?=$org['id']?>">Сервисы</a></td>
                        <? if(checkAccess($db, $org['id'])): ?>
                            <td><button class="show_pswd" id="show_pswd"  title="Показать пароль компании: <?=$org['organization_name']?>">Показать пароль</button></td>
                        <td><button class="edit_org" id="edit_org">Редактировать</button></td>
                        <? endif;?>
                        <? if(!checkServicesExist($db, $org['id'])): ?>
                            <td><button class="delete_org" id="del_org">Удалить</button></td>
                        <? endif;?>
                    </tr>
                    <? endforeach;?>

                </tbody>
    </table>
    <div class="show__modal" id="show__modal">
    <div class="modal-content">
      <span>&times</span>
      <form id="showPassword" class="show-password" method="POST">
         <input type="text" class="password_hash" name="password_hash" id="password_hash" autocomplete="off">
         <input type="submit" id="submit__pswd" value="Показать пароль">
      </form>
     <p class="password"></p>
    </div>
  </div>
    <a href="/pages/createOrganization.php">Создать организацию</a>
    <div class="edit-organization__block">
        <form id="editOrganization" class="edit-form"  method="POST">
                <div class="input-block">
                    <div class="text-part">
                       <label for="item-name">Название организации</label> 
                    </div>
                    
                    <input style="margin-top: 10px" type="text" id="organization_name" name="organization"class="input add-input" value="" required>
                </div>
                <div class="input-block">
                    <div class="access_list">

                    </div>
                </div>
                <div style="margin-top: 25px" class="input-block" id="password-field">
                <div class="text-part">
                       <label for="item-name">Изменение пароля</label> 
                </div>
                <div id="password-input"></div>
                </div>
                <input style="margin: 25px 0" type="submit" id="submit__edit" value="Редактировать организацию">
        </form>          
    </div>
                  
</body>
</html>