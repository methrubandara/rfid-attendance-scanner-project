<?php
include_once 'backend/database.php';
include_once 'backend/utility.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true); // Decode as associative array

$id = $data['id'];
$button_type = $data['buttonType'];
$time = currentISOTimestamp();
$room_device = $data['device'];
$device = getStudentDevice($id);
switch($button_type) {
    case 'nurse':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "nurse_pass" WHERE `card_id` = ?');
        break;
    case 'studyHall':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        if ($device == "bathroom_pass") {
            runQuery([(int)$id], 'UPDATE `scans` SET `device_id` = "studyHall_pass" WHERE `card_id` = ?');
        } else{
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "studyHall_pass" WHERE `card_id` = ?');
        }
        break;
    case 'frontOffice':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        if ($device == "bathroom_pass") {
            runQuery([(int)$id], 'UPDATE `scans` SET `device_id` = "frontOffice_pass" WHERE `card_id` = ?');
        } else{
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "frontOffice_pass" WHERE `card_id` = ?');
        }
        break;
    case 'latePass':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        if ($device == "bathroom_pass") {
            runQuery([(int)$id], 'UPDATE `scans` SET `device_id` = "latePass_pass" WHERE `card_id` = ?');
        } else{
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "latePass_pass" WHERE `card_id` = ?');
        }
        break;
    case 'counselor':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        if ($device == "bathroom_pass") {
            runQuery([(int)$id], 'UPDATE `scans` SET `device_id` = "counselor_pass" WHERE `card_id` = ?');
        } else{
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "counselor_pass" WHERE `card_id` = ?');
        }
        break;
    case 'bathroom':
        runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "pass" WHERE `card_id` = ?');
        if ($device == "bathroom_pass") {
            runQuery([(int)$id], 'UPDATE `scans` SET `device_id` = "bathroom_pass" WHERE `card_id` = ?');
        } else{
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "bathroom_pass" WHERE `card_id` = ?');
        }
        break;
    case 'pres':
        $status = (integer) runQuery([(int) $id], 'SELECT `in_class` FROM `status` WHERE `card_id` = ?')[0]["in_class"];
        if ($status == 1) {
            runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 0, `location` = "absent" WHERE `card_id` = ?');
            runQuery([$time,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = "absent" WHERE `card_id` = ?');
        } else {
            runQuery([(int)$id], 'UPDATE `status` SET `in_class`= 1, `location` = "present" WHERE `card_id` = ?');
            runQuery([$time,$room_device,(int)$id], 'UPDATE `scans` SET `time_stamp`= ?, `device_id` = ? WHERE `card_id` = ?');
        }
        break;
}

// Optional: Send back a response to the client
echo json_encode([
    
    "status" => "success",
    "time"=>$time ,
    "message" => "Button action processed"
    ]
);