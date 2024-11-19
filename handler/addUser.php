<?php 
session_start();
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$fields = array("organization", "password");
$response = ['valid' => false, 'errors' => [], 'error' => false, 'message' => ''];

if (!empty($input['login']) && !empty($input['password'])) {
    if(!preg_match('/^[a-z\d]+$/i', $input['login'])){
        $errors = ['message' => 'Поле Логин заполнено неправильно ', 'field' => 'login' ];
        array_push($response['errors'], $errors);
        $response['error'] = true;
        http_response_code(400);
        $response['message'] = 'Ошибка данных';
    } 
    $checkLogin = $db->column('SELECT id FROM users WHERE login = "'.$input['login'].'"');
    if($checkLogin) {
        $errors = ['message' => 'Такой логин уже занят', 'field' => 'login' ];
        array_push($response['errors'], $errors);
        $response['error'] = true;
        http_response_code(400);
        $response['message'] = 'Ошибка данных: Данный логин уже занят';
    }
    if(strlen($input['password']) < 8 || strlen($input['password']) > 100) {
        $errors = ['message' => 'Поле Пароль должно содержать от 8 до 100 символов', 'field' => 'password' ];
        array_push($response['errors'], $errors);
        $response['error'] = true;
        $response['message'] = 'Ошибка данных';
        http_response_code(400);
    }
    else if($input['role'] == '-' && $response['error'] == false){
        $response['error'] = true;
        $response['message'] = 'Выберите роль';
        http_response_code(400);
    }
    if($response['error'] == false){
        if($input['role'] == 'admin') {
            $permissions = 1;
        }
        else if($input['role'] == 'user'){
            $permissions = 0;
        }
        $params = [
            'id' => null,
            'login' => $db->pure($input['login'], ENT_NOQUOTES),
            'password' => $db->pure($db->encryptPassword($input['password']), ENT_NOQUOTES),
            'role' => $db->pure($input['role'], ENT_NOQUOTES),
            'permissions' => $permissions,
            'created_at'=> date("Y-m-d H:m"),
        ];
        $addUser = $db->query('INSERT INTO users VALUES (:id, :login, :password, :role, :permissions, :created_at)', $params);
        if (!$addUser) {
            $response['error'] = true;  
            http_response_code(400);
        }
        $response['valid'] = true;
        $response['message'] = 'Пользователь добавлен';
    }
}
echo json_encode($response);

?>