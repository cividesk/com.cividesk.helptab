<?php 

include_once 'settings.php';
include_once '/utils/database.php';

//Initialize PDO object
$dbh = open_database('helptab'); 
$records = $result = $context = array();

if(isset($_GET['context'])) {
    $contextArr = explode('/', $_GET['context']);
}

for($i=0; $i<count($contextArr); $i++ ){           
        if($i == 0){
            $result[] = $contextArr[$i];
        }else {
            $result[] = $result[$i-1].'/'.$contextArr[$i];          
        }       
}

$context = "'" . implode("','", $result) . "'";

// Prepare database statements for getting the content
$stmt = array(
    'search' => $dbh->prepare("SELECT * FROM help_item "
                                . "INNER JOIN help_mapping "
                                . "ON help_item.id = help_mapping.item_id "
                                . "WHERE help_mapping.context IN (" . $context . ")"
                            )
);

$stmt['search']->execute();

$records['counts'] = $stmt['search']->rowCount();
$records['result'] = $stmt['search']->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_UNIQUE);

echo json_encode($records);
exit();
unset($dbh);

?> 