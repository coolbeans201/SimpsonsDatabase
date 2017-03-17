#!/usr/local/bin/php

<?php
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
							  							  
	if (!$connection) {
		die('Could not connect');
	}						  
	
	// Retrieve data from Query String
	$type = $_GET['type'];
	if ($type == 'Character')
	{
		$query = "SELECT DISTINCT NAME, ID FROM CHARACTER WHERE NAME IS NOT NULL ORDER BY NAME";
	}
	
	else if ($type == 'Episode')
	{
		$query = "SELECT DISTINCT ID, TITLE FROM EPISODE WHERE TITLE IS NOT NULL ORDER BY TITLE";
	}
	
	else if($type == 'Location')
	{
		$query = "SELECT DISTINCT NAME, ID FROM LOCATION WHERE NAME IS NOT NULL ORDER BY NAME";
	}
	
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	echo '<font size = "4" color="yellow">Select name/title:</font>';
	echo '<select name="personbox1" id = "name1">';
	echo '<option value = "-1">Select:</option>';
	while($row=oci_fetch_assoc($statement)) {
		if ($type == 'Character'){
			echo '<option value="'.$row['ID'].'">' . $row['NAME'] . '</option>';
		}
		else if($type == 'Episode'){
			echo '<option value="'.$row['ID'].'">' . $row['TITLE'] . '</option>';
		}
		else if($type == 'Location'){
			echo '<option value="'.$row['ID'].'">' . $row['NAME'] . '</option>';
		}
	}
	echo "</select> \n";
	//
	// VERY important to close Oracle Database Connections and free statements!
	//
	oci_free_statement($statement);
	oci_close($connection);
?>
