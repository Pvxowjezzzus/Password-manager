<?php 
session_start();
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$response = ['valid' => false, 'error' => false, 'message' => ''];


if($input['pswd_hash'] == '') {
    $response['error'] = true;
    $response['message'] = 'Поле не заполнено';
}
else  {
    if(isset($_SESSION['org_id'])) {
        $pswdID = $db->column('SELECT org_id from services WHERE password_hash = "'.$db->pure($input['pswd_hash'], ENT_NOQUOTES).'" AND org_id = "'.$_SESSION['org_id'].'"');
    }
    else {
        $pswdID = $db->column('SELECT org_id from services WHERE password_hash = "'.$db->pure($input['pswd_hash'], ENT_NOQUOTES).'"');
    }
$checkOrgExists = $db->column('SELECT id from organizations WHERE id = "'.$db->pure($pswdID, ENT_NOQUOTES).'"');
$checkOwner = $db->column('SELECT user_id FROM access WHERE  accessed_org_id = "'.$pswdID.'" AND user_id = "'.$_SESSION['user_id'].'"');

    $params = [
        'password_hash' => $input['pswd_hash'],
    ];
  
    $checkHash = $db->row('SELECT password FROM services WHERE password_hash = :password_hash', $params);
    if(!$pswdID) {
        $response['error'] = true;
        $response['message'] = 'Организации не существует';
        http_response_code(400);
    }
   else if(!$checkOrgExists ||  !$checkOwner) {
        $response['error'] = true;
        $response['message'] = 'Нет доступа к организации';
        http_response_code(400);
    }

    
    else if($checkHash  && isset($input['pswd_hash'])) {
        $response['valid'] = true;
        foreach($checkHash as $pswd) {
                $password = $pswd['password'];
        }   
        $response['message'] = 'Пароль: '.$password;
        if(!isset($_SESSION['active'])){
            require_once('../handler/logout.php');
        }
    
    }   
}
echo json_encode($response);
?>