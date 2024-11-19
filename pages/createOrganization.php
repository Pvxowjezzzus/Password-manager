<?php
session_start();

if (!isset($_SESSION['active'])) {
    // Если сессия не существует, перенаправляем на страницу входа
    header('Location: /'); // 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление организации</title>
    <script src="/assets/scripts/add.js" defer></script>
</head>
<body>
    <h1>Добавление организации</h1>
    <form id="addOrganisation" class="add-form"  method="POST">
        <p id="responseMessage"></p>
                <div class="input-block">
                    <div class="text-part">
                       <label for="item-name">Название организации</label> 
                    </div>
                    
                    <input style="margin-top: 10px" type="text" id="organization" name="organization" class="input add-input" required>
                </div>
                <div  style="margin-top: 30px" class="input-block">
                    <div class="text-part">
                      <label for="password">Пароль</label>  
                    </div>
                    <input  style="margin-top: 10px" type="password" id="password" name="password" class="input add-input" required>
                </div>
                <input style="margin-top: 10px"class="btn add-btn submit-btn" type="submit" value="Добавить организацию">
            </form>
    <a href="/pages/admin.php">Назад в ПА</a>
</body>
</html>