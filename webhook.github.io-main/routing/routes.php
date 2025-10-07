<?php

$routes = [

    /***** login ******/
    '' => 'login.php',
    'login' => 'login.php',
    'index.html' => 'login.php',
    'login-authentication' => 'receiveSignIn.php',

    'class-view' => 'classView.php',
    //this has fancy magic built into it
    //we can somehow detect that force was requested instead
    //and use our own default values through it
    'class-view-force' => 'classView.php',
    'class-view1' => 'classView1.php',
    'class-view2' => 'classView2.php',
    
    // 'utility' => 'utility.php',
    
    /***** fetch ******/
    
    'button' => 'button.php',
    'auto-refresh' => 'autoRefresh.php',
    'time-waste' => 'timeWaste.php',
    'set-absent' => 'setAbsent.php',
    'update-frontend' => 'updateFrontend.php',
    'transition' => 'setTransition.php',
    'overview' => 'classOverview.php',
    'settings' => 'receiveSettings.php',

    'simulate-scan' => 'simulateScan.php', 


    /***** db ******/

    'db' => 'db/index.php',
];
