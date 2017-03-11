#!/usr/local/bin/php

<?php
	$connection = oci_connect($username = 'weingart',
							  $password = 'bridgeoverlord201',
							  $connection_string = '//oracle.cise.ufl.edu/orcl');
							  							  
	if (!$connection) {
		die('Could not connect');
	}				 
	
	// Retrieve data from Query String
	$queryType = $_GET['queryType'];
	$queryName = $_GET['dialogueName'];
	if ($queryType == 'TotalViewing')
	{
		$query = "select season, sum(us_viewers) as total_viewers 
		          from episode
				  group by season
				  order by season asc";
	}
	else if ($queryType == 'AverageRating')
	{
		$query = "select r.season as season, round((sum_ratings/episode_count),2) as avg_rating, episode_count
		          from (select season, sum(imdb_rating) as sum_ratings
		                from episode
						group by season) r,
		               (select season, count(distinct id) as episode_count
		                from episode
						group by season) e
				  where r.season = e.season
				  order by season asc";
	}
	else if ($queryType == 'MostSpokenLine')
	{
				$query = "select * from episode";
	}
	else if ($queryType == 'TopCharacterAll')
	{
		$query = "select rownum as rank, name, episode_count
		          from(select c.name as name, count(distinct scr.episode_id) as episode_count
				       from script_line scr
				       inner join episode ep on ep.id = scr.episode_id
				       inner join character c on c.id = scr.character_id
				       group by name
				       order by episode_count desc)
				  order by rank asc";
	}
	else if ($queryType == 'TopCharacterSimpsons')
	{
		$query = "select rownum as rank, name, episode_count
		          from(select c.name as name, count(distinct scr.episode_id) as episode_count
				       from script_line scr
				       inner join episode ep on ep.id = scr.episode_id
				       inner join character c on c.id = scr.character_id
				       where name like '%Simpson%'
				       group by name
				       order by episode_count desc)
				  order by rank asc";		
	}
	else if ($queryType == 'TopCharactersNonSimpsons')
	{
		$query = "select rownum as rank, name, episode_count
		          from(select c.name as name, count(distinct scr.episode_id) as episode_count
				       from script_line scr
				       inner join episode ep on ep.id = scr.episode_id
				       inner join character c on c.id = scr.character_id
				       where name not like '%Simpson%'
				       group by name
				       order by episode_count desc)
				  order by rank asc";				
	}
	else if ($queryType == 'TopLocations')
	{
		$query = "select rownum as rank, name, episode_count
		          from(select l.name as name, count(distinct scr.episode_id) as episode_count
				       from script_line scr
				       inner join episode ep on ep.id = scr.episode_id
				       inner join location l on l.id = scr.location_id
				       group by name
					   order by episode_count desc)
				  order by rank asc";		
	}
	else if ($queryType == 'MostWatchedEpisodes')
	{
		$query = "select rownum as rank, title, us_viewers, number_in_series, season, number_in_season, still_url, video_url
				  from(select title, us_viewers, number_in_series, season, number_in_season, still_url, video_url 
		               from episode
				       where us_viewers is not null
				       order by us_viewers desc)";		
	}
	else if ($queryType == 'HighestRatedEpisodes')
	{
		$query = "select rownum as rank, title, imdb_rating, number_in_series, season, number_in_season, still_url, video_url
				  from(select title, imdb_rating, number_in_series, season, number_in_season, still_url, video_url 
		               from episode
				       where imdb_rating is not null
				       order by imdb_rating desc)";		
	}
	else if($queryType == 'Dialogue')
	{
		$query = "select * from episode";		
	}
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	if ($queryType == 'TotalViewing')
        {
		echo "<table border='1'>\n";				
		echo '<tr><th>Season Number</th><th>Total US Viewers</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
        else if ($queryType == 'AverageRating')
        {
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Average IMDB Rating</th><th>Episode Count</th></tr>';
                while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
        }
	else if ($queryType == 'MostSpokenLine')
	{
		echo "<table border='1'>\n";
		echo "<tr><td>TODO (Most spoken lines overall, or by individual characters?)</td></tr></table>";
	}
	else if ($queryType == 'TopCharacterAll')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episode Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if ($queryType == 'TopCharacterSimpsons')
        {
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episode Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if ($queryType == 'TopCharactersNonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episode Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if ($queryType == 'TopLocations')
        {
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Location</th><th>Episode Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if ($queryType == 'MostWatchedEpisodes')
        {
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Title</th><th>US Viewers</th>
								 <th>Episode Number</th><th>Season Number</th>
								 <th>Number in Season</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		$count = 0;
		while($row=oci_fetch_assoc($statement)) {
			$count = $count + 1;
			if($count != 1)
			{
				echo "<tr><td>" . $row['RANK'] . 
			        "</td><td>" . $row['TITLE'] . 
				"</td><td>" . $row['IMDB_RATING'] . 
				"</td><td>" . $row['NUMBER_IN_SERIES'] . 
				"</td><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
				"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
			}
		}
		echo "</table><br>";
	}
	else if ($queryType == 'HighestRatedEpisodes')
        {
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Title</th><th>IMDB Rating</th>
								 <th>Episode Number</th><th>Season Number</th>
								 <th>Number in Season</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		$count = 0;
		while($row=oci_fetch_assoc($statement)) {
			$count = $count + 1;
			if($count != 1)
			{
				echo "<tr><td>" . $row['RANK'] . 
			        "</td><td>" . $row['TITLE'] . 
				"</td><td>" . $row['IMDB_RATING'] . 
				"</td><td>" . $row['NUMBER_IN_SERIES'] . 
				"</td><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
				"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";			}
		}
		echo "</table><br>";
	}
	else if ($queryType == 'Dialogue')
	{
		echo "<table border='1'>\n";
		echo "<tr><td>TODO</td></tr>";
	}
	oci_free_statement($statement);
	oci_close($connection);
?>
