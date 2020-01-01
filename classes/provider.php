<?php


class provider
{
    function __construct($clientId, $clientSecret, $redirectUrl) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUrl = $redirectUrl;
    }

    function getAuthorizationUrl() {
        $params = array(
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => 'identify guilds'
        );
        return 'https://discordapp.com/api/oauth2/authorize' . '?' . http_build_query($params);
    }

    function apiRequest($url, $post=FALSE, $headers=array()) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $response = curl_exec($ch);


        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

        $headers[] = 'Accept: application/json';

        if(session('access_token'))
            $headers[] = 'Authorization: Bearer ' . session('access_token');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response);
    }
}