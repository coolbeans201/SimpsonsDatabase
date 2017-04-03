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
	else if($queryType == 'TopLocationsCharacter')
	{
		$query = "select rownum as rank, location, times
				  from(	select character_id, location, count(location) as times
				  		from script_line
						where character_id = ". $queryName ."
						group by character_id, location
						order by times desc)";
	}
	else if($queryType == 'TopLocationsEpisode')
	{
		$query = "select season, number_in_season, title, location, max_times, still_url, video_url
				  from(	select ep.id, location, count(location) as times
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						group by ep.id, location) loc_ep
				  inner join 
				  	  (	select id, max(times) as max_times
						from(	select ep.id, location, count(location) as times
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								group by ep.id, location)
						group by id) max_ep
				  on loc_ep.id = max_ep.id and loc_ep.times = max_ep.max_times
				  inner join episode ep on max_ep.id = ep.id 
				  order by season asc, number_in_season asc";
	}
	else if($queryType == 'TopLocationsSeason')
	{
		$query = "select location, count(location) as times
				  from script_line scr inner join episode ep on scr.episode_id = ep.id
				  where season = " . $queryName ."
				  group by season, location
				  order by times desc";
	}
	else if ($queryType == 'MostSpokenLine')
	{
		$query = "select rownum as rank, character, line, count
				  from (select character, spoken_word as line, max_times as count
						from (	select character_id as s_id, character, spoken_word, count(spoken_word) as times
								from script_line
								where speaking_line='true'
								group by character_id, character, spoken_word) 
						s
						inner join
							(	select s_id as m_id, max(times) as max_times
								from (select character_id as s_id, character, spoken_word, count(spoken_word) as times
									  from script_line
									  where speaking_line='true'
									  group by character_id, character, spoken_word)
								group by s_id) 
						m
						on m.m_id = s.s_id and s.times = m.max_times
						order by count desc)
				 order by rank asc";
	}
	else if ($queryType == 'MostSpokenLineCharacter')
	{
		$query = "select rownum as rank, line, times
				  from (select character_id, character, spoken_word as line, count(spoken_word) as times
						from script_line
						where speaking_line='true' and character_id=" .$queryName ."
						group by character_id, character, spoken_word
						order by times desc) 
				 order by rank asc";
	}
	else if($queryType == 'WordsSpokenAll')
	{
		$query = "select rownum as rank, character, count
				  from (select character_id, character, sum(word_count) as count
						from script_line
						where speaking_line='true' and word_count is not null
						group by character_id, character
						order by count desc)
				 order by rank asc";
	}
	else if($queryType == 'WordsSpokenSimpsons')
	{
		$query = "select rownum as rank, character, count
				  from (select character_id, character, sum(word_count) as count
						from script_line
						where speaking_line='true' and word_count is not null and character like '%Simpson%'
						group by character_id, character
						order by count desc)
				 order by rank asc";
	}
	else if($queryType == 'WordsSpokenNonSimpsons')
	{
		$query = "select rownum as rank, character, count
				  from (select character_id, character, sum(word_count) as count
						from script_line
						where speaking_line='true' and word_count is not null and character not like '%Simpson%'
						group by character_id, character
						order by count desc)
				 order by rank asc";
	}
	else if($queryType == 'WordsSpokenByEpisodeAll')
	{
		$query = "select season, number_in_season, title, character, count, still_url, video_url
				  from(	select ep.id, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true'
						group by ep.id, character) count_ep
				  inner join 
				  	  (	select id, max(count) as max_count
						from(	select ep.id, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true'
								group by ep.id, character)
						group by id) max_ep
				  on count_ep.id = max_ep.id and count_ep.count = max_ep.max_count
				  inner join episode ep on max_ep.id = ep.id 
				  order by season asc, number_in_season asc";
	}
	else if($queryType == 'WordsSpokenByEpisodeSimpsons')
	{
		$query = "select season, number_in_season, title, character, count, still_url, video_url
				  from(	select ep.id, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true' and character like '%Simpson%'
						group by ep.id, character) count_ep
				  inner join 
				  	  (	select id, max(count) as max_count
						from(	select ep.id, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character like '%Simpson%'
								group by ep.id, character)
						group by id) max_ep
				  on count_ep.id = max_ep.id and count_ep.count = max_ep.max_count
				  inner join episode ep on max_ep.id = ep.id 
				  order by season asc, number_in_season asc";
	}
	else if($queryType == 'WordsSpokenByEpisodeNonSimpsons')
	{
		$query = "select season, number_in_season, title, character, count, still_url, video_url
				  from(	select ep.id, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true' and character not like '%Simpson%'
						group by ep.id, character) count_ep
				  inner join 
				  	  (	select id, max(count) as max_count
						from(	select ep.id, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character not like '%Simpson%'
								group by ep.id, character)
						group by id) max_ep
				  on count_ep.id = max_ep.id and count_ep.count = max_ep.max_count
				  inner join episode ep on max_ep.id = ep.id 
				  order by season asc, number_in_season asc";
	}
	else if($queryType == 'WordsSpokenPerEpisodeAll')
	{
		$query = "select rownum as rank, name, ep_count, words_per_ep
				  from	(	select name, ep_count, round((word_count/ep_count),3) as words_per_ep
							from 	(	select distinct character_id, sum(word_count) as word_count
										from script_line
										where speaking_line='true' and word_count is not null
										group by character_id) words
							inner join
									(	select character_id, count(distinct episode_id) as ep_count
										from script_line
										group by character_id) ep_featured
							on words.character_id = ep_featured.character_id 
							inner join character c on words.character_id = c.id
							where ep_count > 0
							order by words_per_ep desc)
				  order by rank asc";
	}
	else if($queryType == 'WordsSpokenPerEpisodeSimpsons')
	{
		$query = "select rownum as rank, name, ep_count, words_per_ep
				  from	(	select name, ep_count, round((word_count/ep_count),3) as words_per_ep
							from 	(	select distinct character_id, sum(word_count) as word_count
										from script_line
										where speaking_line='true' and word_count is not null
										group by character_id) words
							inner join
									(	select character_id, count(distinct episode_id) as ep_count
										from script_line
										group by character_id) ep_featured
							on words.character_id = ep_featured.character_id 
							inner join character c on words.character_id = c.id
							where ep_count > 0
							order by words_per_ep desc)
				  where name like '%Simpson%'
				  order by rank asc";
	}
	else if($queryType == 'WordsSpokenPerEpisodeNonSimpsons')
	{
		$query = "select rownum as rank, name, ep_count, words_per_ep
				  from	(	select name, ep_count, round((word_count/ep_count),3) as words_per_ep
							from 	(	select distinct character_id, sum(word_count) as word_count
										from script_line
										where speaking_line='true' and word_count is not null
										group by character_id) words
							inner join
									(	select character_id, count(distinct episode_id) as ep_count
										from script_line
										group by character_id) ep_featured
							on words.character_id = ep_featured.character_id 
							inner join character c on words.character_id = c.id
							where ep_count > 0
							order by words_per_ep desc)
				  where name not like '%Simpson%'
				  order by rank asc";
	}
	else if($queryType == 'WordsSpokenSeasonAll')
	{
		$query = "select count_s.season, character, count
				  from(	select season, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true'
						group by season, character) count_s
				  inner join 
				  	  (	select season, max(count) as max_count
						from(	select season, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true'
								group by season, character)
						group by season) max_s
				  on count_s.season = max_s.season and count_s.count = max_s.max_count
				  order by season asc";
	}
	else if($queryType == 'WordsSpokenSeasonSimpsons')
	{
		$query = "select count_s.season, character, count
				  from(	select season, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true' and character like '%Simpson%'
						group by season, character) count_s
				  inner join 
				  	  (	select season, max(count) as max_count
						from(	select season, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character like '%Simpson%'
								group by season, character)
						group by season) max_s
				  on count_s.season = max_s.season and count_s.count = max_s.max_count
				  order by season asc";
	}
	else if($queryType == 'WordsSpokenSeasonNonSimpsons')
	{
		$query = "select count_s.season, character, count
				  from(	select season, character, sum(word_count) as count
				  		from script_line scr inner join episode ep on scr.episode_id = ep.id
						where speaking_line='true' and character not like '%Simpson%'
						group by season, character) count_s
				  inner join 
				  	  (	select season, max(count) as max_count
						from(	select season, character, sum(word_count) as count
				  				from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character not like '%Simpson%'
								group by season, character)
						group by season) max_s
				  on count_s.season = max_s.season and count_s.count = max_s.max_count
				  order by season asc";
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
	else if ($queryType == 'MostWatchedEpisodes')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Title</th><th>US Viewers</th>
								 <th>Episode Number</th><th>Season Number</th>
								 <th>Number in Season</th><th>Episode Still</th>
								 <th>URL</th></tr>';

		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['RANK'] . 
				"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['US_VIEWERS'] . 
			"</td><td>" . $row['NUMBER_IN_SERIES'] . 
			"</td><td>" . $row['SEASON'] . 
			"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
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
								 
		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['RANK'] . 
				"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['IMDB_RATING'] . 
			"</td><td>" . $row['NUMBER_IN_SERIES'] . 
			"</td><td>" . $row['SEASON'] . 
			"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
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
	else if($queryType == 'TopLocationsCharacter')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Location</th><th>Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if($queryType == 'TopLocationsEpisode')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Location</th>
								 <th>Times Featured</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['LOCATION'] . 
			"</td><td>" . $row['MAX_TIMES'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
		}
		echo "</table><br>";
	}
	else if($queryType == 'TopLocationsSeason')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Location</th><th>Times Featured</th></tr>';
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
		echo '<tr><th>Rank</th><th>Character</th><th>Line</th><th>Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if ($queryType == 'MostSpokenLineCharacter')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Line</th><th>Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
	else if($queryType == 'WordsSpokenAll')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenNonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenByEpisodeAll')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Character</th>
								 <th>Word Count</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['CHARACTER'] . 
			"</td><td>" . $row['COUNT'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenByEpisodeSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Character</th>
								 <th>Word Count</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['CHARACTER'] . 
			"</td><td>" . $row['COUNT'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenByEpisodeNonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Character</th>
								 <th>Word Count</th><th>Episode Still</th>
								 <th>URL</th></tr>';
		while($row=oci_fetch_assoc($statement)) {
			echo "<tr><td>" . $row['SEASON'] . 
				"</td><td>" . $row['NUMBER_IN_SEASON'] . 
			"</td><td>" . $row['TITLE'] . 
			"</td><td>" . $row['CHARACTER'] . 
			"</td><td>" . $row['COUNT'] . 
			"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
			"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenPerEpisodeAll')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episodes Featured</th><th>Words per Episode</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenPerEpisodeSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episodes Featured</th><th>Words per Episode</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenPerEpisodeNonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Character</th><th>Episodes Featured</th><th>Words per Episode</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenSeasonAll')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenSeasonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}
	else if($queryType == 'WordsSpokenSeasonNonSimpsons')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Character</th><th>Word Count</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";	
	}

	
	oci_free_statement($statement);
	oci_close($connection);
?>
