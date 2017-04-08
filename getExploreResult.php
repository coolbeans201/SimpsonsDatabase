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
	$query2 = $_GET['query2'];
	$query3 = $_GET['query3'];

    /*****************************************************************************************************************************/
	if($queryType == 'TotalViewing')
	{
		$query = "select season, sum(us_viewers) as total_viewers 
		          from episode
				  group by season
				  order by season asc";
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'AverageRating')
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
    /*****************************************************************************************************************************/
	else if ($queryType == 'MostWatchedEpisodes')
	{
		$query = "select rownum as rank, title, us_viewers, number_in_series, season, number_in_season, still_url, video_url
				  from(select title, us_viewers, number_in_series, season, number_in_season, still_url, video_url 
		               from episode
				       where us_viewers is not null
				       order by us_viewers desc)";		
	}
    /*****************************************************************************************************************************/
	else if ($queryType == 'HighestRatedEpisodes')
	{
		$query = "select rownum as rank, title, imdb_rating, number_in_series, season, number_in_season, still_url, video_url
				  from(select title, imdb_rating, number_in_series, season, number_in_season, still_url, video_url 
		               from episode
				       where imdb_rating is not null
				       order by imdb_rating desc)";		
	}
	/*****************************************************************************************************************************/
	else if($queryType == 'TotalDialogue')
	{
		$query = "select season, number_in_season, title, location, word_count, spoken_word
					from script_line s inner join episode e on s.episode_id = e.id
					where speaking_line='true' and character_id=" .$query2 ."
					order by season asc, number_in_season asc";
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'TopCharacter')
	{
		if($query3 == 'simpsons')
		{
			$charType = "";
		}
		else if($query3 == 'nonsimpsons')
		{
			$charType = "not ";
		}

		if($query2 == 'overall')
		{
			if($query3 == 'all')
			{
				$query = "select rownum as rank, name, episode_count
						  from(	select c.name as name, count(distinct scr.episode_id) as episode_count
								from script_line scr
								inner join episode ep on ep.id = scr.episode_id
								inner join character c on c.id = scr.character_id
								group by name
								order by episode_count desc)
						  order by rank asc";
			}
			else
			{
				$query = "	select rownum as rank, name, episode_count
							from(	select c.name as name, count(distinct scr.episode_id) as episode_count
									from script_line scr
									inner join episode ep on ep.id = scr.episode_id
									inner join character c on c.id = scr.character_id
									where name " .$charType ."like '%Simpson%'
									group by name
									order by episode_count desc)
							order by rank asc";		
			}
		}
		else if($query2 == 'season')
		{
			if($query3 == 'all')
			{
				$query = "select s.season as season, name, episode_count
						  from(		select season, c.name as name, count(distinct scr.episode_id) as episode_count
									from script_line scr
									inner join episode ep on ep.id = scr.episode_id
									inner join character c on c.id = scr.character_id
									group by season, name) s
						  inner join
						      (		select season, max(episode_count) as max_count
							  		from(	select season, c.name as name, count(distinct scr.episode_id) as episode_count
											from script_line scr
											inner join episode ep on ep.id = scr.episode_id
											inner join character c on c.id = scr.character_id
											group by season, name)
									group by season) m
						  on s.season = m.season and s.episode_count = m.max_count
						  order by season asc";
			}
			else
			{
				$query = "select s.season, name, episode_count
						  from(		select season, c.name as name, count(distinct scr.episode_id) as episode_count
									from script_line scr
									inner join episode ep on ep.id = scr.episode_id
									inner join character c on c.id = scr.character_id
									where name " .$charType ."like '%Simpson%'
									group by season, name) s
						  inner join
						      (		select season, max(episode_count) as max_count
							  		from(	select season, c.name as name, count(distinct scr.episode_id) as episode_count
											from script_line scr
											inner join episode ep on ep.id = scr.episode_id
											inner join character c on c.id = scr.character_id
											where name " .$charType ."like '%Simpson%'
											group by season, name)
									group by season) m
						  on s.season = m.season and s.episode_count = m.max_count
						  order by season asc";		
			}
		}
		else if($query2 == 'episode')
		{
			if($query3 == 'all')
			{
				$query = "select season, number_in_season, title, character, max_times, still_url, video_url
						  from(	select ep.id, character, count(character) as times
								from script_line scr inner join episode ep on scr.episode_id = ep.id
								group by ep.id, character) char_ep
						  inner join 
							  (	select id, max(times) as max_times
								from(	select ep.id, character, count(character) as times
										from script_line scr inner join episode ep on scr.episode_id = ep.id
										group by ep.id, character)
								group by id) max_ep
						  on char_ep.id = max_ep.id and char_ep.times = max_ep.max_times
						  inner join episode ep on max_ep.id = ep.id 
						  order by season asc, number_in_season asc";
			}
			else
			{
				$query = "select season, number_in_season, title, character, max_times, still_url, video_url
						  from(	select ep.id, character, count(character) as times
								from script_line scr inner join episode ep on scr.episode_id = ep.id
								where character " .$charType ."like '%Simpson%'
								group by ep.id, character) char_ep
						  inner join 
							  (	select id, max(times) as max_times
								from(	select ep.id, character, count(character) as times
										from script_line scr inner join episode ep on scr.episode_id = ep.id
										where character " .$charType ."like '%Simpson%'
										group by ep.id, character)
								group by id) max_ep
						  on char_ep.id = max_ep.id and char_ep.times = max_ep.max_times
						  inner join episode ep on max_ep.id = ep.id 
						  order by season asc, number_in_season asc";		
			}
		}
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'TopLocation')
	{
		if($query2 == 'overall')
		{
			$query = "select rownum as rank, name, episode_count
		          	  from(	select l.name as name, count(distinct scr.episode_id) as episode_count
				       		from script_line scr
				       		inner join episode ep on ep.id = scr.episode_id
				       		inner join location l on l.id = scr.location_id
				      		group by name
					   		order by episode_count desc)
				  	  order by rank asc";	
		}
		else if($query2 == 'episode')
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
		else if($query2 == 'season')
		{
			$query = "select rownum as rank, location, times
					  from(	select location, count(location) as times
					  		from script_line scr inner join episode ep on scr.episode_id = ep.id
					  		where season = " . $query3 ."
					  		group by season, location
					  		order by times desc)
					  order by rank asc";
		}
		else if($query2 == 'character')
		{
			$query = "select rownum as rank, location, times
				  	  from(	select character_id, location, count(location) as times
				  			from script_line
							where character_id = ". $query3 ."
							group by character_id, location
							order by times desc)";
		}
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'MostSpokenLine')
	{
		if($query2 == 'overall')
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
		else if($query2 == 'episode')
		{
			$query = "select season, number_in_season, title, character, spoken_word as line, max_times, still_url, video_url
					  from (	select episode_id, character_id as s_id, character, spoken_word, count(spoken_word) as times
								from script_line
								where speaking_line='true'
								group by episode_id, character_id, character, spoken_word) s
					  inner join
							(	select episode_id, max(times) as max_times
								from(	select episode_id, character_id as s_id, character, spoken_word, count(spoken_word) as times
										from script_line
										where speaking_line='true'
										group by episode_id, character_id, character, spoken_word)
								group by episode_id) m
					  on s.times = m.max_times and s.episode_id = m.episode_id
					  inner join episode ep on m.episode_id = ep.id 
					  order by season asc, number_in_season asc";
		}
		else if($query2 == 'season')
		{
			$query = "select s.season as season, character, spoken_word as line, times as count
					  from (	select season, character_id as s_id, character, spoken_word, count(spoken_word) as times
								from script_line scr inner join episode e on scr.episode_id = e.id
								where speaking_line='true'
								group by season, character_id, character, spoken_word) s
					  inner join
							(	select season, max(times) as max_times
								from(	select season, character_id as s_id, character, spoken_word, count(spoken_word) as times
										from script_line scr inner join episode e on scr.episode_id = e.id
										where speaking_line='true'
										group by season, character_id, character, spoken_word)
								group by season) m
					  on s.times = m.max_times and s.season = m.season
					  order by season asc";
		}
		else if($query2 == 'character')
		{
			$query = "select rownum as rank, line, times
					  from (select character_id, character, spoken_word as line, count(spoken_word) as times
							from script_line
							where speaking_line='true' and character_id=" .$query3 ."
							group by character_id, character, spoken_word
							order by times desc) 
					  order by rank asc";
		}
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'WordsSpoken')
	{
		if($query3 == 'simpsons')
		{
			$charType = "";
		}
		else if($query3 == 'nonsimpsons')
		{
			$charType = "not ";
		}

		if($query2 == 'overall')
		{
			if($query3 == 'all')
			{
				$query = "select rownum as rank, character, count
						from (select character_id, character, sum(word_count) as count
								from script_line
								where speaking_line='true' and word_count is not null
								group by character_id, character
								order by count desc)
						order by rank asc";
			}
			else
			{
				$query = "select rownum as rank, character, count
						  from (select character_id, character, sum(word_count) as count
								from script_line
								where speaking_line='true' and word_count is not null and character " .$charType. "like '%Simpson%'
								group by character_id, character
								order by count desc)
						  order by rank asc";
			}
		}
		else if($query2 == 'perEpisode')
		{
			if($query3 == 'all')
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
			else
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
						  where name " .$charType. "like '%Simpson%'
						  order by rank asc";
			}
		}
		else if($query2 == 'episode')
		{
			if($query3 == 'all')
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
			else
			{
				$query = "select season, number_in_season, title, character, count, still_url, video_url
						  from(	select ep.id, character, sum(word_count) as count
								from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character " .$charType. "like '%Simpson%'
								group by ep.id, character) count_ep
						  inner join 
							(	select id, max(count) as max_count
								from(	select ep.id, character, sum(word_count) as count
										from script_line scr inner join episode ep on scr.episode_id = ep.id
										where speaking_line='true' and character " .$charType. "like '%Simpson%'
										group by ep.id, character)
								group by id) max_ep
						  on count_ep.id = max_ep.id and count_ep.count = max_ep.max_count
						  inner join episode ep on max_ep.id = ep.id 
						  order by season asc, number_in_season asc";
			}
		}
		else if($query2 == 'season')
		{
			if($query3 == 'all')
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
			else
			{
				$query = "select count_s.season, character, count
						  from(	select season, character, sum(word_count) as count
								from script_line scr inner join episode ep on scr.episode_id = ep.id
								where speaking_line='true' and character " .$charType. "like '%Simpson%'
								group by season, character) count_s
						  inner join 
							(	select season, max(count) as max_count
								from(	select season, character, sum(word_count) as count
										from script_line scr inner join episode ep on scr.episode_id = ep.id
										where speaking_line='true' and character " .$charType. "like '%Simpson%'
										group by season, character)
								group by season) max_s
						  on count_s.season = max_s.season and count_s.count = max_s.max_count
						  order by season asc";
			}
		}
	}

	$statement = oci_parse($connection, $query);
	oci_execute($statement);

    /*****************************************************************************************************************************/
	if ($queryType == 'TotalViewing')
	{
		echo "<table border='1'>\n";				
		echo '<tr><th>Season Number</th><th>Total US Viewers (millions)</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
    /*****************************************************************************************************************************/
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
    /*****************************************************************************************************************************/
	else if ($queryType == 'MostWatchedEpisodes')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Rank</th><th>Title</th><th>US Viewers (millions)</th>
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
    /*****************************************************************************************************************************/
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
    /*****************************************************************************************************************************/
	else if($queryType == 'TotalDialogue')
	{
		echo "<table border='1'>\n";
		echo '<tr><th>Season Number</th><th>Episode Number</th><th>Episode Title</th><th>Location</th><th>Word Count</th><th>Dialogue</th></tr>';
		while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) 
			{
				echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
			}
			echo "</tr>\n";
		}
		echo "</table><br>";
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'TopCharacter')
	{
		if($query2 == 'overall')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Rank</th><th>Character</th><th>Episodes Featured</th></tr>';
			while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) 
				{
					echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table><br>";
		}
		else if($query2 == 'episode')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Character</th>
									<th>Times Featured</th><th>Episode Still</th>
									<th>URL</th></tr>';
			while($row=oci_fetch_assoc($statement)) {
				echo "<tr><td>" . $row['SEASON'] . 
					"</td><td>" . $row['NUMBER_IN_SEASON'] . 
				"</td><td>" . $row['TITLE'] . 
				"</td><td>" . $row['CHARACTER'] . 
				"</td><td>" . $row['MAX_TIMES'] . 
				"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
			}
			echo "</table><br>";
		}
		else if($query2 == 'season')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Season</th><th>Character</th><th>Episodes Featured</th></tr>';
			while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) 
				{
					echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table><br>";
		}
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'TopLocation')
	{
		if($query2 == 'overall')
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
		else if($query2 == 'episode')
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
		else if($query2 == 'season')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Rank</th><th>Location</th><th>Times Featured</th></tr>';
			while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) 
				{
					echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table><br>";
		}
		else if($query2 == 'character')
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
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'MostSpokenLine')
	{
		if($query2 == 'overall')
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
		else if($query2 == 'episode')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Season Number</th><th>Episode Number</th><th>Title</th><th>Character</th><th>Line</th>
									<th>Times Spoken</th><th>Episode Still</th>
									<th>URL</th></tr>';
			while($row=oci_fetch_assoc($statement)) {
				echo "<tr><td>" . $row['SEASON'] . 
					"</td><td>" . $row['NUMBER_IN_SEASON'] . 
				"</td><td>" . $row['TITLE'] . 
				"</td><td>" . $row['CHARACTER'] . 
				"</td><td>" . $row['LINE'] . 
				"</td><td>" . $row['MAX_TIMES'] . 
				"</td><td> <img src=" .$row['STILL_URL'] . " alt=" .$row['STILL_URL']. "height='200' width='200'>" . 
				"</td><td><a href='" . $row['VIDEO_URL'] . "' " . "target='_blank'>Click here to watch the Episode</a></td></tr>";
			}
			echo "</table><br>";
		}
		else if($query2 == 'season')
		{
			echo "<table border='1'>\n";
			echo '<tr><th>Season</th><th>Character</th><th>Line</th><th>Times Spoken</th></tr>';
			while ($row = oci_fetch_array($statement, OCI_ASSOC+OCI_RETURN_NULLS)) {
				foreach ($row as $item) 
				{
					echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
				}
				echo "</tr>\n";
			}
			echo "</table><br>";
		}
		else if($query2 == 'character')
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
	}
    /*****************************************************************************************************************************/
	else if($queryType == 'WordsSpoken')
	{
		if($query2 == 'overall')
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
		else if($query2 == 'perEpisode')
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
		else if($query2 == 'episode')
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
		else if($query2 == 'season')
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
	}
	
	oci_free_statement($statement);
	oci_close($connection);
?>
