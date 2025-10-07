<?php

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

$counter = 0;

while (true)
{
    // Send a simple message with an increasing counter
    echo "data: Server-Sent Event #" . $counter++ . "\n\n";
    ob_flush();
    flush();
    sleep(1); // Adjust as needed
}
