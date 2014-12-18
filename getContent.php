<?php 

include_once 'settings.php';

$records = array();

// Prepare database statements for getting the content
$stmt = array(
    'search' => $dbh->prepare("SELECT * FROM help_item "
                                . "INNER JOIN help_mapping "
                                . "ON help_item.id = help_mapping.item_id "
                                . "WHERE help_mapping.context LIKE :source"
                            )
);

//Function to get parameters from URL
$source = getContext();

$stmt['search']->execute(array(':source' => '%'.$source.'%'));
$records['counts'] = $stmt['search']->rowCount();
$records['result'] = $stmt['search']->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_UNIQUE);
echo json_encode($records);

unset($dbh);


//Get Params ( context ) from URL
function getContext() {
    
    $params = array();
    if (!$params) {
        return;
    }

    //@todo - Temporory Hardcode parameter need to make dynamic
    $params = 'civicrm/admin';
    return $params;
}
?> 