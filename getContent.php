<?php 
include_once 'settings.tpl.php';
include_once 'utils/database.php';

//Initialize PDO object
$dbh = open_database('helptab'); 
$contextArr = array();
$records = array('result' => '', 'counts' => '0');


if(isset($_REQUEST['civicrm_contex'])) {
    $context = explode('/', $_REQUEST['civicrm_contex']);
}

 if (!empty($context ) && $context[0] == 'civicrm' ) {
    //Build query for context
    for ($i = 0; $i < count($context); $i++) {       
        $contextArr[':context_'.$i] = implode("/", array_slice($context, 0, $i + 1));
        }       

    $where_params = implode(',', array_keys($contextArr));
// Prepare database statements for getting the content
    $stmt         = $dbh->prepare("SELECT * FROM help_item INNER JOIN help_mapping ON help_item.id = help_mapping.item_id     
                         WHERE help_mapping.context IN ( {$where_params} ) ");

    // Note : can not bind mutiple value for IN clause using single bind. Solution : Iterate to bind each IN Clause value
    foreach ($contextArr as $key => $value) {
        $stmt->bindValue($key, $value, PDO::PARAM_STR);
    }

    $stmt->execute();
    $records['counts'] = $stmt->rowCount();
    $records['result'] = $stmt->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_UNIQUE);
}
header('Access-Control-Allow-Origin: *');
echo json_encode($records);
exit();
unset($dbh);
?> 