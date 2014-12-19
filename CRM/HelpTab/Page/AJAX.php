<?php

/**
 * This class contains all the function that are called using AJAX
 */
class CRM_HelpTab_Page_AJAX {


  static function getItems() {
    /*
    try {
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, 'https://api.cividesk.com/helptab/getContent.php');
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, array(
        'cividesk_key'  => $_REQUEST['cividesk_key'],
        'civicrm_version'=> $_REQUEST['civicrm_version'],
      ));
      $curl_response = curl_exec($curl);
      // Deal with curl errors
      if(curl_errno($curl)) {
        throw new Exception('Curl error: ' . curl_errno($curl) . ' '. curl_error($curl) );
      }
      if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        throw new Exception('error occurred during curl exec. Additional info: ' . print_r($info, TRUE) );
      }
      curl_close($curl);
    } catch( Exception $e ) {
      //
    }
    */
    $records = array();
    $records[] = array('url' => 'https://google.com', 'item_id' => '1', 'title' => 'Google', 'text' => 'Google Gmail');
    $records[] = array('url' => 'https://yahoo.com', 'item_id' => '2', 'title' => 'Yahoo', 'text' => 'Yahoo Seach and mail');
    $records[] = array('url' => 'https://microsoft.com', 'item_id' => '3', 'title' => 'Microsoft', 'text' => 'Microsoft OS');
    $records[] = array('url' => 'https://cividesk.com', 'item_id' => '4', 'title' => 'CiviDesk', 'text' => 'CiviDesk CRM');
    $records[] = array('url' => 'https://wikipedia.com', 'item_id' => '1', 'title' => 'Wikipedia', 'text' => 'Wikipedia');
    $records[] = array('url' => 'https://gmail.com', 'item_id' => '2', 'title' => 'Gmail', 'text' => 'search email');
    $records[] = array('url' => 'https://skype.com', 'item_id' => '3', 'title' => 'Skype', 'text' => 'Video Call');
    $records[] = array('url' => 'https://join.me', 'item_id' => '4', 'title' => 'Join Me', 'text' => 'screen sharing');
    $output = array( 'result' => $records, 'total' => count($records));

    echo json_encode($output);
    CRM_Utils_System::civiExit();
  }

}
