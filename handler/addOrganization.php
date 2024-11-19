<?php 
session_start();
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$fields = array("organization", "password");
$response = ['valid' => false, 'errors' => [], 'error' => false, 'message' => ''];
$errors = [];
if (!empty($input['organization']) && !empty($input['password'])) { 
    if(strlen($input['organization']) < 1 || strlen($input['organization']) > 255 ){
        $errors = ['message' => 'Поле "Организация" должно содержать от 1 до 255 символов', 'field' => 'organization' ];
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
        $params = [
            'id' => null,
            'organization_name' => $db->pure($input['organization'], ENT_NOQUOTES),
            'user_id' => $_SESSION['user_id'],
            'created_at'=> date("Y-m-d H:i:s"),
        ];
        $addOrganization = $db->query('INSERT INTO organizations VALUES (:id, :organization_name, :user_id, :created_at)', $params);
        if (!$addOrganization) {
            $response['error'] = true;  
            http_response_code(400);
        }
        else {
            $lastID = $db->lastid();
     //   $org_id = $db->column('SELECT id FROM organizations WHERE organization_name = "'.$input['organization'].'"');
          $params2 = [
            'id' => null,
            'org_id'=> $lastID,
            'password'=> $input['password'],
            'password_hash' => $db->encryptPassword($input['password']),
            'user_id' => $_SESSION['user_id'],
            'comment' => '',
            'created_at'=> date("Y-m-d H:i:s"),
        
        ];

       // SELECT p.org_id FROM organizations o INNER JOIN org_passwords p ON p.org_id = o.id
         $addPassword = $db->query('INSERT INTO org_passwords VALUES (:id, :org_id, :password, :password_hash,
          :user_id, :comment, :created_at)', $params2);
        if($addPassword) {
            $params3 = [
                'id' => null,
                'user_id'=> $_SESSION['user_id'],
                'accessed_org_id'=> $lastID,
                'created_at'=> date("Y-m-d H:i:s"),
            
            ];
            $addOwner = $db->query('INSERT INTO access VALUES (:id, :user_id, :accessed_org_id, :created_at)', $params3);
            if($addOwner)
            $response['message'] = 'Компания добавлена';
        }
    }
    }
}

else {
    $errors = ['message' => 'Заполните все поля', 'field' => 'all' ];
    array_push($response['errors'], $errors);
    $response['error'] = true;
    http_response_code(400);
}
echo json_encode($response);
?>