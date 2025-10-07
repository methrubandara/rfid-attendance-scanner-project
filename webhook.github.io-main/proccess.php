<?php
// process.php
include_once 'backend/database.php';
include_once 'backend/utility.php';

if(isset($_POST['action']) && $_POST['action'] == 'toggleState') {
    // Your method logic here
    // For example, toggle the state and return the new state
    $currentState = $_POST['currentState'];
    $newState = ($currentState == 1) ? 0 : 1;
    echo json_encode(['newState' => $newState]);
    exit;
}
?>
