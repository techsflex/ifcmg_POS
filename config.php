<?php

	//Testing Server
	$DB_NAME = 'pos_v2';
	$DB_USER = 'root';
	$DB_PASSWORD = '';
	$DB_HOST = 'localhost:3307';

	// Live Server - IFCMG Server
 
/*
	$DB_NAME = 'u767773135_pos';
	$DB_USER = 'u767773135_pos';
	$DB_PASSWORD = 'International/1';
	$DB_HOST = 'mysql.hostinger.com';
*/
/*
	//Live Server - Techsflex Sandbox

	$DB_NAME = 'u285175987_pos';
	$DB_USER = 'u285175987_pos';
	$DB_PASSWORD = 'International/1';
	$DB_HOST = 'mysql.hostinger.com';
*/
	//Create Connection
	$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

	//Check Connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>