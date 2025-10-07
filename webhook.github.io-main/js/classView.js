// import Darkmode from 'darkmode-js';
// import Stopwatch from './stopwatch.js';
function initialize(preventTransition = false)
{
    initClock();
    updateStopwatches(true);

    //click handlers for dropdown students
    [...document.querySelectorAll(".roster-visible")].forEach(function (x)
    {
        x.addEventListener("click", function (y)
        {
            this.parentElement.toggleAttribute("collapsed");
        });
    });

    //check for new scans
    setInterval(refresh, 300);


    //check for transitions
    if(preventTransition)
    {
        setInterval(launchTransition, 1000); // 60000 milliseconds = 1 minute
    }

}


async function updateSettings(command)
{
    //command is an array with 2 or 3 elements
    //action: ... , setting: ... , (value): ...
    const response = await fetch("settings", {
        method: "POST",
        body: JSON.stringify({
            command: command
        })
    });
    result = response.text();
    result.then(function (r)
    {
        console.log(r);
    });
}


function updateClock()
{
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var meridiem = hours >= 12 ? 'PM' : 'AM';
    // Convert to 12-hour format
    hours = hours % 12 || 12;
    // Add leading zero to minutes and seconds if they are less than 10
    minutes = minutes < 10 ? '0' + minutes : minutes;
    seconds = seconds < 10 ? '0' + seconds : seconds;
    var timeString = hours + ':' + minutes + ':' + seconds + ' ' + meridiem;
    document.getElementById('clock').innerText = timeString;
}

function initClock()
{
    updateClock();

    setInterval(updateClock, 1000);
    // Initial call to display the clock immediately
}

function whenScanned()
{
    document.getElementById("studentAdd").innerHTML +=
        "<div class=\"student\"><div class=\"r\">CardNum###########</div><div class=\"r\">NewFirstName</div><div class=\"r\">NewLastName</div><div class=\"r\">Full Stack</div><div class=\"r\">TimeScannedIn</div></div>";
}

function updateColor(status1, studentID)
{

    //we should only need to check if they are in class, not if their device-id matches our device-id
    //for them to be considered present
    const studentElement = document.querySelector('[student-id="' + studentID + '"]');
    studentElement.toggleAttribute("present", false);
    studentElement.toggleAttribute("pass", false);
    studentElement.toggleAttribute("absent", false);

    if (status1 == "present")
    {
        studentElement.toggleAttribute("present", true);
    }
    else if (status1 == "pass")
    {
        studentElement.toggleAttribute("pass", true);
    }
    else
    {
        studentElement.toggleAttribute("absent", true);
    }
}

function checkTimeWaste(new_status, old_status, time_stamp, new_room, old_room, class_id, card_id) {
    const response = fetch("time-waste", {
        method: "POST",
        body: JSON.stringify({
            card_id: card_id,
            class_id: class_id,
            new_status: new_status,
            old_status: old_status,
            time_stamp: time_stamp,
            new_room: new_room,
            old_room: old_room
        })
    })
}

function updateCheckbox(status1, studentID)
{
    const studentElement = document.querySelector('[student-id1="' + studentID + '"] ');

    if (status1 == "present")
    {
        studentElement.checked = true;
    }
    else  
    {
        studentElement.checked = false;
    }
}




//so basically this does work
//the problem is that when the button is clicked to mark a student as present
//the database is never updated to mark the student as present
//so when this is called, it gets the value from the database: the wrong value
//so fix them darn queries and properly update the DB pls
//check console, but that is what seems to be happening
//peace

async function refresh()
{
    const currentTimestamp = new Date(new Date().getTime()).toISOString();
    const timestampElement = document.querySelector('body[latest-timestamp]');

    const response1 = await fetch("auto-refresh", {
        method: "POST",
        body: JSON.stringify({
            email: teacherEmail,
            timestamp: timestampElement.getAttribute('latest-timestamp'),
            block: block
        })
    }).then(response => response.json())
        .then(function (response1)
        {
            if(response1.length==0){
                //console.log(currentTimestamp);
            } else {
                //console.log(currentTimestamp);
                timestampElement.setAttribute('latest-timestamp', currentTimestamp);
                response1['updates'].forEach(

                    function (studentObject)
                    {
                        if (studentObject.time_stamp != null)
                        {
                            console.log(studentObject);
                            var cardId = studentObject.card_id;
                            var class_id = studentObject.class_id;
                            var status = studentObject.location;
                            var device = studentObject.device_id;
                            var room = studentObject.current_room;
                            var time = studentObject.time_stamp;
                            var old_room = document.querySelector('[student-id="' + cardId + '"] .device_id').innerHTML;
                            var old_status = document.querySelector('[student-id="' + cardId + '"] .status').innerHTML;
                        
        
                            document.querySelector('[student-id="' + cardId + '"].roster-visible').classList.remove('present', 'pass', 'absent');
                            document.querySelector('[student-id="' + cardId + '"].roster-visible').classList.add(status);

                            document.querySelector('[student-id="' + cardId + '"] .status').innerHTML = status;
                            document.querySelector('[student-id="' + cardId + '"] .device_id').innerHTML = room;
                            document.querySelector('[student-id="' + cardId + '"] .time_stamp').innerHTML =  parseTime(time);
                            if(status=="pass" && old_room !="Bathroom") {
                                document.querySelector('[student-id="' + cardId + '"] .stopwatch').setAttribute('start-time', time);
                            }
                            updateCheckbox(status, cardId);
                            updateColor(status, cardId);
                            checkTimeWaste(status,old_status, time, room,old_room, class_id, cardId);

                        }
                    }

                );
            }
            //update new "reference" timestamp

        });


}


