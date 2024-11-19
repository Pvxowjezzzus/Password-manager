<?php 
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);
$response = ['valid' => false, 'organizationname' => '', 'error' => false, 'usersID'=> '', 'accesssID' =>'','message' => ''];

if(isset($input['id'])) {
    //  выборка имени организации, и id владельцев по нужному id организации
    $orgs = $db->column('SELECT organization_name FROM organizations WHERE id = "'.$input['id'].'"');
    require('../handler/admin.php');
    //$accesssIDs =  $db->row("SELECT user_id FROM access WHERE  owned_org_id = ".$input['id'].""); 
    $response['message'] = 'Редактирование организации: '.$input['id']; // сообщение о ред. организации
    $response['organizationname'] =  $orgs; // Данные: название организации и владельцев (индекс 0 для того, чтобы из запроса выбирался первый и единственный индекс)
    $accessIDs = getaccess($db, $input['id']);
    $usersID = $db->row('SELECT login, id as UserID FROM users WHERE id NOT IN ('.$accessIDs.')');
    // $userIDs = $db->row('SELECT login, id as UserID FROM users WHERE id NOT IN ('.$accesssIDs.')');
    $response['accessID'] = $accessIDs;
    $response['usersID'] = $usersID;
    if(isset($input['org_name'])) {
        if(strlen($input['org_name']) < 1 || strlen($input['org_name']) > 255 ){
            $errors = ['message' => 'Поле "Организация" должно содержать от 1 до 255 символов', 'field' => 'organization' ];
            array_push($response['errors'], $errors);
            $response['error'] = true;
            http_response_code(400);
        }   
        $params = [
            'id' => $input['id'],
            'org_name' => $input['org_name'],

        ];
        $checkChanges = $db->column('SELECT id FROM organizations WHERE organization_name = :org_name AND id = :id', $params);

        if($checkChanges && $input['new_access'] == '' && $input['new_password'] == '') {
            $response['error'] = true;
            $response['message'] = 'Ничего не изменилось';  
            http_response_code(400);
        }
        else {
            if($input['new_access'] !== '') {
           $newaccess = $input['new_access'];
           $params = [
            'id' => null,
            'user_id'=> $input['new_access'],
            'accessed_org_id'=> $input['id'],
            'created_at'=> date("Y-m-d H:i:s"),
           ];
           $addaccess = $db->query('INSERT INTO access VALUES (:id, :user_id, :accessed_org_id, :created_at)', $params);
            }
            $changeparams = [
                'id' => $input['id'],
                'org_name' => $input['org_name'],
               
            ];
            $change = $db->query('UPDATE organizations SET organization_name = :org_name WHERE id = :id', $changeparams );
            if($change || $addaccess){
                $response['valid'] = true;
                $response['message'] = 'Изменения применены';
            }
            if($input['new_password'] !== '') {
                $newparams = [
                    'id' => $input['id'],
                    'password_hash' => $db->encryptPassword($input['new_password']),
                ];
                $checkPassword = $db->column("SELECT id from org_passwords WHERE org_id = :id AND password_hash = :password_hash", $newparams);
                if(strlen($input['new_password']) < 6 || strlen($input['new_password']) > 255){
                    $response['message'] = 'Поле "Пароль" должно содержать от 6 до 255 символов';
                    $response['error'] = true;
                    $response['valid'] = false;
                    http_response_code(400);

                }
               
            
                else {
                    $pswdparams = [
                        'id' => $input['id'],
                        'password'=> $input['new_password'],
                        'password_hash' => $db->encryptPassword($input['new_password']),
                    ];
                    $changePswd = $db->query('UPDATE org_passwords SET password = :password, password_hash = :password_hash WHERE id = :id', $pswdparams );
                    if($changePswd){
                        $response['valid'] = true;
                        $response['message'] = 'Изменения применены';
                    }
            }
        }
        }

    }
}

echo json_encode($response);
?>