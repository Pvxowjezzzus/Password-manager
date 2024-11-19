<?php 
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$response = ['ok' => false, 'error' => false,'message' => ''];

if(isset($input['id'])) {
    $del_org = $db->query("DELETE FROM organizations WHERE id = ".$input['id'].""); 
    $del_pswd = $db->query("DELETE FROM org_passwords WHERE org_id = ".$input['id']."");
    if(!$del_org && !$del_pswd) {
        $response['error'] = true;
        $response['message'] = "Ошибка удаления организации";
        http_response_code(400);
    }
    $response['ok'] = true;
    $response['message'] = 'Компания удалена';
}

echo json_encode($response);
?>