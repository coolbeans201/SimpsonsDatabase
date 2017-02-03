#!/usr/local/bin/php
<?php
	$connection = oci_connect($username = 'weingart', $password = 'bridgeoverlord201', $connection_string = '//oracle.cise.ufl.edu/orcl');						  
	if (!$connection) {
		die('Could not connect');
	}
	$query = "SELECT a.aCount + b.bCount + c.cCount + d.dCount
					 FROM(SELECT COUNT(*) aCount FROM Character) a, 
					 (SELECT COUNT(*) bCount FROM Location) b,
					 (SELECT COUNT(*) cCount FROM Episode) c,					 
					 (SELECT COUNT(*) dCount FROM Script_Line) d";
	echo '<font size = "4" color = "white">';
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	
	echo "<table border='2px solid white' style='margin-left: auto; margin-right: auto'>\n";

	echo "<tr>\n";
	echo '<th><font color = "yellow">Total Tuples</th>';
	echo '</tr>';
	while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) {
					echo '    <td><font color = "yellow">' . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				echo "</tr>\n";
	}
	echo "</table><br>";
	oci_free_statement($statement);
?>
