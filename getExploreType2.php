#!/usr/local/bin/php

<?php
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
														
	if (!$connection) {
		die('Could not connect');
	}						  
	
	$queryType = $_GET['query'];
	$query2 = $_GET['query2'];

	if(($queryType == 'TopCharacter') || ($queryType == 'WordsSpoken'))
	{
		echo '<font size = "4" color="yellow">Select Type:</font>';
		echo '<select name="query3box" id = "query3" onchange="query3Change();">';
		echo '<option value = "-1">Select:</option>';
		echo '<option value="all">All Characters</option>';
		echo '<option value="simpsons">Simpsons</option>';
		echo '<option value="nonsimpsons">Non-Simpsons</option>';
		echo "</select> \n";
	}
	else if(($queryType == 'TopLocation' || $queryType == 'TopCharacter') && $query2 == 'season')
	{
		$query = "select distinct season from episode order by season asc";

		$statement = oci_parse($connection, $query);
		oci_execute($statement);

		echo '<font size = "4" color="yellow">Select season:</font>';
		echo '<select name="query3box" id = "query3"onchange="query3Change();">';
		echo '<option value = "-1">Select season:</option>';
		while($row=oci_fetch_assoc($statement)) {
			echo '<option value="'.$row['SEASON'].'">' . $row['SEASON'] . '</option>';
		}
		echo "</select> \n";

		oci_free_statement($statement);
	}
	else if ($query2 == 'character')
	{
		// Retrieve data from Query String
		$query = "SELECT DISTINCT NAME, ID FROM CHARACTER WHERE NAME IS NOT NULL ORDER BY NAME";

		$statement = oci_parse($connection, $query);
		oci_execute($statement);

		echo '<font size = "4" color="yellow">Select name:</font>';
		echo '<select name="query3box" id = "query3" onchange="query3Change();">';
		echo '<option value = "-1">Select:</option>';
		while($row=oci_fetch_assoc($statement)) {
			echo '<option value="'.$row['ID'].'">' . $row['NAME'] . '</option>';
		}
		echo "</select> \n";
		
		oci_free_statement($statement);
	}
	else
	{
		echo '';
	}
	oci_close($connection);
?>
