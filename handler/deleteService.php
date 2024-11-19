<?php 
require_once '../libs/DB.php';
$db = new DB();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$response = ['ok' => false, 'error' => false,'message' => ''];

if(isset($input['id'])) {
    $del_service = $db->query("DELETE FROM services WHERE id = ".$input['id'].""); 
    if(!$del_service) {
        $response['error'] = true;
        $response['message'] = "Ошибка удаления сервиса";
        http_response_code(400);
    }
    $response['ok'] = true;
    $response['message'] = 'Сервис удален';
}

echo json_encode($response);
?>