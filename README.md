# SimpsonsDatabase
Database project for COP5725. This database stores character, episode, location, and dialogue information for every episode of The Simpsons.

# Schema
Character (ID (PK), Name, Gender (optional))

Episode (ID (PK), Title, Air Date, Season, Number in Season, Number in Series, US Viewers, IMDb Rating, Still URL, Video URL)

Location (ID (PK), Name)

Script line (ID (PK), Episode ID (FK), Line Number, Raw Text, Timestamp, Speaking Line, Character ID (FK), Location ID (FK), Character, Location, Spoken Words, Word Count)
