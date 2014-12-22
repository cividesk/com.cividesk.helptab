<?php
// Database settings 
$credentials = array(
    'helptab' => array(
        'hostname' => 'localhost',
        'database' => 'civi_test_db',
        'username' => 'root',
        'password' => '',
    ),
    'customer' => array(
        'hostname' => 'localhost',
        'database' => 'civi_test_db',
        'username' => 'root',
        'password' => '',
    ),
    'osticket' => array(
        'hostname' => 'localhost',
        'database' => 'civi_test_db',
        'username' => 'root',
        'password' => '',
    ),
);

// Various options
$options = array(
  'production' => FALSE, // If TRUE, will check domain credentials and log all actions
  'email_ops' => 'myself@example.com', // Email address of the operations team
);