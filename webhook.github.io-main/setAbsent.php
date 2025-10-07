<?php
    include_once 'backend/database.php';
    include_once 'backend/utility.php';
    include_once 'backend/json.php';

    #this is how we receive inputs
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $card_id = $data['card_id'];

    $time = currentISOTimestamp();
    runQuery([$card_id], 'UPDATE `status` SET `location`="absent" WHERE `card_id` = ?');
    runQuery([$card_id], 'UPDATE `status` SET `in_class` = 0 WHERE `card_id` = ?');
    runQuery([$card_id], 'UPDATE `scans` SET `device_id`="absent" WHERE `card_id` = ?');
    runQuery([$time], 'UPDATE `scans` SET `time_stamp`=?');

?>
