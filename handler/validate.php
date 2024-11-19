<?php
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$fields = array("login", "password");
$response = ['valid' => false, 'errors' => [], 'error' => false, 'message' => '', 'invalid' => [],'done' => []];
$errors = [];

if (!empty($input['login']) && !empty($input['password'])) {
    if(!preg_match('/^[a-z\d]+$/i', $input['login'])){
        $errors = ['message' => 'Поле Логин заполнено неправильно ', 'field' => 'login' ];
        array_push($response['invalid'], 'login');
        array_push($response['errors'], $errors);
        $response['error'] = true;
        http_response_code(400);
    } 

    $params = [
        'login' => $input['login'],
        'password' => $db->encryptPassword($input['password']),
    ];
    $query = $db->column('SELECT id FROM users WHERE login = :login AND  password = :password', $params);
    $login =  $db->column('SELECT login FROM users WHERE login = :login AND  password = :password', $params);
    if (!$query || strcmp($input['login'], $login)) {
        $errors = ['message' => 'Логин или Пароль неправильные', 'field' => 'all' ];
        array_push($response['errors'], $errors);
        array_push($response['invalid'], 'all');
        $response['error'] = true;
        http_response_code(400);
    } 
    else { 
        $done = array_diff($fields, $response['invalid']);
        foreach($done as $field => $value) {
            array_push($response['done'], $value);
        }   
    }
    //  if(isset($input['password'])) {
    //     echo $db->encryptPassword($input['password']);
    
    //  }
    if($response['error'] == false){
        $response['valid'] = true;
        $response['message'] = 'Поля заполнены';
    }

   
    //Логика проверки данных (например, использование регулярных выражений)

} else {
    $errors = ['message' => 'Заполните все поля', 'field' => 'all' ];
    array_push($response['errors'], $errors);
    array_push($response['invalid'], 'all');
    $response['error'] = true;
    http_response_code(400);
}

echo json_encode($response);    


?>