<?php
include '../settings.php';
include '../utils/database.php';

$sources = array('book', 'cividesk_kb');

if ($argc < 2 || !($argv[1] == 'all' || in_array($argv[1], $sources))) {
  $options = implode(', ', $sources) . ' or all';
  echo <<<EOT
Usage is: $argv[0] <source>
where <source> is one of: $options.
EOT;
  echo PHP_EOL;
  exit();
}

$dbh = open_database('helptab');
  
if ($argv[1] == 'all') {
  foreach ($sources as $source) {
    load_data($dbh, $source);
  }
} else {
  load_data($dbh, $argv[1]);
}

function load_data( $dbh, $source ) {
  echo "Loading $source: ";
  
  // Load source information in $data array
  switch ($source) {
    case 'book':
	  $data = load_csv( 'book.csv' );
	  foreach ($data as &$line) {
	    $subdir = (empty($line['version']) ? 'current' : "version/$line[version]");
		$line['url']  = "http://book.civicrm.org/user/$subdir/$line[id]";
		$line['text'] = <<<EOT
A chapter of the User and Administrator Guide, the official book on CiviCRM. This is a guide to the set up and every day use of
CiviCRM, including planning, configuration, everyday use, reporting and more.
This book is developed and maintained by the CiviCRM community.
EOT;
	  }
	  break;
	case 'cividesk_kb':
	  $data = load_db('osticket','
SELECT faq_id as id, ispublished as status, question as title, notes as context
  FROM ost_faq
 WHERE notes !=""
      ');
	  foreach ($data as &$line) {
        $line['url']  = "https://my.cividesk.com/help/kb/faq.php?id=$line[id]";
        $line['text'] = <<<EOT
An article of the Cividesk Knowledge Base. This Knowledge Base is developed and maintained by Cividesk for its Software-as-a-Service
customers, but limited access is offered after a simple registration.
EOT;
      }
	  break;
	default:
	  echo 'ERROR: Unknown source';
	  return;
  }
  
  // Prepare database statements
  $stmt = array(
    'search' => $dbh->prepare("SELECT id FROM help_item WHERE source = :source AND external_id = :external_id"),
    'insert' => $dbh->prepare("INSERT INTO help_item (source, external_id, title, url, text) VALUES (:source, :external_id, :title, :url, :text)"),
	'update' => $dbh->prepare("UPDATE help_item SET title = :title, url = :url, text = :text WHERE id = :id"),
	'status' => $dbh->prepare("UPDATE help_item SET status = :status WHERE id = :id AND status >= 0"),
	'mapins' => $dbh->prepare("INSERT INTO help_mapping (context, item_id) VALUES (:context, :item_id)"),
  );
  
  // Process each record in turn
  $stmt['search']->bindParam(':source', $source);
  $stmt['insert']->bindParam(':source', $source);
  foreach ($data as &$line) {
    // First insert the item
    $stmt['search']->bindParam(':external_id', $line['id']);
	$stmt['search']->execute();
	if ($id = $stmt['search']->fetchColumn()) {
	  // The item was already existing
	  echo 'u';
	  $stmt['update']->bindParam(':id'   , $id);
	  $stmt['update']->bindParam(':title', $line['title']);
	  $stmt['update']->bindParam(':url'  , $line['url']);
	  $stmt['update']->bindParam(':text' , $line['text']);
  	  $stmt['update']->execute();
	} else {
	  // Create the item
	  echo 'i';
	  $stmt['insert']->bindParam(':external_id', $line['id']);
	  $stmt['insert']->bindParam(':title'      , $line['title']);
	  $stmt['insert']->bindParam(':url'        , $line['url']);
	  $stmt['insert']->bindParam(':text'       , $line['text']);
  	  $stmt['insert']->execute();
	  $id = $dbh->lastInsertId();
    }
	// Update the status (afterwards so we can disable items on our end)
	if (isset($line['status'])) {
	  $stmt['status']->bindParam(':id'    , $id);
	  $stmt['status']->bindParam(':status', $line['status']);
  	  $stmt['status']->execute();
	}
    // Then map the contexts to this item
    $dbh->exec("DELETE FROM help_mapping WHERE item_id = $id");
    $stmt['mapins']->bindParam(':item_id', $id);
    foreach(explode('|', $line['context']) as $context) {
  	  if ($context = trim($context)) {
	    echo '.';
	    $stmt['mapins']->bindParam(':context', $context);
  	    $stmt['mapins']->execute();
      }
    }
  }
  
  echo PHP_EOL;
}

function load_csv( $filename ) {
  $data = array();
  if (($handle = fopen($filename, "r")) !== FALSE) {
    // read column titles
	$column = fgetcsv($handle, 5000, ",");
	// read all lines
    while (($line = fgetcsv($handle, 5000, ",")) !== FALSE) {
	  $item = array();
	  foreach ($line as $id => $value) {
	    if (!empty($column[$id])) {
	      $item[$column[$id]] = $value;
		}
      }
	  if (count($item)) {
	    $data[] = $item;
      }
    }
    fclose($handle);
  }
  return $data;
}

function load_db( $database, $query ) {
  $data = array();
  $conn = open_database($database);
  foreach ($conn->query($query) as $row) {
	$data[] = $row;
  }
  return $data;
}