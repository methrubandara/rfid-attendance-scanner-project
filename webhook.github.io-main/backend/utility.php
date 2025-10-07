<?php

function var_visualize(...$variables)
{
    foreach ($variables as $v)
    {
        highlight_string("\n\n<?php \n" . var_export($v, true) . "\n?>");
    }
}
function var_vis(...$variables)
{
    return var_visualize(...$variables);
}

function var_viz(...$variables)
{
    return var_visualize(...$variables);
}

function vv(...$variables)
{
    return var_visualize(...$variables);
}

#gets current day
function getDay()
{
    $startDate = new DateTime('2023-08-29'); // Start from today
    $endDate = new DateTime('2024-06-18'); // End date is June 18, 2024
    $interval = new DateInterval('P1D'); // 1 day interval

    $weekdayDayNumbers = array();
    $holidays = [
        "September 4, 2023",
        "October 9, 2023",
        "November 10, 2023",
        "November 23, 2023",
        "November 24, 2023",
        "December 18, 2023",
        "December 19, 2023",
        "December 20, 2023",
        "December 21, 2023",
        "December 22, 2023",
        "December 25, 2023",
        "December 26, 2023",
        "December 27, 2023",
        "December 28, 2023",
        "December 29, 2023",
        "January 1, 2024",
        "January 15, 2024",
        "February 19, 2024",
        "February 20, 2024",
        "February 21, 2024",
        "February 22, 2024",
        "February 23, 2024",
        "April 15, 2024",
        "April 16, 2024",
        "April 17, 2024",
        "April 18, 2024",
        "April 19, 2024",
        "April 20, 2024",
        "May 27, 2024",
        "June 19, 2024",
    ];

    $dayNumber = 1;

    while ($startDate <= $endDate)
    {
        $dayOfWeek = $startDate->format('N'); // N returns 1 for Monday, 7 for Sunday
        $dateStr = $startDate->format('F j, Y'); // Month day, year. ex: "December 16, 2023"

        // Check if it's a weekday (Monday to Friday) and not a holiday
        if ($dayOfWeek >= 1 && $dayOfWeek <= 5 && !in_array($dateStr, $holidays))
        {
            $weekdayDayNumbers[$dateStr] = $dayNumber;
            $dayNumber = ($dayNumber % 8) + 1; // Rotate through day numbers 1 to 8
        }
        else
        {
            $weekdayDayNumbers[$dateStr] = 0;
        }

        $startDate->add($interval); // Move to the next day
    }

    // Now $weekdayDayNumbers contains an associative array mapping weekdays to day numbers
    // foreach ($weekdayDayNumbers as $date => $dayNumber) {
    //     echo "$date: Day Number $dayNumber<br>";
    // }

    $today = new DateTime();
    $todayStr = $today->format('F j, Y');
    //if it is a weekend or a holiday, this code is going to break, as the element does not exist
    //above set day to 0 so we know there is no school
    $dayNumber = $weekdayDayNumbers[$todayStr];

    return $dayNumber;
}

#gets current block
//for non school days, the input could be a day 0
function getBlock($day)
{
    //if it is a non school day, what block should this return?
    if ($day == 0)
    {

        //hopefully this format works
        //its funny because blocks are always A-H, 1, 2 or 12
        //$block = "Z23";
        //just looks better on teh website lol
        return "You're Welcome!";
    }
    $rocky = "7:40:00"; //easter egg
    $woodchips = "3:15:00"; //real easter egg
    $times = [new DateTime("7:40:00"),
        new DateTime("8:40:00"),
        new DateTime("9:29:00"),
        new DateTime("10:18:00"),
        new DateTime("11:07:00"),
        new DateTime("12:22:00"),
        new DateTime("13:11:00"),
        new DateTime("13:56:00"),
        new DateTime("14:45:00"),
    ];
    $now = new DateTime();
    $periods = ["period1", "period2", "period3", "period4", "period5", "period6", "period7", "period8"];
    for ($i = 0; $i < count($times) - 1; $i++)
    {
        if ($now > $times[$i] && $now < $times[$i + 1])
        {
            $period = $periods[$i];
            //if it is a non school day, this query returns null because it is a day 0
            $block = runQuery([(int) $day], 'SELECT `' . $period . '`  FROM `periods` WHERE `day` = ?');
            return $block[0][$period];
        }
    }
    //this is annoying, already has a day 0
    if ((int) $day == 8)
    {
        $day = 0;
    }
    $block = runQuery([((int) $day) + 1], 'SELECT `period1`  FROM `periods` WHERE `day` = ?');
    return $block[0]['period1'];
}

