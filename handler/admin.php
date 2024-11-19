<?php
require_once '../libs/DB.php';
$db = new DB();
$organizations = $db->query('SELECT * FROM `organizations`');

$users = $db->query('SELECT * FROM users');

function getAccess($db, $org_id){
    $accesses = $db->row("SELECT user_id FROM access WHERE  accessed_org_id = ".$org_id."");
    $accessId = [];
    foreach($accesses as $access) {
      array_push($accessId, $access['user_id']); 
    }
    // $owners = implode('', $owners);
     return implode(', ',$accessId);
}

function checkAccess($db, $org_id){
  $checkOwner = $db->column('SELECT user_id FROM access WHERE  accessed_org_id = "'.$org_id.'" AND user_id = "'.$_SESSION['user_id'].'"');
  if ($checkOwner) {
   return true;
 }

}
function checkServicesExist($db, $org_id) {
  $checkService = $db->column('SELECT user_id FROM services WHERE org_id = "'.$org_id.'"');
  if($checkService)
    return true;
}
?>