function parseTime(timestamp)
{
    if (timestamp !== null)
    {
        var dateTime1 = timestamp.split("T");
        var dateTime2 = dateTime1[0].split("-");
        var date = dateTime1[1].slice(0, -8) + " " + dateTime2[1] + "/" + dateTime2[2] + "/" + dateTime2[0];

        // Convert the date string to a Unix timestamp
        var timestamp = Date.parse(date + " UTC");
        // Format the timestamp in 12-hour format without leading zeros for the hour
        return ((new Date(timestamp).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })).toLowerCase());
    } else
    {
        console.log(" ");
    }
}



