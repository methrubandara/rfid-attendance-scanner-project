<?php


$HolidaysStrings = [ 	"September 4, 2023" ,
	
	
	"October 9, 2023",
	"November 10, 2023",
	"November 23, 2023", "November 24, 2023",
	
	"December 18, 2023", "December 19, 2023", "December 20, 2023", "December 21, 2023", "December 22, 2023",
	"December 25, 2023", "December 26, 2023", "December 27, 2023", "December 28, 2023", "December 29, 2023", 
	"January 1, 2024",

	"January 15, 2024",
	
	"February 19, 2024", "February 20, 2024", "February 21, 2024", "February 22, 2024", "February 23, 2024",
	
	"April 15, 2024", "April 16, 2024", "April 17, 2024", "April 18, 2024", "April 19, 2024", "April 20, 2024", 
	
	"May 27, 2024",

	"June 19, 2024" ];

$holidays = array();

foreach ($HolidaysStrings as $holiday) {
	$holidays[] = date("y/m/d", strtotime($holiday));
}

foreach ($holidays as $date) {
	echo $date->format('Y-m-d') . "   ";
}
?>