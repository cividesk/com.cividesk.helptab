<?php
/**
 * This is a home page.
*/

//Get the content data
if( isset($_GET['action']) && $_GET['action'] == 'getContent'){       
    include_once 'getContent.php';        
}

//Redirect to destination and logged the data
if( isset($_GET['action']) && $_GET['action'] == 'redirect'){                
    include_once 'redirect.php';        
}

?>
