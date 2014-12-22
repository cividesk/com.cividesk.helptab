<?php 

include_once 'settings.php';
include_once '/utils/database.php';

//Initialize PDO object
$dbh = open_database('helptab');
$action = $context = $itemId = $domain = NULL;    
$isLgged    = false;

// Prepare database statements for insert log information
$stmt = array(
    'insert' => $dbh->prepare("INSERT INTO help_log (time, action, context, item_id, domain) VALUES (:time, :action, :context, :item_id, :domain)")
);

//Prepared insertion data
$time       = date('Y-m-d H:i:s');
$action     = $_GET['url'];
$context    = $_GET['context'];
$itemId     = $_GET['itemId'];

//Bind the parameter to insert in DB
$dataInsert = $stmt['insert']->execute(array(':time' => $time, ':action' => $action, ':context' => $context, ':item_id' => $itemId, ':domain' => $domain)); 

if($dataInsert){
    $isLgged = true;
}
echo json_encode($isLgged);
exit;

?> 