<?php
    include_once 'backend/database.php';
    include_once 'backend/utility.php';

    $roster = getRoster(123412);
    //var_visualize($roster);

    


?>




<!-- <button onclick = 'simulateScan()'>Simulate</button> -->
<button onclick = 'setAllAbsent()'>Set All Absent</button>
<button onclick = 'setAllPresent()'>Set All Present Fullstack</button>

<script>

function simulateScan(data, ){
    
    const event = new Date();
    const simulatedParticleObject =  {
                                        "name": "scanProcessing.php",
                                        "data": "86185220115",
                                        "ttl": 60,
                                        "published_at": event.toISOString(),
                                        "coreid": "0a10aced202194944a04a3b4",
                                        "userid": "64ff2360c94c9e781927c8d6",
                                        "version": 0,
                                        "public": false,
                                        "productID": 24729
                                    }


    const response = fetch("scanProcessing.php", {
                method: "POST",
                body: JSON.stringify(simulatedParticleObject)
            });

            result = response.text();

            return result.then(function (r)
            {
                console.log(r);
            });
}

function setAllAbsent() {
    <?php
    $time = currentISOTimestamp();
    runQuery([], 'UPDATE `status` SET `location`="absent"');
    runQuery([], 'UPDATE `status` SET `in_class` = 0');
    runQuery([], 'UPDATE `scans` SET `device_id`="absent"');
    runQuery([$time], 'UPDATE `scans` SET `time_stamp`=?');
    ?>
}


function setAllPresent() {
    <?php
    $time = currentISOTimestamp();
    runQuery([], 'UPDATE `status` SET `location`="present"');
    runQuery([], 'UPDATE `status` SET `in_class` = 1');
    runQuery([], 'UPDATE `scans` SET `device_id`="0a10aced202194944a04a3b4"');
    runQuery([$time], 'UPDATE `scans` SET `time_stamp`=?');
    ?>
}

</script>
