<?php
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
session_start();
$response = ['ok' => false, 'role' => '', 'gotoPA' => '', 'checkHASH' => ''];
$params = [
    'login' => $input['login'],
    'password' => $db->encryptPassword($input['password']),
];
$query = $db->query('SELECT *  FROM users WHERE login = :login AND  password = :password', $params);
$adminBtn = '<button class="goto-admin" onclick="gotoAdmin()">Перейти в ПА</button>';
if ($query) {
 foreach($query as $data){
    $response['gotoPA'] = false;
    $response['role'] = $data['role'];
    $_SESSION['user_id'] = $data['id'];
    $_SESSION['login'] = $data['login'];
    if($data['role'] == 'admin') {
        $response['gotoPA'] = $adminBtn;
        $_SESSION['role'] = $data['role'];   
    }

 }
 $checkHASH = ' 
 <button class="show_pswd" id="show_pswd"  title="Показать пароль организации" onclick="showModal()">Показать пароль</button>
 <div class="show__modal" id="show__modal">
 <div class="modal-content">
   <span onclick="hideModal()">&times</span>
   <form id="showPassword" class="show-password" method="POST" onsubmit="return showPassword(event)">
      <input type="text" class="password_hash" name="password_hash" id="password_hash" autocomplete="off">
      <input type="submit" id="submit__pswd" value="Показать пароль">
   </form>
  <p class="password"></p>
     </div>
     </div>';
$response['checkHASH'] = $checkHASH;
 if(isset($input['isAdmin']) && $input['isAdmin'] == true && $_SESSION['role'] == 'admin') {
        $response = ['ok' => false, 'gotoPA' => true];
        $_SESSION['active'] = true;
    }
    
 $response['ok'] = true;
 echo json_encode($response);
} 

?>