function launchTransition()
{
    // Get the current time
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    const currentTime = currentHour * 60 + currentMinute; // Convert current time to minutes

    // Define the transition periods in minutes
    const transitionPeriods = [
        7 * 60 + 39,
        8 * 60 + 39,   // 8:40 converted to minutes
        9 * 60 + 28,   // 9:29 converted to minutes
        10 * 60 + 17,  // 10:18 converted to minutes
        13 * 60 + 6,   // 13:07 converted to minutes
        13 * 60 + 55,  // 13:56 converted to minutes
        12 * 60 + 17   // 12:18 converted to minutes
    ];

    // Check if the current time is within a transition period
    for (const period of transitionPeriods)
    {
        if (currentTime >= period && currentTime <= period + 4)
        {
            // Launch the PHP file
            window.location.href = 'transition';
            // You can add additional actions here if needed
        }
    }
}


function handleButtonClick(buttonType, card_id, current_device)
{
    fetch('button', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: card_id, buttonType: buttonType, device: current_device })
    })
        .then(response => response.json())
        .then(data =>
        {
            //yo, you gotta fix this
            //this message is being logged wayyy toooo many times for one button click
            //could be affecting performance or maybe just efficiency, but still
            console.log('Success:', data);
        })
        .catch(error =>
        {
            console.error('Error:', error);
        });
    
    
}

function scanInToggle()
{
    const studentElement = document.querySelector('[student-id1="' + studentID + '"]');

}


function updateStopwatches(init = false)
{
    // Convert startTime to a Date object only once if init is true
    document.querySelectorAll('.stopwatch').forEach(function (stopwatchElement)
    {
        const referenceTimestamp = stopwatchElement.getAttribute('start-time');
        stopwatchElement.innerText = getStopwatchValue(referenceTimestamp);
    });


    if (init)
    {
        // Correct the usage of setInterval and bind
        setInterval(function ()
        {
            updateStopwatches();
        }, 1000);

    }

}

function getStopwatchValue(startTime)
{
    const currentTime = new Date().getTime();
    const elapsedTime = Math.floor((currentTime - new Date(startTime)) / 1000);
    const minutes = Math.floor(elapsedTime / 60);
    const seconds = elapsedTime % 60;

    return `${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")}`;

}

function setLateToAbsent(card_id)
{
    // Get the current time
    const now = new Date();
    const currentHour = now.getHours();
    const currentMinute = now.getMinutes();
    const currentTime = currentHour * 60 + currentMinute; // Convert current time to minutes
    const studentElement = document.querySelector('[student-id="' + card_id + '"]');


    const transitionPeriods = [
        7 * 60 + 55,
        8 * 60 + 44,   // 8:40 converted to minutes
        9 * 60 + 33,   // 9:29 converted to minutes
        10 * 60 + 22,  // 10:18 converted to minutes
        13 * 60 + 11,   // 13:07 converted to minutes
        14 * 60,  // 13:56 converted to minutes
        12 * 60 + 22   // 12:18 converted to minutes
    ];

    status2 = document.querySelector('[student-id="' + card_id + '"] .status').innerHTML;
    
    // Check if the current time is within a transition period
    for (const period of transitionPeriods)
    {
        if (currentTime == period + 15)
        {
            if (status2 == "transition") {
                const response1 = fetch("set-absent", {
                    method: "POST",
                    body: JSON.stringify({
                        card_id: card_id
                    })
                })
            }
        }
    }
}


//Dark mode

// new Darkmode().showWidget();
// const options = {
//     bottom: '64px', // default: '32px'-
//     right: 'unset', // default: '32px'
//     left: '32px', // default: 'unset'
//     time: '0.5s', // default: '0.3s'
//     mixColor: '#fff', // default: '#fff'
//     backgroundColor: '#fff',  // default: '#fff'
//     buttonColorDark: '#100f2c',  // default: '#100f2c'
//     buttonColorLight: '#fff', // default: '#fff'
//     label: '🌓', // default: ''
//   }
  
//   const darkmode = new Darkmode(options);
//   darkmode.showWidget();
  
