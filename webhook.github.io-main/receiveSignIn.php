<?php

#mr bakkala magic code
include_once 'backend/database.php';
include_once 'backend/utility.php';

#Gets credential
//this is the input to the file
$data = json_decode(file_get_contents('php://input'), true);
$credential = $data['data'];
//split string into array at each '.'
$credential = explode(".", $credential);
function decodeCredential($x)
{
    return base64_decode($x);
}
//runs the function on each element of the array
$credential = array_map('decodeCredential', $credential);
// Decode the JSON string, 'true' forces it into an a python-style array with square brackets
    // not use the silly '=>' yuck!
$decodedCredential = json_decode($credential[1], true);
// im gonna fix the layout of the site the class view one
// did you though?

//---------------------------------------------------
$email = $decodedCredential['email'];
//do some sql stuff to store this with the session id
//most important statement if you want to use the session var
session_start();
//bundle it all together
//yap code
if(!isset($_SESSION['user_info'])){
    $_SESSION['user_info'] = array();
}
$user_info = $_SESSION['user_info'];
$user_info['email'] = $email;
$user_info['page'] = "sign-in";
//---------------------------------------------------

$sessId = session_id( );
$user_info['userid'] = $sessId;
$_SESSION['user_info'] = $user_info;
//sql magic here
//basically use the first name and last name to get the student/teacher id of the current user
//if not found, bad guy
//if found add to a new database that associates session ids with student/teacher ids
$time = localtime();
//works perfectly
sessionIdAndStudentID($sessId,$email); //values should be stored in session now
// I love my cookies...
//$day = 86400 //length of one day, cookies are time based
//setcookie('first_name', $first_name, time() + $day * 30, '/'); // "'/'" - means accessible everywhere
//setcookie('last_name', $last_name, time() + $day * 30, '/');

$hdValue = $decodedCredential['hd']; // Access the "hd" key from the decoded array


#Hey! This is the part of the code we care about, that returns the values!
//-----------------------------------------------------------------------------------------------------------------------------------
#check if domain is valid
#temporary students are valid
if ($hdValue == 'student.amsacs.org' || $hdValue == 'amsacs.org')
{
    echo 'valid';
}
else
{
    echo 'invalid';
}
//-----------------------------------------------------------------------------------------------------------------------------------

//important cyber security feature here :^)
//lets see how long we can get away with not doing this part $^)
/*
#verify csrf token
$csrf_token_cookie = $_COOKIE['g_csrf_token'];
if (!$csrf_token_cookie) {
http_response_code(400);
die('No CSRF token in Cookie.');
}
$csrf_token_body = $_POST['g_csrf_token'];
if (!$csrf_token_body) {
http_response_code(400);
die('No CSRF token in post body.');
}
if ($csrf_token_cookie != $csrf_token_body) {
http_response_code(400);
die('Failed to verify double submit cookie.');
}

#After Google returns an ID token, it's submitted by an HTTP POST method request, with the parameter name credential, to your login endpoint.

#Verify the ID Token
require_once 'vendor/autoload.php';

// Get $id_token via HTTPS POST.
#The ID token is returned in the credential field, instead of the g_csrf_token field.
$id_token =
$CLIENT_ID =

$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
$userid = $payload['sub'];
// If request specified a G Suite domain:
$domain = $payload['hd'];
#check for amsacs domain
} else {
// Invalid ID token
}
 */
