<?php

/**
 * This class contains all the function that are called using AJAX
 */
class CRM_HelpTab_Page_AJAX {


  static function getItems() {
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
    echo json_encode($curl_response);
    CRM_Utils_System::civiExit();
  }

}
