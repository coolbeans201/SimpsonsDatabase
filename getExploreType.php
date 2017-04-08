#!/usr/local/bin/php

<?php
	
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
							  						  
	if (!$connection) {
		die('Could not connect');
	}					

	$queryType = $_GET['query'];

	if(	($queryType == 'TopLocation') || ($queryType == 'MostSpokenLine') || ($queryType == 'TopCharacter') || ($queryType == 'WordsSpoken') )
	{
			echo '<font size = "4" color="yellow">Select Type:</font>';
			echo '<select name="query2box" id = "query2" onchange="typeFunction2();">';
			echo '<option value = "-1">Select:</option>';
			echo '<option value="overall">Overall</option>';
			if($queryType == 'TopLocation' || $queryType == 'MostSpokenLine')
			{
				echo '<option value="character">By Character</option>';
			}
			else if($queryType == 'WordsSpoken')
			{
				echo '<option value="perEpisode">Per Episode</option>';
			}
			echo '<option value="episode">By Episode</option>';
			echo '<option value="season">By Season</option>';
			echo "</select> \n";
	}
	else if($queryType == 'TotalDialogue')
	{
		// Retrieve data from Query String
		$query = "SELECT DISTINCT NAME, ID FROM CHARACTER WHERE NAME IS NOT NULL ORDER BY NAME";

		$statement = oci_parse($connection, $query);
		oci_execute($statement);

		echo '<font size = "4" color="yellow">Select name:</font>';
		echo '<select name="query3box" id = "query2" onchange="enableBtn();">';
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
