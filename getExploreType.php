#!/usr/local/bin/php

<?php
	
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
							  						  
	if (!$connection) {
		die('Could not connect');
	}					

	$queryType = $_GET['query'];

	if(	($queryType == 'TopLocation') || ($queryType == 'MostSpokenLine') || ($queryType == 'TopCharacter') || ($queryType == 'WordsSpoken'))
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
	else
	{
		echo '';
	}

	oci_close($connection);
?>
