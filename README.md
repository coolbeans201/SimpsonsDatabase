# SimpsonsDatabase
Database project for COP5725, done by Kimberly Branch, Timmy Chandy, Will Posey, and Matt Weingarten. This database stores character, episode, location, and dialogue information for every episode of *The Simpsons*.

# Schema
Character (ID (PK), Name, Gender (optional))

Episode (ID (PK), Title, Air Date, Season, Number in Season, Number in Series, US Viewers, IMDb Rating, Still URL, Video URL)

Location (ID (PK), Name)

Script line (ID (PK), Episode ID (FK), Line Number, Raw Text, Timestamp, Speaking Line, Character ID (FK), Location ID (FK), Character, Location, Spoken Words, Word Count)

# Web Layout
The home page will introduce you to the theme of the website, which is obviously *The Simpsons*. In the top right corner of this page (and every page, for that matter), you'll see the buttons to transition to different pages.

The two main sections of the website are the retrieval section and the "Explore" section. The retrieval section allows you to grab the relevant information for a tuple in a relation. For characters, you'll be able to see the name of the character, the first episode they appeared in, the last episode they appeared in, and a total count of episodes that they've appeared in. This is also the same for locations.

For episodes, the user will see the name of the episode, the air date, the season number, which episode number it is in the series, how many US viewers watched it (in millions), and the IMDb rating for that episode. To the right of that information, you'll have a still of the episode, which is provided in URL form in the database.

In the middle of the screen, you'll see an embedded video player which contains that *Simpsons* episode, which is provided by FXX. Under the video, you'll be able to see the whole dialogue of the episode.

You can choose which character, location, or episode you want through two comboboxes. Once your selection is made and you get the information, you can move to the next or previous sorted record in the table through the "Next" and "Previous" buttons. When you're at the first record, the "Previous" button will disappear and when you're at the last record, the "Next" button will disappear.

The "Explore" section will allow you to perform a lot more statistical queries on the dataset. Here, we can answer such questions as:

1. How has total viewership of *The Simpsons* changed by season?

2. How has average ratings of *The Simpsons* changed by season?

3. What is each character's most common line?

4. Who are the most prevalent characters in the series?

5. What are the most prevalent locations in the series?

6. What are the most-watched episodes?

7. What are the highest-rated episodes?

8. What is each character's total dialogue?

More can be added based on ideas that pop into our heads throughout the project.
