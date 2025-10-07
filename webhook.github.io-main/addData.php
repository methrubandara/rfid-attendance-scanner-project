<?php

//so basically
//need to make a script that generates and runs sql queries in php
//they need to add data to the database in order to display the classes on the website
    //in the queries, the "id" field can be ignored, as the database takes care of it for you

//add 'student id' and their 'class id' to 'enrollment'
# "enrollment" table:
    # Parameters:
        # "card_id"
        # "class_id"
            # runQuery([(integer) $card_id, $class_id], 'INSERT INTO `enrollment`(`card_id`, `class_id`) VALUES (?,?)');
//Bulk of the work is in 'section':
    //need class name, teacher id, class id, device id, block
# "section" table:
    # Parameters:
        # "class_name"
        # "class_id"
        # device_id"
        # "block"
        # "teacher_id"

//need to know room number and device id if registering a new room
    //new rooms to room_number if needed
# "room_number" table: (if needed)
    # Parameters:
        # "device_id"
        # "room"

//enroll a single student into a single class
function enroll_student( $card_id, $class_id ){
    runQuery([(integer) $card_id, $class_id], 'INSERT INTO `enrollment`(`card_id`, `class_id`) VALUES (?,?)');
}

function insert_student_classes_enrollment( $card_id, $class_ids ){
    foreach( $class_ids as $class_id ){
        enroll_student( $card_id, $class_id );
    }
}
//enroll a list of students into a single class
function enroll_students( $card_ids, $class_id ){
    foreach( $card_ids as $card_id ){
        enroll_student($card_id, $class_id);
    }
}
//enrolls several classes worth of students
//class_enrollments:
    //contains "class" objects
    //each object has:
        //list of students
        //class_id

function insert_enrollment( $class_enrollments ){
    foreach( $class_enrollments as $class_enrollment ){
        enroll_students( $class_enrollment['card_ids'], $class_enrollment['class_id']);
    }
}










