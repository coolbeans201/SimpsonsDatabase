#!/usr/local/bin/php

<?php
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
							  							  
		if (!$connection) {
			die('Could not connect');
		}						  
	
	$query2 = $_GET['query'];
	if ($query2 == 'Dialogue')
	{
		// Retrieve data from Query String
		$query = "SELECT DISTINCT NAME, ID FROM CHARACTER WHERE NAME IS NOT NULL ORDER BY NAME";
	}
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	if($query2 == 'Dialogue')
	{
		echo '<font size = "4" color="yellow">Select name:</font>';
		echo '<select name="personbox1" id = "name1">';
		echo '<option value = "-1">Select:</option>';
		while($row=oci_fetch_assoc($statement)) {
			echo '<option value="'.$row['ID'].'">' . $row['NAME'] . '</option>';
		}
		echo "</select> \n";
		oci_free_statement($statement);
		oci_close($connection);
	}
	else
	{
		echo '<font size = "4" color="yellow">Not Needed</font>';
	}
?>
