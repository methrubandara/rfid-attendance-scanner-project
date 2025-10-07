<?php
    #idk why the below comment is displayed tothe console but idc rn
?>
<!--

So this code has two methods of operation: GET amd SET
It uses the _SESSION variable
meaning you must start the session first

Important:
    All of teh settings are stored in the _SESSION php variable
    inside the array settings, so that they are all bundle together

GET allows you to get the current value of a setting
    if it hasn't been set, it returns ""
SET allows you to set the current value of a setting

-->



<?php

#mr bakkala magic code
include_once 'backend/database.php';
include_once 'backend/utility.php';


#Gets credential
$data = json_decode(file_get_contents('php://input'), true);

$command = $data['command'];

$action = $command['action'];

//most important line here
session_start();

if(!isset($_SESSION['settings'])){
    $_SESSION['settings'] = array();
}
$settings = $_SESSION['settings'];


if($action == "SET"){
    $setting = $command["setting"];
    $value = $command["value"];
    //just beautiful
    //echo json_encode(array($setting, $value));
    $settings[$setting] = $value;
    $_SESSION['settings'] = $settings;
} elseif ($action = "GET") {
    $setting = $command['setting'];
    if(isset($settings[$setting])){
        echo $settings[$setting];
    } else {
        //idk what to return, this works I guess
        echo "yay!";
    }
}

?>
