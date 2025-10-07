<?php

#mr bakkala magic code
include_once 'backend/database.php';
include_once 'backend/utility.php';
include_once 'backend/json.php';

#this is how we receive inputs
$json = file_get_contents('php://input');
$data = json_decode($json, true);

$buffer_timestamp = $data['timestamp'];
$email = $data['email'];
$block = $data['block'];

$teacherID = getTeacherId($email);
$classID = getClassID($teacherID, $block);
$roster = getRoster($classID);
$students = getUpdatedIds($buffer_timestamp, $roster);

//why is that '&' there? addresses?
foreach ($students as &$student)
{
    $student['current_room'] = getRoom($student['device_id']);
}

$new_time = currentISOTimestamp();

if($students == null){
    jsonFetchResponse(json_encode([]));
} else {
    jsonFetchResponse(json_encode(array(
        "updates" => $students,
        "timestamp" => $new_time,
        "teacher_id" => $teacherID,
        "class_id" => $classID,
        "roster" => $roster,
        "students" => $students,
    )));
}
