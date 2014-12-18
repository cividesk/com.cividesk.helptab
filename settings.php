<?php
// Database settings 
$db = array(
    'helptab' => array(
        'hostname' => 'localhost',
        'database' => 'helptab',
        'username' => 'root',
        'password' => '',
    ),
);

//Created PDO object
try {
    $dbh = new PDO('mysql:dbname='.$db['helptab']['database'].';host='.$db['helptab']['hostname'],
        $db['helptab']['username'], $db['helptab']['password']);
} catch (PDOException $e) {
    echo "Failed to get DB handle: ".$e->getMessage()."\n";
    exit;
}

// Various options
$options = array(
  'production' => FALSE, // If TRUE, will check domain credentials and log all actions
  'email_ops' => 'myself@example.com', // Email address of the operations team
);