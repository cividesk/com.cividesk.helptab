<?php

function open_database( $name ) {
  global $credentials;
  
  $dbh = new PDO( "mysql:dbname={$credentials[$name]['database']};host={$credentials[$name]['hostname']}",
                  $credentials[$name]['username'], $credentials[$name]['password'] );
  // An exception is raised if the previous statement fails
  return $dbh;
}