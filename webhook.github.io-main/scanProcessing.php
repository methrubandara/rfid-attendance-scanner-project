<?php
  
  #mr bakkala magic code
  include_once 'backend/database.php';
  include_once 'backend/utility.php';

  #this is how we receive inputs
  $json = file_get_contents('php://input');
  $data = json_decode($json);
  #the important info
  $id = (integer) "$data->data";
  $device = "$data->coreid";
  #the recommended thing to do is store in UTC time
  #convert to EST only for displaying
  $time= "$data->published_at";

  
  update($id, $device, $time);

  
  #fetch value
  $allow = runQuery([(integer) $id], 'SELECT `allowed` FROM `permission` WHERE `card_id` = ?');

  #send back the allow value
  echo json_encode(array(
    "allow" => $allow[0]["allowed"],
    "device" => $device
  ));
    

?>
