<style>body{margin: 0; padding: 0; font-family: sans-serif; background: #34495e;}.box{width: 300px; padding: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #191919; text-align: center;}.box h1{color: white; text-transform: uppercase; font-weight: 500;}.box button{border:0; background: none; display: block; margin: 20px auto; text-align: center; border: 2px solid #2ecc71; padding: 14px 40px; outline: none; color: white; border-radius: 24px; transition: 0.25s; cursor: pointer;}.box buton:hover{background: #2ecc71;}</style>
<?php
require __DIR__ . '/classes/provider.php';

$config = json_decode(file_get_contents('config.json'));

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', $config->clientId);
define('OAUTH2_CLIENT_SECRET', $config->clientSecret);

$authorizeURL = 'https://discordapp.com/api/oauth2/authorize';
$tokenURL = 'https://discordapp.com/api/oauth2/token';
$apiURLBase = 'https://discordapp.com/api/users/@me';

$provider = new provider($config->clientId, $config->clientSecret, $config->redirectUrl);

session_start();

// Start the login process by sending the user to Discord's authorization page
if(get('action') == 'login') {

    // Redirect the user to Discord's authorization page
    header('Location: '.$provider->getAuthorizationUrl());
    die();
}


// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {

    // Exchange the auth code for a token
    $token = $provider->apiRequest($tokenURL, array(
        "grant_type" => "authorization_code",
        'client_id' => OAUTH2_CLIENT_ID,
        'client_secret' => OAUTH2_CLIENT_SECRET,
        'redirect_uri' => 'https://countr.thefabicraft.com/',
        'code' => get('code')
    ));
    $logout_token = $token->access_token;
    $_SESSION['access_token'] = $token->access_token;


    header('Location: ' . $_SERVER['PHP_SELF']);
}

if(session('access_token')) {
    $user = $provider->apiRequest($apiURLBase);
    $guilds = $provider->apiRequest($apiURLBase.'/guilds');

    $_SESSION["user"] = $user;
    $_SESSION["guilds"] = $guilds;

    echo '<div class="box"><p style="color: white">Redirecting...</p></div>';
    echo '<script>
            window.location.replace("/dashboard.php");
          </script>';

} else {
    echo '<div class="box"><h1>Login to Countr</h1><a href="/?action=login">
           <button>Login with Discord</button>
		</a></div>';
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

function get($key, $default=NULL) {
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default=NULL) {
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

?>