function getTeacherId($email)
{
    $query = runQuery([$email], 'SELECT `teacher_id` FROM `teachers` WHERE `email` = ?');
    return $query[0]["teacher_id"];
}

#gets class_id from teacher id and block
function getClassID($teacher_id, $block)
{
    $query = runQuery([(int) $teacher_id], 'SELECT `class_id` FROM `section` WHERE `teacher_id` = ? AND `block` LIKE "%' . $block . '%"');
    return $query[0]["class_id"];
}

function getClassName($classID)
{
    $query = runQuery([(int) $classID], 'SELECT `class_name` FROM `section` WHERE `class_id` = ?');
    return $query[0]["class_name"];
}

function getDevice($classID)
{
    $query = runQuery([(int) $classID], 'SELECT `device_id` FROM `section` WHERE `class_id` = ?');
    return $query[0]["device_id"];
}

function getStudentDevice($id)
{
    $query = runQuery([(int) $id], 'SELECT `device_id` FROM `scans` WHERE `card_id` = ?');
    return $query[0]["device_id"];
}

function getRoster($classID)
{
    $result = runQuery([(int) $classID], 'SELECT * FROM `enrollment` LEFT JOIN `students` ON (enrollment.card_id = students.card_id) LEFT JOIN `scans` ON (enrollment.card_id = scans.card_id) LEFT JOIN `status` ON (enrollment.card_id = status.card_id) WHERE `class_id` = ? ORDER BY `last_name`');
    return $result;
}
function compare_names($a, $b)
{
    return $a->last_name - $b->last_name;
}

function parseName($first, $last)
{
    echo "<div class= 'r'>";
    echo $first . " " . $last;
    echo "</div>";
}

function getStudentObject($card_id) {
    return runQuery([(int) $card_id], 'SELECT * FROM `students` LEFT JOIN `scans` ON (students.card_id = scans.card_id) LEFT JOIN `status` ON (students.card_id = status.card_id) WHERE `card_id` = ?')[0];
}

function parseTime($scan1)
{
    if ($scan1 != null)
    {
        $dateTime1 = explode("T", $scan1);
        $dateTime2 = explode("-", $dateTime1[0]);
        $date = substr($dateTime1[1], 0, -8) . " " . $dateTime2[1] . "/" . $dateTime2[2] . "/" . $dateTime2[0];
        // Convert the date string to a Unix timestamp
        $timestamp = strtotime("$date UTC");
        // Format the timestamp in 12-hour format without leading zeros for the hour
        echo date('g:i a', $timestamp);
    }
    else
    {
        echo " ";
    }

}

/*function getStudentInfo ($id) {
    echo "\r\n Email: " . (runQuery([(int) $id], 'SELECT `email` FROM `students` WHERE `card_id` = ?')[0]["email"]);
    echo "\r\n Parent's Email: (PULL FROM POWERSCHOOL)";
    echo "\r\n Grade: " . (runQuery([(int) $id], 'SELECT `Grade` FROM `students` WHERE `card_id` = ?')[0]["Grade"]);;
    echo "\r\n Number of Tardies: (PULL FROM POWERSCHOOL)";
    echo "\r\n Wanted By Admin: (PULL FROM POWERSCHOOL)";
}*/
function getStudentInfo ($id) {
    echo "Card ID: " . $id . "<br>";
    echo "Email: " . (runQuery([(int) $id], 'SELECT `email` FROM `students` WHERE `card_id` = ?')[0]["email"]) . "<br>";
    echo "Parent's Email: (PULL FROM POWERSCHOOL)" . "<br>";
    echo "Grade: " . (runQuery([(int) $id], 'SELECT `Grade` FROM `students` WHERE `card_id` = ?')[0]["Grade"]) . "<br>";
    echo "Number of Tardies: (PULL FROM POWERSCHOOL)" . "<br>";
    echo "Wanted By Admin: (PULL FROM POWERSCHOOL)" . "<br>";
}

function status($student)
{
    //return $student['location'];
    return (runQuery([(int) $student['card_id']], 'SELECT `location` FROM `status` WHERE `card_id` = ?')[0]["location"]);
}

