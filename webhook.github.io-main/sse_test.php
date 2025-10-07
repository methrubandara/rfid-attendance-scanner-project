<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SSE Example</title>
</head>
<body>
    <div id="sse-data"></div>

    <script>
        const eventSource = new EventSource('/sse_server.php');

        eventSource.onmessage = function(event) {
            const dataElement = document.getElementById('sse-data');
            dataElement.innerHTML += '<p>' + event.data + '</p>';
        };

        eventSource.onerror = function(error) {
            console.error('Error occurred:', error);
        };
    </script>
</body>
</html>


<?php

include_once 'backend/utility.php';

echo currentISOTimestamp();

?>!