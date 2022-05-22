<?php
// This page manages the database connection.

// Check if database exist, create it otherwise.

// ************ Connect to Database ************ 
$DATABASE_HOST_ONE = '127.0.0.1';
$DATABASE_USER_ONE = 'root';
$DATABASE_PASS_ONE = '';
//$DATABASE_NAME_ONE = 'BrowSwExLite';

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST_ONE, $DATABASE_USER_ONE, $DATABASE_PASS_ONE);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	die ('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// ************ Connect to Database ************  
 
 
 
  // Drop the database (For developement debugging)
   // $stmt1 = $con->prepare('DROP DATABASE BrowSwExLite');
   // $stmt1->execute();
   // $stmt1->close();
  //  echo("<br>BrowSwExLite Database Created<br>");

 
// Make BrowSwExLite the current database
$db_selected = mysqli_select_db($con,'BrowSwExLite');


// If BrowSwExLite database does not exist
if (!$db_selected) { 

	// Create Database
	$stmt2 = $con->prepare('CREATE DATABASE BrowSwExLite');
	$stmt2->execute();
	$stmt2->close();
    echo("<br>BrowSwExLite Database Created<br>");
	
	$db_selected = mysqli_select_db($con,'BrowSwExLite');
	
	// Create Records Table
	$stmt3 = $con->prepare('CREATE TABLE IF NOT EXISTS Records(
	                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
							name VARCHAR(30),
							obtainedname VARCHAR(30) )');
	$stmt3->execute();
	$stmt3->close();
    echo("<br>Records Table Created<br>");
	
	
	
	// account username, userid, issuspect
	
	// Create Records Table
	$stmt4 = $con->prepare('CREATE TABLE IF NOT EXISTS Accounts(
	                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
							name VARCHAR(30),
							instagramid VARCHAR(30),
                            issuspect INT(1) )');
	$stmt4->execute();
	$stmt4->close();
    echo("<br>Accounts Table Created<br>");
	
	
	// Relationships id(auto), accountid, blockedbysuspect, followedbysuspect, restrictedbysuspect
	$stmt4 = $con->prepare('CREATE TABLE IF NOT EXISTS Relationships(
	                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
							instagramid VARCHAR(30),
                            isblocked INT(1),
                            isfollowed INT(1),
                            isrestricted INT(1)	)');
	$stmt4->execute();
	$stmt4->close();
    echo("<br>Accounts Table Created<br>");
	
	}



// Tables [Id, Name, ReceivedName]


?>
