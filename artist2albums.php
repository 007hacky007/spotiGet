<?php
include("inc/common.php");
// this generates spotify URIs of all artist's albums
$auth = new auth();
$auth->client_id = $GLOBALS["config"]["client_id"];
$auth->client_secret = $GLOBALS["config"]["client_secret"];

$api = new playlist();
$api->token = $auth->getToken();

$artists = $api->getArtists(readline::readString("Enter artist to search for"));

if(count($artists) < 1) die("No artist found.");

$artist = readline::readAnswers($artists);

if(count($artist) > 1) die("Please select only one artist");
if(count($artist) < 1) die("No artist selected");

$albums = $api->getArtistAlbums(array_values($artist)[0]);

// print tracks in albums
foreach($albums as $album => $albumID){
    $i=1;
    $tracks = "";
    $err = "";
    $totalAlbumDurationMs[$album] = 0;
    echo $album."\n";
    foreach($api->getAlbumTracks($albumID) as $name => $trackID) {
        $tracks .= sprintf("%02d_%s ", $i, str_replace(" ", "_", $name));
        $i++;
    }

    $totalAlbumDurationMs[$album] += $api->getLastAlbumLength();
    echo wordwrap($tracks);
    echo "\n---------$err----------\n\n";
}

$albumsSelected = readline::readAnswers($albums);

$totalDurationMs = 0;
foreach($albumsSelected as $albumName => $spotifyID){
    if(isset($totalAlbumDurationMs[$albumName])) $totalDurationMs += $totalAlbumDurationMs[$albumName];
    echo "spotify:album:$spotifyID\n";
}

$totalDurationS = (0.6 * $totalDurationMs) / 1000; // subtract 40%, b/c spotiload have ~150% speedup
echo "\nDL time (approx): ".gmdate("H \h\o\u\\r\s i \m\i\\n\u\\t\\e\s", $totalDurationS)."\n";