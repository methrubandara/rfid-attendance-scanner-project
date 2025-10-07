<?php

//default stuff to alow us to see errors on the web page, idk exactly what it does but its cool
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//mr bakkala magic code
//kinda like c++ header files, or kinda like classes
//basically allows you to use and call methods defined in a different file!
include_once 'backend/database.php';
include_once 'backend/utility.php';
#include_once 'recieveSignIn.php';
//timezone stuff
date_default_timezone_set('America/New_York'); //new york is EST deal with it
//--------------------------------------------------------------------------------------
//if the user tries to skip logging in and go directly to this link
//tell them ha no, go back to start baby
//idk why but i have to start the session first each time
session_start();
//$_SESSION['user_info']['page'] = $time;
if ($request !== 'class-view-force')
{
    if (empty($_SESSION['user_info']['email']))
    {

        // If they are not, redirect them to the login page.
        header("Location: index.html");
        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        // doesn't actually get displayed anymore
        $response = "no";
        die($response);
    }

}
//--------------------------------------------------------------------------------------
//teacher and current time to get roster
//use time to get period (1-8) from period and day get block, then you should be able to get the roster
//display roster from the given parameters
$getToDay = getDay(); //      SET MANUALLY FOR TESTING
#get from sign-in

//not sure where exactly request comes from, but it is after the '/' in the url
//basically instead of using your own email and setting up the database for it
//default to mr bakkalas email; so ppl like pranav DO NOT need to be added to the DB
if ($request == 'class-view-force')
{
    $currentBlock = 'D1';
    $teacher_id = '87654321';
    $teacher_email = 'bbakkala@amsacs.org';
    $classID = '123412';
    $className = getClassName($classID);
    $current_device = getDevice($classID);
}
else
{
    $teacher_email = $_SESSION['user_info']['email'];
    if ($getToDay == 0)
    {
        //it is a non school day
        #this one should work:
        $currentBlock = getBlock($getToDay);
        #for fun
        $teacher_id = 123456789;
        $classID = 987654321;
        $className = "No Class Today Baby!";
        $current_device = "My phone";
    }
    else
    {

        #normal school day
        //$teacher_email = "zschlichtmann@amsacs.org";
        //this parts always breaking idk
        $currentBlock = getBlock($getToDay);
        $teacher_id = getTeacherId($teacher_email);
        $classID = getClassID($teacher_id, $currentBlock);
        $className = getClassName($classID);
        $current_device = getDevice($classID);
        //hey also get the name of the teacher and display it, that'd be nice @here//**. */

    }
}
//this code should really be cleaned up and grouped all together
$onloadAttribute = '';
if ($request == 'class-view-force')
{
    $onloadAttribute .= "initialize(true);";
}
else
{
    $onloadAttribute .= "initialize();";
}

//ew yucky html code:
?>

