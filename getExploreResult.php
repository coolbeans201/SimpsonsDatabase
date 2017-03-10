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
		$query = "select season, sum(imdb_rating) as sum_ratings
		          from episode
				  group by season
				  order by season asc";
	}
	else if ($queryType == 'MostSpokenLine')
	{
				$query = "select * from episode";
	}
	else if ($queryType == 'TopCharacterAll')
	{
				$query = "select * from episode";
	}
	else if ($queryType == 'TopCharacterSimpsons')
	{
		$query = "select * from episode";		
	}
	else if ($queryType == 'TopCharactersNonSimpsons')
	{
		$query = "select * from episode";		
	}
	else if ($queryType == 'TopLocations')
	{
		$query = "select * from episode";		
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
	else /* $queryType == 'Dialogue' */
	{
		$query = "select * from episode";		
	}

	$statement = oci_parse($connection, $query);
	oci_execute($statement);

	$count = 0;
	
	while($row=oci_fetch_assoc($statement)) {
		$count = $count + 1;
		if ($queryType == 'TotalViewing')
        {	
			if($count == 1)
				echo "<table><tr><th>Season Number</th><th>Total US Viewers</th></tr>";
				
			echo "<tr><td>" . $row['SEASON'] . "</td><td>" . $row['TOTAL_VIEWERS'] . "</td></tr>";
        }
        else if ($queryType == 'AverageRating')
        {
			if($count == 1)
				echo "<table><tr><th>Season Number</th><th>Average IMDB Rating (This is the sum, average is TODO)</th></tr>";

            echo "<table><tr><td>" . $row['SEASON'] . "</td><td>" . $row['SUM_RATINGS'] . "</td></tr>";
        }
        else if ($queryType == 'MostSpokenLine')
        {
            echo "<table><tr><td>SPOKEN</td></tr></table>";            
        }
        else if ($queryType == 'TopCharacterAll')
        {
            echo "<table><tr><td>CHAR_ALL</td></tr></table>";            
        }
        else if ($queryType == 'TopCharacterSimpsons')
        {
            echo "<table><tr><td>CHAR_S</td></tr></table>";            
        }
        else if ($queryType == 'TopCharactersNonSimpsons')
        {
            echo "<table><tr><td>CHAR_NS</td></tr></table>";            
        }
        else if ($queryType == 'TopLocations')
        {
            echo "<table><tr><td>LOCATION</td></tr></table>";            
        }
        else if ($queryType == 'MostWatchedEpisodes')
        {
			if($count == 1) {
				echo "<table><tr><th>Rank</th><th>Title</th><th>US Viewers</th>
								 <th>Episode Number</th><th>Season Number</th>
								 <th>Number in Season</th><th>Episode Still</th>
								 <th>URL</th></tr>";
			}
				
			echo "<tr><td>" . $row['RANK'] . 
			     "</td><td>" . $row['TITLE'] . 
				 "</td><td>" . $row['US_VIEWERS'] . 
				 "</td><td>" . $row['NUMBER_IN_SERIES'] . 
				 "</td><td>" . $row['SEASON'] . 
				 "</td><td>" . $row['NUMBER_IN_SEASON'] . 
				 "</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				 "</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
        }
        else if ($queryType == 'HighestRatedEpisodes')
        {
            if($count == 1) {
				echo "<table><tr><th>Rank</th><th>Title</th><th>IMDB Rating</th>
								 <th>Episode Number</th><th>Season Number</th>
								 <th>Number in Season</th><th>Episode Still</th>
								 <th>URL</th></tr>";
			}
				
			echo "<tr><td>" . $row['RANK'] . 
			     "</td><td>" . $row['TITLE'] . 
				 "</td><td>" . $row['IMDB_RATING'] . 
				 "</td><td>" . $row['NUMBER_IN_SERIES'] . 
				 "</td><td>" . $row['SEASON'] . 
				 "</td><td>" . $row['NUMBER_IN_SEASON'] . 
				 "</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				 "</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
        }
        else /* $queryType == 'Dialogue' */
        {
            echo "<table><tr><td>DIA</td></tr></table>";            
        }
	}
	if($count > 0)
		echo "</table";

	//
	// VERY important to close Oracle Database Connections and free statements!
	//
	oci_free_statement($statement);
	oci_close($connection);
?>
