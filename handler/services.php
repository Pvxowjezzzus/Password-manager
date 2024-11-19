<?php 
session_start();
require_once '../libs/DB.php';
$db = new DB();
$checkorgID = $db->column("SELECT id FROM `organizations` WHERE id = '".$_GET['id']."'");
if(!$checkorgID) {
    header("HTTP/1.0 404 Not Found");
    exit();
}
  $_SESSION['org_id'] = $checkorgID;

$services = $db->row("SELECT * FROM `services` WHERE org_id = '".$_GET['id']."'");


function getOwners($db, $org_id){
    $owners = $db->row("SELECT user_id FROM owners WHERE  owned_org_id = ".$org_id."");
    $ownersId = [];
    foreach($owners as $owner) {
      array_push($ownersId, $owner['user_id']); 
    }
    // $owners = implode('', $owners);
     return implode(', ',$ownersId);
}
?>