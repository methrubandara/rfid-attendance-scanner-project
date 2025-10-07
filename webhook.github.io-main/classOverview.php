<?php

session_start();
$_SESSION['user_info']['page'] = "classOverview";
if(empty($_SESSION['user_info']['email'])){
  header("Location: index.html");
  die('no hacking sorry');
}

?>


<link rel="stylesheet" href="css/classOverviewStyle.css"> 
<link rel="icon" type="image/x-icon" href="img/EagleEyeLogo.png">

<div class="container">
  <!--
maxTilt:        20,
perspective:    1000,   // Transform perspective, the lower the more extreme the tilt gets.
easing:         "cubic-bezier(.03,.98,.52,.99)",    // Easing on enter/exit.
scale:          1,      // 2 = 200%, 1.5 = 150%, etc..
speed:          300,    // Speed of the enter/exit transition.
transition:     true,   // Set a transition on enter/exit.
disableAxis:    null,   // What axis should be disabled. Can be X or Y.
reset:          true,   // If the tilt effect has to be reset on exit.
glare:          false,  // Enables glare effect
maxGlare:       1       // From 0 - 1.
-->
  <div class=" column  tilt tilt-box  tilt-box1 Wrapline animLine " data-tilt data-tilt-transition="true" data-tilt-perspective="1000" data-tilt-glare="true" data-tilt-reset="true" data-tilt-scale="0.9">
    <a href="https://amsaeagleeye.org/class-view" title="option 1" class="tilt-box-inner Wrapline animLine inverted ">
      <span class="text-content">
        Intro Java
        <!--Elf Village-->
      </span>
    </a>
  </div>

  <div class=" column  tilt tilt-box  tilt-box2 Wrapline animLine inverted" data-tilt data-tilt-transition="true" data-tilt-perspective="1000" data-tilt-glare="true" data-tilt-reset="true" data-tilt-scale="0.9">
    <a href="https://amsaeagleeye.org/class-view" title="option 2" class="tilt-box-inner Wrapline animLine ">
      <span class="text-content">
        Fullstack
        <!--Santa's Sleigh-->
      </span>
    </a>
  </div>

  <div class=" column  tilt tilt-box  tilt-box3 Wrapline animLine inverted" data-tilt data-tilt-transition="true" data-tilt-perspective="1000" data-tilt-glare="true" data-tilt-reset="true" data-tilt-scale="0.9">
    <a href="https://amsaeagleeye.org/class-view" title="option 3" class="tilt-box-inner Wrapline animLine ">
      <span class="text-content">
        Discrete Math
        <!--Santa's Workshop-->
      </span>
    </a>
  </div>
  <div class=" column  tilt tilt-box  tilt-box4 Wrapline animLine " data-tilt data-tilt-transition="true" data-tilt-perspective="1000" data-tilt-glare="true" data-tilt-reset="true" data-tilt-scale="0.9">
    <a href="https://amsaeagleeye.org/class-view" title="option 4" class="tilt-box-inner Wrapline animLine inverted">
      <span class="text-content">
        AP Computer Science
        <!--North Pole-->
      </span>
    </a>
  </div>

</div>
<!-- -----
<div class="topcontainer  innerLine ">
  Content
</div> -->
<!-- https://github.com/gijsroge/tilt.js -->
