<?php 

include_once 'settings.php';

$realUrl    = $_POST['real_url'];
$virtualUrl = $_POST['virtual_url'];
$isLgged    = false;

// Prepare database statements for insert log information
$stmt = array(
    'insert' => $dbh->prepare("INSERT INTO help_log (time, action, context, item_id, domain) VALUES (:time, :action, :context, :item_id, :domain)")
);

//Prepared insertion data
$time       = date('Y-m-d H:i:s');
$action     = $realUrl; 
//@todo - Get the context from url
$context    = 'civicrm/admin';
$itemId     = $_GET['itemId'];
//@todo - Domain will be generated from key parameters
$domain     = 'NULL';

//Bind the parameter to insert in DB
$dataInsert = $stmt['insert']->execute(array(':time' => $time, ':action' => $action, ':context' => $context, ':item_id' => $itemId, ':domain' => $domain)); 

if($dataInsert){
    $isLgged = true;
}
echo json_encode($isLgged);

?> 