## SpotiDL

SpotiDL is very simple set of PHP classes written in order to get data and use Spotify API.
While I know there are at least already two existing Spotify PHP API solutions,
I missed one simple, say "vanilla", solution which would not require stuff like composer.


## Prerequisites

* Spotify developer **CLIENT_ID** and **CLIENT_SECRET** (you can get those on https://developer.spotify.com/ -> applications)
* basic PHP extensions
    * curl
    * JSON
    * readline (just readline class)
* tested on PHP 5.6.19 / OS X

## Intended usages (and the actual reason I wrote this)

* Generate albums' Spotify URIs for further usage (psst! "usage" means "download artist discography") with **spotiload**
(https://bitbucket.org/OlgahWolgah/spotiload - great piece of sw btw!)
* \[not done yet\] Convert set of mp3s in directory to Spotify playlists (you know what I mean... you have set of mp3s in
layout like this: artist/track_name.mp3 and you want it convert to Spotify playlists. I did not find any
solution for this stuff)

## Current features

* Fetching of Spotify acccess token
* `playlist->getArtists(string $artistName)` search for artists
    * input: artist name (string)
    * output: artist name => Spotify artist ID (array)
* `playlist->getArtistAlbums(string $spotifyArtistID)` get all artist's albums
    * input: Spotify artist ID (string)
    * output: album name => Spotify album ID (array)
* `playlist->getAlbumTracks(string $spotifyAlbumID)` get all album's tracks
    * input: Spotify album ID (string)
    * output: track name => Spotify track ID (array)
* `readline->readAnswers(array $optionsToShow)` CLI interaction with user to provide simple artist/album selection
from provided list
    * input: option name => any value, i.e. Spotify ID returned by playlist class functions (array)
    * output: selected options in the same format as provided on input
* `readline->readString(string $description)` just basic readline implementation
    * input: string to print user before the readline prompt (string)
    * output: user input from STDIN (string)

## Todo

* Write at least some documentation (but c'mon, it is so simple and self-explanatory)
* Extend classes to include authorization in order to perform changes (add playlists automagically)
 to Spotify user account (this would require some sort of web server in order to talk oAuth)
 
## License 

Use it however you want and need, just please include Copyright and LICENSE file (MIT license)