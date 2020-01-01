<meta charset="utf-8" />
<style>body{margin: 0; padding: 0; font-family: sans-serif; background: #34495e;}.box{width: 300px; padding: 40px; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); background: #191919; text-align: center;}.box h1{color: white; text-transform: uppercase; font-weight: 500;}.box button{border:0; background: none; display: block; margin: 20px auto; text-align: center; border: 2px solid #2ecc71; padding: 14px 40px; outline: none; color: white; border-radius: 24px; transition: 0.25s; cursor: pointer;}.box buton:hover{background: #2ecc71;}</style>
<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
<link rel="manifest" href="/assets/favicon/site.webmanifest">
<link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#424242">
<meta name="msapplication-TileColor" content="#2b5797">
<meta name="theme-color" content="#ffffff">
<script>
    console.log('                                                                                                                  \n' + '                                                                                                                  \n' + '        CCCCCCCCCCCCC                                                            tttt                             \n' + '     CCC::::::::::::C                                                         ttt:::t                             \n' + '   CC:::::::::::::::C                                                         t:::::t                             \n' + '  C:::::CCCCCCCC::::C                                                         t:::::t                             \n' + ' C:::::C       CCCCCC   ooooooooooo   uuuuuu    uuuuuunnnn  nnnnnnnn    ttttttt:::::ttttttt   rrrrr   rrrrrrrrr   \n' + 'C:::::C               oo:::::::::::oo u::::u    u::::un:::nn::::::::nn  t:::::::::::::::::t   r::::rrr:::::::::r  \n' + 'C:::::C              o:::::::::::::::ou::::u    u::::un::::::::::::::nn t:::::::::::::::::t   r:::::::::::::::::r \n' + 'C:::::C              o:::::ooooo:::::ou::::u    u::::unn:::::::::::::::ntttttt:::::::tttttt   rr::::::rrrrr::::::r\n' + 'C:::::C              o::::o     o::::ou::::u    u::::u  n:::::nnnn:::::n      t:::::t          r:::::r     r:::::r\n' + 'C:::::C              o::::o     o::::ou::::u    u::::u  n::::n    n::::n      t:::::t          r:::::r     rrrrrrr\n' + 'C:::::C              o::::o     o::::ou::::u    u::::u  n::::n    n::::n      t:::::t          r:::::r            \n' + ' C:::::C       CCCCCCo::::o     o::::ou:::::uuuu:::::u  n::::n    n::::n      t:::::t    ttttttr:::::r            \n' + '  C:::::CCCCCCCC::::Co:::::ooooo:::::ou:::::::::::::::uun::::n    n::::n      t::::::tttt:::::tr:::::r            \n' + '   CC:::::::::::::::Co:::::::::::::::o u:::::::::::::::un::::n    n::::n      tt::::::::::::::tr:::::r            \n' + '     CCC::::::::::::C oo:::::::::::oo   uu::::::::uu:::un::::n    n::::n        tt:::::::::::ttr:::::r            \n' + '        CCCCCCCCCCCCC   ooooooooooo       uuuuuuuu  uuuunnnnnn    nnnnnn          ttttttttttt  rrrrrrr            \n' + '                                                                                                                  \n' + '                                                                                                                  ');
    console.log('[COUNTR] Initializing...')
</script>
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
echo "<script>console.log('[COUNTR] Provider initialized.')</script>";

session_start();
echo "<script>console.log('[COUNTR] Session started.')</script>";

// Start the login process by sending the user to Discord's authorization page
if(get('action') == 'login') {

    // Redirect the user to Discord's authorization page
    echo "<script>console.log('[COUNTR] Redirecting to login page...')</script>";
    header('Location: '.$provider->getAuthorizationUrl());
    die();
}

if(get('action') == 'logout') {

    // Redirect the user to Discord's authorization page
    echo "<script>console.log('[COUNTR] Destroying session...')</script>";
    session_destroy();
    header('Location: /');
    die();
}


// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if(get('code')) {

    // Exchange the auth code for a token
    echo "<script>console.log('[COUNTR] Getting token.')</script>";
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
    $_SESSION["state"] = true;

    echo '<div class="box"><p style="color: white">Redirecting...</p></div>';
    echo "<script>console.log('[COUNTR] Redirecting to dashboard...')</script>";
    if (empty($_SESSION["serverwants"])) {
        echo '<script>
            window.location.replace("/dashboard.php");
          </script>';
    }
    if (isset($_SESSION["serverwants"])) {
        echo '<script>
            window.location.replace("/viewguild.php/?id='.$_SESSION["serverwants"].'");
          </script>';
    }

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