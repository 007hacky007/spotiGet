<?php

class playlist {
    public $token;
    public $token_type = "Bearer";
    const apiDomain = "api.spotify.com";
    const market = "CZ";

    private function apiRequest($endpoint, array $params){
        if(empty($this->token)) throw new Exception("Missing token (\$this->token)");
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://".self::apiDomain.$endpoint."?".http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: ".$this->token_type." ".$this->token;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Curl error:' . curl_error($ch));
        }
        curl_close ($ch);

        return json_decode($result, true);
    }

    private function stripApiResponse($response, $subFolder = null){
        $response2 = ($subFolder === null) ? $response["items"] : $response[$subFolder]["items"];
        if(count($response2) < 1) return array();
        $result = array();
        foreach($response2 as $item){
            $result[$item["name"]] = $item["id"];
        }

        return $result;
    }

    public function getArtists($artistName){
        return $this->stripApiResponse($this->apiRequest("/v1/search", array("type" => "artist", "q" => $artistName, "market" => self::market)), "artists");
    }

    public function getArtistAlbums($spotifyArtistID){
        return $this->stripApiResponse($this->apiRequest("/v1/artists/$spotifyArtistID/albums", array("market" => self::market)));
    }

    public function getAlbumTracks($spotifyAlbumID){
        return $this->stripApiResponse($this->apiRequest("/v1/albums/$spotifyAlbumID/tracks", array("market" => self::market)));
    }
}