function update($id, $newDevice, $time)
{
    //all the magic attendance code
    $currentDevice = (runQuery([(int) $id], 'SELECT `device_id` FROM `scans` WHERE `card_id` = ?'))[0]["device_id"];
    runQuery([(integer) $id, (integer) $id], 'INSERT INTO `status` (`card_id`, `in_class`,`location`) VALUES(?, 1,"present") ON DUPLICATE KEY UPDATE `card_id`=?');
    $currentStatus = (runQuery([(int) $id], 'SELECT `in_class` FROM `status` WHERE `card_id` = ?'))[0]["in_class"];
    if ($newDevice == $currentDevice)
    {
        if ((int) $currentStatus == 1)
        {
            runQuery([(int) $id], 'UPDATE `status` SET `in_class`= 0,`location`="pass" WHERE `card_id` = ?');
            $newDevice = "bathroom_pass";
        }
        else
        {
            runQuery([(int) $id], 'UPDATE `status` SET `in_class`= 1,`location`="present" WHERE `card_id` = ?');
        }
    }
    else
    {
        runQuery([(int) $id], 'UPDATE `status` SET `in_class`= 1,`location`= "present" WHERE `card_id` = ?');
    }
    runQuery([(integer) $id, $time, $newDevice], 'INSERT INTO `scans_log`(`card_id`, `time_stamp`, `device_id`) VALUES (?,?,?)');
    runQuery([(integer) $id, $time, $newDevice, $time, $newDevice], 'INSERT INTO `scans` (`card_id`, `time_stamp`, `device_id`) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE `time_stamp`=?, `device_id`=?');
    runQuery([(integer) $id, 1, (integer) $id], 'INSERT INTO `permission` (`card_id`, `allowed`) VALUES(?, ?) ON DUPLICATE KEY UPDATE `card_id`=?');
    //runQuery([(integer) $id, $time, $newDevice, $time, $newDevice], 'INSERT IGNORE INTO `status` (`card_id`, `in_class`, `location`) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE `time_stamp`=?, `device_id`=?');

    //this will allow the page to refresh after a person gets scanned in
    //will happen after every scan but oh well
    //only if you are currently on the webpage
    //$page = $_SESSION['user_info']['page'];
    //if($page == "classView"){
    //    fetch("classView.php");
    //}
}

function sessionIdAndStudentId($sessionId, $email)
{
    //add an expiration time to store as well
    //can access and use this later for cybersecurity purposes
    //create a new database that connects sessionid and amsa email
    //check if the sessionid is in the database, if not:
    //basically use the email to find either the student or teacher id (amsa id)

    //use session here?
    runQuery([$sessionId, $email], 'INSERT INTO `user_sessions` (`session_id`, `email`) VALUES(?, ?)');
}

function nurseButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-clipboard2-pulse svgIcon" viewBox="0 0 16 16">';
    echo '<path d="M9.5 0a.5.5 0 0 1 .5.5.5.5 0 0 0 .5.5.5.5 0 0 1 .5.5V2a.5.5 0 0 1-.5.5h-5A.5.5 0 0 1 5 2v-.5a.5.5 0 0 1 .5-.5.5.5 0 0 0 .5-.5.5.5 0 0 1 .5-.5z"/>';
    echo '<path d="M3 2.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 0 0-1h-.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1H12a.5.5 0 0 0 0 1h.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5z"/>';
    echo '<path d="M9.979 5.356a.5.5 0 0 0-.968.04L7.92 10.49l-.94-3.135a.5.5 0 0 0-.926-.08L4.69 10H4.5a.5.5 0 0 0 0 1H5a.5.5 0 0 0 .447-.276l.936-1.873 1.138 3.793a.5.5 0 0 0 .968-.04L9.58 7.51l.94 3.135A.5.5 0 0 0 11 11h.5a.5.5 0 0 0 0-1h-.128z"/>';
    echo '</svg>';
}

function studyHallButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-book svgIcon" viewBox="0 0 16 16">';
    echo '<path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>';
    echo '</svg>';
}

function frontOfficeButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-building svgIcon" viewBox="0 0 16 16">';
    echo '<path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>';
    echo '<path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>';
    echo '</svg>';
}

function counselorButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-person-raised-hand svgIcon" viewBox="0 0 16 16">';
    echo '<path d="M6 6.207v9.043a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H6.236a.998.998 0 0 1-.447-.106l-.33-.165A.83.83 0 0 1 5 2.488V.75a.75.75 0 0 0-1.5 0v2.083c0 .715.404 1.37 1.044 1.689L5.5 5c.32.32.5.754.5 1.207"/>';
    echo '<path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>';
    echo '</svg>';
}

function latePassButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-hourglass-bottom svgIcon" viewBox="0 0 16 16">';
    echo '<path d="M2 1.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1-.5-.5m2.5.5v1a3.5 3.5 0 0 0 1.989 3.158c.533.256 1.011.791 1.011 1.491v.702s.18.149.5.149.5-.15.5-.15v-.7c0-.701.478-1.236 1.011-1.492A3.5 3.5 0 0 0 11.5 3V2z"/>';
    echo '</svg>';
}

function bathroomButton()
{
    echo '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="45" height="45" id="Icons" viewBox="0 0 32 32" xml:space="preserve" class = "svgIcon">';
    echo '<style type="text/css">';
    echo '</style>';
    echo '<path class="st0" d="M25,18H7c-1.1,0-2-0.9-2-2v0c0-1.1,0.9-2,2-2h18c1.1,0,2,0.9,2,2v0C27,17.1,26.1,18,25,18z"/>';
    echo '<path class="st0" d="M25,18c0,5-4,9-9,9s-9-4-9-9"/>';
    echo '<polyline class="st0" points="21.7,25 23,31 9,31 10.3,25 "/>';
    echo '<path class="st0" d="M24,14H8V5c0-2.2,1.8-4,4-4h8c2.2,0,4,1.8,4,4V14z"/>';
    echo '<line class="st0" x1="12" y1="5" x2="14" y2="5"/>';
    echo '</svg>';
}

function getRoom($device)
{
    return (runQuery([$device], 'SELECT `room` FROM `room_number` WHERE `device_id`= ?')[0]["room"]);
}

function change($student)
{
    $id = $student['card_id'];
    $status = $student['in_class'];
    $now = new Datetime();
    if ((integer) $status == 1)
    {
        runQuery([$id], 'UPDATE `status` SET `location`="transition" WHERE `card_id`= ?');
        runQuery([$id], 'UPDATE `status` SET `in_class` = 0 WHERE `card_id`= ?');
        runQuery([$id], 'UPDATE `scans` SET `time_stamp`=NULL WHERE `card_id`= ?');
    }
    if ((integer) $status == 0)
    {
        runQuery([$id], 'UPDATE `status` SET `location`="present" WHERE `card_id`= ?');
        runQuery([$id], 'UPDATE `status` SET `in_class` = 1 WHERE `card_id`= ?');
        runQuery([$now, $id], 'UPDATE `scans` SET `time_stamp`=? WHERE `card_id`= ?');
    }

    function getNewerScans($time_stamp)
    {
        //do a little query here
        //using timestamp and device id,
        //go to scans table, not scans log
        //return all scans that match device id and are newer than the timestamp
        //json encode it later

        $result = runQuery([$time_stamp], 'SELECT `card_id` FROM `scans` WHERE `time_stamp`>=?');
    }

}

function currentISOTimestamp()
{
    date_default_timezone_set('UTC');

    // Get the current UTC time in ISO 8601 format
    return (new DateTime())->format('Y-m-d\TH:i:s.u\Z');

}

function convertEasternToUTC($timestamp)
{
    $dateTime = new DateTime($timestamp, new DateTimeZone('America/New_York'));
    $dateTime->setTimezone(new DateTimeZone('UTC'));
    return $dateTime->format('Y-m-d\TH:i:s.u\Z');
}

function convertUTCToEastern($timestamp)
{
    $dateTime = new DateTime($timestamp, new DateTimeZone('UTC'));
    $dateTime->setTimezone(new DateTimeZone('America/New_York'));
    return $dateTime->format('Y-m-d\TH:i:s.u\E');
}

function getUpdatedIds($timestamp, $roster)
{
    //returns array of card ids
    //time in microseconds
    $newer_ids = array_map(function ($x)
    {
        return $x['card_id'];},
        runQuery([$timestamp], 'SELECT `card_id` FROM `scans` WHERE `time_stamp`>=?')
    ); //get the newer time stamps

    //new array
    $updates = [];

    //for each student in the roster
    foreach ($roster as $student)
    {
        //if the student in the roster is also in the list of updated ids
        //needs to check the student ids
        if (in_array($student['card_id'], $newer_ids))
        {
            //add the student to the return array
            $updates[] = $student;
        }
    }

    return $updates;
}


function display_image($id){
    //easter, christmas, valentines or ""
    $theme = "easter";
    $ending = "jpg";
    if($theme != ""){
        $theme = "_" . $theme;
        $ending = "png";
    }
    $img = 'photos' . $theme . '\\' . $id . '' . $theme . '.' . $ending;
    if($id < 100000){
        $img = 'img\default_img.png';
    }
    echo '<img id = ' . $id . ' class="fitBox" src= ' . $img . '>';
}
