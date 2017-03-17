#!/usr/local/bin/php
<?php

	$connection = oci_connect($username = 'weingart', $password = 'bridgeoverlord201', $connection_string = '//oracle.cise.ufl.edu/orcl');						  
	if (!$connection) {
		die('Could not connect');
	}
	$type = $_GET['type'];
	if($type == "Character")
	{
		$id = $_GET['id'];
		$name = $_GET['name'];
		$gender = $_GET['gender'];
		$query = "INSERT INTO CHARACTER VALUES (". $id .", '". $name ."', '". $gender ."')";
	}
	else if ($type == "Episode")
	{
		$id = $_GET['id'];
		$title = $_GET['title'];
		$date = $_GET['date'];
		$season = $_GET['season'];
		$numberinseason = $_GET['numberinseason'];
		$numberinseries = $_GET['numberinseries'];
		$viewers = $_GET['viewers'];
		$rating = $_GET['rating'];
		$stillurl = $_GET['stillurl'];
		$videourl = $_GET['videourl'];
		$query = "INSERT INTO EPISODE VALUES (". $id .", '". $title ."', '". $date ."', ". $season .", ". $numberinseason .", ". $numberinseries .", ". $viewers .", ". $rating .", '". $stillurl . "', '". $videourl . "')";

	}
	else if ($type == "Location")
	{
		$id = $_GET['id'];
		$name = $_GET['name'];
		$query = "INSERT INTO LOCATION VALUES (". $id .", '". $name ."')";

	}
	else
	{
		$id = $_GET['id'];
		$episodeid = $_GET['episodeid'];
		$linenumber = $_GET['linenumber'];
		$rawtext = $_GET['rawtext'];
		$timestamp = $_GET['timestamp'];
		$speakingline = $_GET['speakingline'];
		$characterid = $_GET['characterid'];
		$locationid = $_GET['locationid'];
		$character = $_GET['character'];
		$location = $_GET['location'];
		$spokenword = $_GET['spokenword'];
		$wordcount = $_GET['wordcount'];
		$query = "INSERT INTO SCRIPT_LINE VALUES (". $id .", ". $episodeid .", ". $linenumber .", '". $rawtext ."', ". $timestamp .", '". $speakingline ."', ". $characterid .", ". $locationid .", '". $character ."', '". $location ."', '". $spokenword ."', ". $wordcount .")";
	}
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	echo '<font size = "2" color = "yellow">Record inserted</font>'; 
	oci_free_statement($statement);
	oci_close($connection);
?>
