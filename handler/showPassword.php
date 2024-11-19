<?php 
session_start();
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$response = ['valid' => false, 'error' => false, 'message' => ''];
$orgID = $db->row('SELECT id from org_passwords WHERE password_hash = "'.$input['pswd_hash'].'" AND org_id = "'.$input['id'].'"');


if(isset($input['id'])) {
    $params = [
        'password_hash' => $input['pswd_hash'],
        'org_id' => $input['id'],
    ];
    $checkHash = $db->row('SELECT password FROM org_passwords WHERE password_hash = :password_hash AND org_id = :org_id', $params);

if($input['pswd_hash'] == '') {
    $response['error'] = true;
    $response['message'] = 'Поле не заполнено';
}
    
   else if(!$orgID) {
        $response['error'] = true;
        $response['message'] = 'Ошибка: неправильная хэш-сумма';
        http_response_code(400);
    }
}


if($checkHash  && isset($input['pswd_hash'])) {
        $response['valid'] = true;
        foreach($checkHash as $pswd) {
                $password = $pswd['password'];
        }   
        $response['message'] = 'Пароль: '.$password;
        if(!isset($_SESSION['active'])){
            require_once('../handler/logout.php');
        }
    
    }   

echo json_encode($response);
?>