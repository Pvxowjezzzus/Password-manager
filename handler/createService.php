<?php 
session_start();

require_once '../libs/DB.php';
$db = new DB();

header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$response = ['valid' => false, 'errors' => [], 'error' => false, 'message' => ''];
if (!empty($input['service']) && !empty($input['password'])) { 
   
    echo $checkorgID;
    if(strlen($input['service']) < 1 || strlen($input['service']) > 255 ){
        $errors = ['message' => 'Поле "Сервис" должно содержать от 1 до 255 символов', 'field' => 'service' ];
        array_push($response['errors'], $errors);
        $response['error'] = true;
        http_response_code(400);
    }
    if(strlen($input['password']) < 6 || strlen($input['password']) > 255){
        $errors = ['message' => 'Поле "Пароль" должно содержать от 6 до 255 символов', 'field' => 'password' ];
        array_push($response['errors'], $errors);
        $response['error'] = true;
        http_response_code(400);
    }
    if($response['error'] == false){
        $response['valid'] = true;
        $response['message'] = 'Поля заполнены';
        $params2 = [
            'id' => null,
            'org_id' => $_SESSION['org_id'],
            'service_name' => $db->pure($input['service'], ENT_NOQUOTES),
            'user_id' => $_SESSION['user_id'],
            'password'=> $input['password'],
            'password_hash' => $db->encryptPassword($input['password']),
            'created_at'=> date("Y-m-d H:i:s"),
        ];
        $addService = $db->query('INSERT INTO services VALUES (:id, :org_id, :service_name, :user_id, :password, :password_hash, :created_at)', $params2);
        if (!$addService) {
            $response['error'] = true;  
            http_response_code(400);
        }
        else {
            $response['valid'] = true;
            $response['message'] = 'Сервис добавлен';
            unset($_SESSION['org_id']);
        }
    }
}
echo json_encode($response);
?>