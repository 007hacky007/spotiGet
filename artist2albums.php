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
    echo $album."\n";
    foreach($api->getAlbumTracks($albumID) as $name => $trackID) {
        $tracks .= sprintf("%02d_%s ", $i, str_replace(" ", "_", $name));
        $i++;
    }
    echo wordwrap($tracks);
    echo "\n-------------------\n\n";
}

$albumsSelected = readline::readAnswers($albums);
foreach($albumsSelected as $albumName => $spotifyID){
    echo "spotify:album:$spotifyID";
}