<!DOCTYPE html>
    <html lang="en">
        <!-- use title to make the class name appear on the tab name -->
        <link rel="icon" type="image/x-icon" href="img/EagleEyeLogo.png">
        <title><?php echo $className; ?></title>
        <head>
            <script src="js/utility.js?<?php echo date('zi', strtotime('now')) ?>"></script>
            <!-- <script src="js/stopwatch.js?<?php echo date('zi', strtotime('now')) ?>"></script> -->
            <script src="js/classView.js?<?php echo date('zi', strtotime('now')) ?>"></script>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <link href='https://fonts.googleapis.com/css?family=Poiret One' rel='stylesheet'>
            <link rel="stylesheet" href="css/classViewStyle.css?<?php echo date('zi', strtotime('now')) ?>">

            <script name = 'PHP Globals'>
                const dateTime = '<?php echo currentISOTimestamp() ?>';
                const teacherEmail = '<?php echo $teacher_email ?>';
                const teacherId = '<?php echo $teacher_id ?>';
                const block = '<?php echo $currentBlock ?>';
            </script>
            <style>
                @media screen and (max-width: 768px) {
                    .container {
                        width: 100%;
                    }
                    /* Set font size to 16px on mobile screens */
                    body {
                        font-size: 16px;
                        margin: 0;
                        padding: 0;
                    }
                    .r {
                        font-size: 20px;
                    }
                    .info {
                        text-align: left;
                        font-size: 16px;
                    }
                }
            </style>
        </head>
        <script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
        <script>
        function addDarkmodeWidget() {
            new Darkmode().showWidget();
        }
        window.addEventListener('load', addDarkmodeWidget);
        </script>
        



        <body onload = '<?php echo $onloadAttribute; ?>' latest-timestamp = '<?php echo currentISOTimestamp() ?>'>

            <wrapper class = "back">
                <wrapper id = "makeBlack">
                            <div class="dropdown">
                                <button class="dropbtn"><strong>Classes ▼</strong></button>
                                <div class="dropdown-content">
                                    <a href="overview">AP Lang</a>
                                    <a href="overview">Intro Java</a>
                                    <a href="overview">AP CS</a>
                                    <!-- Christmas Update!
                                    <a href="class-view">North Pole</a>
                                    <a href="class-view">Santa's Workshop</a>
                                    <a href="class-view">Santa's Sleigh</a>
                                    -->
                                </div>
                            </div>
                </wrapper>
                <wrapper class = "row">

                    <wrapper class="topimages">
                        <div class="buttonstuff">
                            <!-- This looks like old, bad dark mode code, can probably delete it -->
                            <!-- <label class="theme-switch">
                            <input type="checkbox" class="theme-switch__checkbox" id="darkModeSwitch">
                            <div class="theme-switch__container">
                                <div class="theme-switch__clouds"></div>
                                <div class="theme-switch__stars-container">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 144 55" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M135.831 3.00688C135.055 3.85027 134.111 4.29946 133 4.35447C134.111 4.40947 135.055 4.85867 135.831 5.71123C136.607 6.55462 136.996 7.56303 136.996 8.72727C136.996 7.95722 137.172 7.25134 137.525 6.59129C137.886 5.93124 138.372 5.39954 138.98 5.00535C139.598 4.60199 140.268 4.39114 141 4.35447C139.88 4.2903 138.936 3.85027 138.16 3.00688C137.384 2.16348 136.996 1.16425 136.996 0C136.996 1.16425 136.607 2.16348 135.831 3.00688ZM31 23.3545C32.1114 23.2995 33.0551 22.8503 33.8313 22.0069C34.6075 21.1635 34.9956 20.1642 34.9956 19C34.9956 20.1642 35.3837 21.1635 36.1599 22.0069C36.9361 22.8503 37.8798 23.2903 39 23.3545C38.2679 23.3911 37.5976 23.602 36.9802 24.0053C36.3716 24.3995 35.8864 24.9312 35.5248 25.5913C35.172 26.2513 34.9956 26.9572 34.9956 27.7273C34.9956 26.563 34.6075 25.5546 33.8313 24.7112C33.0551 23.8587 32.1114 23.4095 31 23.3545ZM0 36.3545C1.11136 36.2995 2.05513 35.8503 2.83131 35.0069C3.6075 34.1635 3.99559 33.1642 3.99559 32C3.99559 33.1642 4.38368 34.1635 5.15987 35.0069C5.93605 35.8503 6.87982 36.2903 8 36.3545C7.26792 36.3911 6.59757 36.602 5.98015 37.0053C5.37155 37.3995 4.88644 37.9312 4.52481 38.5913C4.172 39.2513 3.99559 39.9572 3.99559 40.7273C3.99559 39.563 3.6075 38.5546 2.83131 37.7112C2.05513 36.8587 1.11136 36.4095 0 36.3545ZM56.8313 24.0069C56.0551 24.8503 55.1114 25.2995 54 25.3545C55.1114 25.4095 56.0551 25.8587 56.8313 26.7112C57.6075 27.5546 57.9956 28.563 57.9956 29.7273C57.9956 28.9572 58.172 28.2513 58.5248 27.5913C58.8864 26.9312 59.3716 26.3995 59.9802 26.0053C60.5976 25.602 61.2679 25.3911 62 25.3545C60.8798 25.2903 59.9361 24.8503 59.1599 24.0069C58.3837 23.1635 57.9956 22.1642 57.9956 21C57.9956 22.1642 57.6075 23.1635 56.8313 24.0069ZM81 25.3545C82.1114 25.2995 83.0551 24.8503 83.8313 24.0069C84.6075 23.1635 84.9956 22.1642 84.9956 21C84.9956 22.1642 85.3837 23.1635 86.1599 24.0069C86.9361 24.8503 87.8798 25.2903 89 25.3545C88.2679 25.3911 87.5976 25.602 86.9802 26.0053C86.3716 26.3995 85.8864 26.9312 85.5248 27.5913C85.172 28.2513 84.9956 28.9572 84.9956 29.7273C84.9956 28.563 84.6075 27.5546 83.8313 26.7112C83.0551 25.8587 82.1114 25.4095 81 25.3545ZM136 36.3545C137.111 36.2995 138.055 35.8503 138.831 35.0069C139.607 34.1635 139.996 33.1642 139.996 32C139.996 33.1642 140.384 34.1635 141.16 35.0069C141.936 35.8503 142.88 36.2903 144 36.3545C143.268 36.3911 142.598 36.602 141.98 37.0053C141.372 37.3995 140.886 37.9312 140.525 38.5913C140.172 39.2513 139.996 39.9572 139.996 40.7273C139.996 39.563 139.607 38.5546 138.831 37.7112C138.055 36.8587 137.111 36.4095 136 36.3545ZM101.831 49.0069C101.055 49.8503 100.111 50.2995 99 50.3545C100.111 50.4095 101.055 50.8587 101.831 51.7112C102.607 52.5546 102.996 53.563 102.996 54.7273C102.996 53.9572 103.172 53.2513 103.525 52.5913C103.886 51.9312 104.372 51.3995 104.98 51.0053C105.598 50.602 106.268 50.3911 107 50.3545C105.88 50.2903 104.936 49.8503 104.16 49.0069C103.384 48.1635 102.996 47.1642 102.996 46C102.996 47.1642 102.607 48.1635 101.831 49.0069Z" fill="currentColor"></path>
                                </svg>
                                </div>
                                <div class="theme-switch__circle-container">
                                <div class="theme-switch__sun-moon-container">
                                    <div class="theme-switch__moon">
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                    <div class="theme-switch__spot"></div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            </label> -->
                        </div>
                        <div class="center class2">
                            <div id="clock"></div>
                            <!-- put a dash in between classname at least please -->
                            <div id =  "classname"> <?php echo $className ?></div>
                            <!-- why is this spelled bloc and not block?-->
                            <div id =  "bloc"> <?php echo $currentBlock ?></div>
                        </div>

                        <div id = "login1">Logged in as <?php echo $teacher_email ?></div>

                    </wrapper>

                    <wrapper class="titlegrid">
                        <div class="columnName">
                            <!-- <div class="r">Card ID</div> -->
                            <div class="r">Name</div>
                            <div class="r">Status</div>
                            <div class="r">Room</div>
                            <div class="r">Scan-In Time</div>
                        </div>
                    </wrapper>

                    <wrapper class="studentgrid" id="studentAdd">

                            <?php
