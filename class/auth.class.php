<?php

class auth {
    public $client_id = false;
    public $client_secret = false;
    private $token = false;
    private $token_type = false;
    private $refresh_ts = false;
    private $expiry = false;

    private function encodeCredentials(){
        return base64_encode($this->client_id.":".$this->client_secret);
    }

    private function getNewToken(){
        if(empty($this->client_id) || empty($this->client_secret)) throw new Exception('$this->client_id and/or $this->client_secret is empty. Please set them first.');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://accounts.spotify.com/api/token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("grant_type" => "client_credentials")));

        $headers = array();
        $headers[] = "Authorization: Basic ".$this->encodeCredentials();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('Curl error:' . curl_error($ch));
        }
        curl_close ($ch);

        $resultDecoded = json_decode($result, true);

        $this->token = $resultDecoded["access_token"];
        $this->token_type = $resultDecoded["token_type"];
        $this->expiry = $resultDecoded["expires_in"];
    }


    private function isTokenValid(){
        if((time()-$this->refresh_ts) >= $this->expiry) return false;

        return true;
    }

    public function getToken(){
        if(empty($this->token) || ($this->isTokenValid() === false)){
            $this->getNewToken();
        }

        // token should be okay -> just return it
        return $this->token;
    }
}