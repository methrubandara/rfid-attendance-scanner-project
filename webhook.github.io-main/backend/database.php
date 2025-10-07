<?php

/**
 * Runs a paramaterized SQL query in the database
 *
 * @param array $vars_array strings to insert into query, in order of appearance
 * @param string $sql_code code of query to run, with ?'s in place of insertable strings from $vars_array
 * @param string $db_name (optional) name of database
 *
 * @return array|string $result
 */

function runQuery($vars_array, $sql_code, $db_name = "dbmaster")
{

    $server_name = 'ls-caa94c87a9ea82ec60f0fc05a0b0e3f71dcae6bb.cxmnzdfrue4n.us-east-1.rds.amazonaws.com';
    $username = 'dbmasteruser';
    $password = 'M*yzY(B(y&!_1!>WUxx.H8R~50+:m395';

    $connection = new mysqli($server_name, $username, $password, $db_name);

    // Check connection
    if ($connection->connect_error)
    {
        die('Port: ' . ini_get('mysqli.default_port') . "\n\r\n\r | Connection to server failed <B>(1)</B>: " . $connection->connect_error);
    }
    else
    {
        $connection->set_charset('utf8mb4');
    }

    if ($vars_array != null)
    {
        $vars_array = array_slice($vars_array, 0, substr_count($sql_code, '?'));
    }

    $result = mysqli_execute_query($connection, $sql_code, $vars_array);

	if($result === false)
	{
		return mysqli_error($connection);
	}
	else if ($result === true)
	{

        $temporary_id  = mysqli_insert_id($connection);
		if(isset($temporary_id))
		{
            //If insert is successful, return the auto-assigned id number, if available
			return $temporary_id;
		}
		else
		{
			return $result;
		}
	}

    $result = mysqli_fetch_all($result, MYSQLI_ASSOC);


    return $result;
}
