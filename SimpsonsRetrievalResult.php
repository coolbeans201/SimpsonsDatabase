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
	$secondFilter = $_GET['secondFilter'];
	if ($type == 'Character')
	{
		$query = "select a.name, a.title as first_episode, b.title as latest_episode, c.episode_count
			from (select distinct c.name, a.title
      from script_line b
      inner join episode a on a.id = b.episode_id
      inner join character c on b.character_id = c.id
      where c.ID = ". $secondFilter ."
      and a.title in (select title from (select distinct a.title, a.number_in_series from episode a inner join script_line b on a.id = b.episode_id inner join character c on b.character_id = c.id
                      where c.ID = ". $secondFilter ." order by a.number_in_series)a where rownum = 1))a,
      (select distinct c.name, a.title
       from script_line b
       inner join episode a on a.id = b.episode_id
       inner join character c on b.character_id = c.id
       where c.ID = ". $secondFilter ."
       and a.title in (select title from (select distinct a.title, a.number_in_series from episode a inner join script_line b on a.id = b.episode_id inner join character c on b.character_id = c.id
                       where c.ID = ". $secondFilter ." order by a.number_in_series desc)a where rownum = 1))b,
       (select count(distinct b.episode_id) as episode_count
        from script_line b
        inner join episode a on a.id = b.episode_id
        inner join character c on c.id = b.character_id
        where c.ID = ". $secondFilter .")c";
	}
	
	else if ($type == 'Episode')
	{
		$query = "select air_date, season, number_in_season, number_in_series, us_viewers, imdb_rating, still_url, video_url
					from episode
				where ID = ". $secondFilter;
	}
	else
	{
		$query = "select a.name, a.title as first_episode, b.title as latest_episode, c.episode_count
					from (select distinct c.name, a.title
				  from script_line b
				  inner join episode a on a.id = b.episode_id
				  inner join location c on b.location_id = c.id
				  where c.ID = ". $secondFilter ."
				  and a.title in (select title from (select distinct a.title, a.number_in_series from episode a inner join script_line b on a.id = b.episode_id inner join location c on b.location_id = c.id
								  where c.ID = ". $secondFilter ." order by a.number_in_series)a where rownum = 1))a,
				  (select distinct c.name, a.title
				   from script_line b
				   inner join episode a on a.id = b.episode_id
				   inner join location c on b.location_id = c.id
				   where c.ID = ". $secondFilter ."
				   and a.title in (select title from (select distinct a.title, a.number_in_series from episode a inner join script_line b on a.id = b.episode_id inner join location c on b.location_id = c.id
								   where c.ID = ". $secondFilter ." order by a.number_in_series desc)a where rownum = 1))b,
				   (select count(distinct b.episode_id) as episode_count
					from script_line b
					inner join episode a on a.id = b.episode_id
					inner join location c on c.id = b.location_id
					where c.ID = ". $secondFilter .")c";
	}
	$statement = oci_parse($connection, $query);
	oci_execute($statement);
	
	while($row=oci_fetch_assoc($statement)) {
		if ($type == 'Character'){
			echo "<table><tr><td>Name</td><td>" . $row['NAME'] .
				"</td></tr><tr><td>First Episode</td><td>". $row['FIRST_EPISODE'] .
				"</td></tr><tr><td>Latest Episode</td><td>". $row['LATEST_EPISODE'] .
				"</td></tr><tr><td>Episode Count</td><td>". $row['EPISODE_COUNT'] .
				"</td></tr></table>";
		}
		else if($type == 'Episode'){
			echo "<table><tr><td>Air Date</td><td>" . $row['AIR_DATE'] . 
					"</td></tr><tr><td>Season</td><td>". $row['SEASON'] . 
					"</td></tr><tr><td>Number in Season</td><td>". $row['NUMBER_IN_SEASON'] . 
					"</td></tr><tr><td>US Viewers</td><td>". $row['US_VIEWERS'] .
					"</td></tr><tr><td>IMDB Rating</td><td>". $row['IMDB_RATING'] .
					"</td></tr><tr><td>Episode Still</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>".  
					"</td></tr><tr><td>Video URL</td><td> <a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a> " .
					"</td></tr></table>";
			
		}
		else{
			echo "<table><tr><td>Name</td><td>" . $row['NAME'] . 
					"</td></tr><tr><td>First Episode</td><td>". $row['FIRST_EPISODE'] . 
					"</td></tr><tr><td>Latest Episode</td><td>". $row['LATEST_EPISODE'] . 
					"</td></tr><tr><td>Episode Count</td><td>". $row['EPISODE_COUNT'] .
					"</td></tr></table>";
		}
	}
	//
	// VERY important to close Oracle Database Connections and free statements!
	//
	oci_free_statement($statement);
	oci_close($connection);
?>
