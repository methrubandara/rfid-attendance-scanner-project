<?php
    include_once 'backend/database.php';
    include_once 'backend/utility.php';

    $time = currentISOTimestamp();
    runQuery([], 'UPDATE `status` SET `location`="transition"');
    runQuery([], 'UPDATE `status` SET `in_class` = 0');
    runQuery([], 'UPDATE `scans` SET `device_id`="transitioning"');
    runQuery([$time], 'UPDATE `scans` SET `time_stamp`=?');

    echo "";
?>
