<?php

  #mr bakkala magic code
  include_once 'backend/database.php';
  include_once 'backend/utility.php';

  #this is how we receive inputs
  $json = file_get_contents('php://input');
  $data = json_decode($json);

  $timestamp = $data['timestamp'];  //most recent website update
  $device_id = $data['device-id'];   //id of room

  $new_info = json_encode(getNewerScans($timestamp));


  ?>