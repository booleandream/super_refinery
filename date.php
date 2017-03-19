<?php

function dateConverter($ddmmyyyy) {
	//this function acts as both validator & converter.
	
	// date received in ddmmyyyy format. explode it into pieces of day, month and year
	$exploded = explode("-", $ddmmyyyy);
	
	//validate the day, month and year
	is_numeric($exploded[0]) && $exploded[0] < 32 && $exploded[0] > 0 ? 1 : die('invalid date (day)');
	is_numeric($exploded[1]) && $exploded[1] < 13 && $exploded[1] > 0 ? 1 : die('invalid date (month)');
	is_numeric($exploded[2]) && $exploded[2] < 2030 && $exploded[2] > 2005 ? 1 : die('invalid date (year)');
	
	//return the date in yyyymmdd format
	return $exploded[2] . '-' . $exploded[1] . '-' . $exploded[0];
}