<?php

include_once 'backend/database.php';
include_once 'backend/utility.php';
include_once 'backend/json.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);


$card_id = $data['card_id'];
$class_id = $data['class_id'];
$old_status = $data['old_status'];
$new_status = $data['new_status'];
$time_stamp = $data['time_stamp'];
$old_room = $data['old_room'];
$new_room = $data['new_room'];

if($old_status == "present" && $new_status == "pass") {
    $b_stat = 0;
    if($new_room == "Bathroom") {
        $b_stat = 1;
    }
    runQuery([(integer) $card_id, $time_stamp, $class_id, (integer) $b_stat], 'INSERT INTO `time_wasted`(`card_id`, `start_time`, `class_id`,`bathroom`) VALUES (?,?,?,?)');
}

if($new_status == "present" && $old_status == "pass") {
    $b_stat = 0;
    if($old_room == "Bathroom") {
        $b_stat = 1;
    }
    runQuery([$time_stamp,(integer) $card_id, $class_id, $b_stat], 'UPDATE `time_wasted` SET `end_time` = ? WHERE `card_id` = ? AND `class_id` = ? AND `bathroom` = ? AND `end_time` IS NULL');
}