#array
if ($getToDay == 0)
{
    //no school, no students
    //cant really be funny because theres actually work trying to be done with the roster; smh
    //student objects too complex, could do some default ones though...
    $roster = array();
}
else
{
    $roster = getRoster($classID);
}
#Makes name readeable

foreach ($roster as $student)
{
    echo '<div class="collapsible" collapsed>'; //2
    echo "<div class= 'r roster-visible colorChange "; //3
    if (status($student) == "present" && $student['device_id'] == $current_device)
    {
        echo "present";
    }
    else if (status($student) == "pass")
    {
        echo "pass";
    }
    else
    {
        echo "absent";
    }
    //working code
    $location = $student['location'];
    //$location = "On Vacation";
    //bro
    //what are all these number comments about
    echo "'  student-id='" . $student['card_id'] . "'>"; //4.2

    $room_number = getRoom($student['device_id']);
    echo "<div class= 'student'>"; //4

    // echo "<div class= 'r'>"; //5
    // echo $student['card_id'];
    // echo "</div>"; //5
    parseName($student['first_name'], $student['last_name']);
    echo "<div class= 'r status'>"; //6
    echo "<div>$location</div>";
    echo "</div>"; //6
    echo "<div class= 'r device_id' >";
    echo $room_number;
    echo "</div>";
    echo "<div class= 'r time_stamp' >"; //7
    parseTime(convertUTCToEastern($student['time_stamp']));
    echo "</div>";
    echo "<div class= 'r stopwatch' start-time = '" . $student['time_stamp'] . "' >0:00</div>";
    // 'r' class bette
    // echo "<div class= 'stopwatch' start-time = '".$student['time_stamp']."' >0:00</div>";

    echo "</div>"; //7
    echo '</div>'; //3
    echo '<div class = "roster-expand"> ';
    echo '<div class = "expanded">';
    echo '<div>';
    $id = $student['student_id'];
    display_image($id);
    echo '</div>';
    echo '<div class = "notPic">'; #evverythiung other than pic
        echo '<div class = "nP1">'; #info amnd present
            
            echo '<div id="presabs">'; #info amnd present
                echo '<div class="btn-container">';
                    echo '<label class="switch">';
                        echo '<input ';

                        if (status($student) == "present" && $student['device_id'] == $current_device)
                        {
                            echo ' checked '  ;
                        }
                        else
                        {
                            echo " ";
                        }


                        echo 'type="checkbox" class="checkbox1" ';
                        echo ' onchange="handleButtonClick(\'pres\', ' . $student['card_id'] . ', \'' . $current_device . '\')"';
                        echo "   student-id1='" . $student['card_id'] . "'>";
                        echo '<div class="slider">';
                            echo '<div class="circle">';
                                echo '<svg class="cross" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 365.696 365.696" y="0" x="0" height="6" width="6" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">';
                                    echo '<g>';
                                        echo '<path data-original="#000000" fill="currentColor" d="M243.188 182.86 356.32 69.726c12.5-12.5 12.5-32.766 0-45.247L341.238 9.398c-12.504-12.503-32.77-12.503-45.25 0L182.86 122.528 69.727 9.374c-12.5-12.5-32.766-12.5-45.247 0L9.375 24.457c-12.5 12.504-12.5 32.77 0 45.25l113.152 113.152L9.398 295.99c-12.503 12.503-12.503 32.769 0 45.25L24.48 356.32c12.5 12.5 32.766 12.5 45.247 0l113.132-113.132L295.99 356.32c12.503 12.5 32.769 12.5 45.25 0l15.081-15.082c12.5-12.504 12.5-32.77 0-45.25zm0 0"></path>';
                                    echo '</g>';
                                echo '</svg>';
                                echo '<svg class="checkmark" xml:space="preserve" style="enable-background:new 0 0 512 512" viewBox="0 0 24 24" y="0" x="0" height="10" width="10" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg">';
                                    echo '<g>';
                                        echo '<path class="" data-original="#000000" fill="currentColor" d="M9.707 19.121a.997.997 0 0 1-1.414 0l-5.646-5.647a1.5 1.5 0 0 1 0-2.121l.707-.707a1.5 1.5 0 0 1 2.121 0L9 14.171l9.525-9.525a1.5 1.5 0 0 1 2.121 0l.707.707a1.5 1.5 0 0 1 0 2.121z"></path>';
                                    echo '</g>';
                                echo '</svg>';
                            echo '</div>';
                        echo '</div>';
                    echo '</label>';
                echo '</div>';
            echo '</div>';
            echo '<div>';
            echo '</div>';
        echo '</div>'; #1
        // echo '<div class = "nP1">'; // both passes
        //     echo '<div class = "passes">'; // passes2
                echo '<button class="Nbutton" id="NB" onclick="handleButtonClick(\'nurse\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    nurseButton();
                echo '</button>';
                echo '<button class="Nbutton" id="SHP" onclick="handleButtonClick(\'studyHall\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    studyHallButton();
                echo '</button>';
                echo '<button class="Nbutton" id="FOB" onclick="handleButtonClick(\'frontOffice\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    frontOfficeButton();
                echo '</button>';
            // echo '</div>';

            // echo '<div class = "passes">'; // passes1
                echo '<button class="Nbutton" id="LPB" onclick="handleButtonClick(\'latePass\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    latePassButton();
                echo '</button>';
                echo '<button class="Nbutton" id="CB" onclick="handleButtonClick(\'counselor\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    counselorButton();
                echo '</button>';
                echo '<button class="Nbutton" id="BB" onclick="handleButtonClick(\'bathroom\', ' . $student['card_id'] . ', \'' . $current_device . '\')">';
                    bathroomButton();
                echo '</button>';
        //     echo '</div>';
        // echo '</div>';
    echo '</div>';
echo '</div>';
echo '</div>'; //2
echo '</div>'; //2
}

?>
                            </div>
                    </wrapper>
                </wrapper>
            </wrapper>
        </body>

    </html>

<?php
#var_visualize($roster);
?>

