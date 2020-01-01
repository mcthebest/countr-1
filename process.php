<!-- Loader -->
<!DOCTYPE html><html><head><meta name="viewport" content="width=device-width, initial-scale=1"><style>.loader{border: 16px solid #f3f3f3; border-radius: 50%; border-top: 16px solid #3498db; width: 120px; height: 120px; -webkit-animation: spin 2s linear infinite; /* Safari */ animation: spin 2s linear infinite; transform: translateX(-50%) translateY(-50%); margin-left: auto; margin-right: auto}/* Safari */@-webkit-keyframes spin{0%{-webkit-transform: rotate(0deg);}100%{-webkit-transform: rotate(360deg);}}@keyframes spin{0%{transform: rotate(0deg);}100%{transform: rotate(360deg);}}</style></head><body><div class="loader"></div></body></html>
<?php
$configraw = file_get_contents('config.json');
$config = json_decode($configraw);

// Method Handling
if (isset($_GET["method"])) $METHOD = $_GET["method"];
if (empty($METHOD)) {
    echo "<script>window.history.back();</script>";
    return;
}
$METHODS = array('getserver');
if (in_array($METHOD, $METHODS) === false) {
    echo "<script>window.history.back();</script>";
    return;
}

ini_set("allow_url_fopen", 1);

if ($METHOD === "getserver") {
    // Setting Params Variables
    if (isset($_GET["id"])) $gs_id = $_GET["id"];
    if (isset($_GET["userid"])) $gs_uid = $_GET["userid"];

    // Checking for Variables
    if (empty($gs_id)) echo '<script>window.history.back();</script>';
    if (empty($gs_uid)) echo '<script>window.history.back();</script>';

    $URL = $config->api.'/'.$gs_id.'/'.$gs_uid.'?auth='.$config->authorization;

    $result = file_get_contents($URL, false);
    $response = json_decode($result);

    if ($response->error == true) {
        echo '<script>window.location.replace("https://discordapp.com/oauth2/authorize?client_id=467377486141980682&scope=bot&guild_id='.$gs_id.'&response_type=code&redirect_uri=https://countr.thefabicraft.com/return/&permissions=805317648");</script>';
    } else {
        echo '<script>window.location.replace("/viewguild.php/?id='.$gs_id.'");</script>';
    }
}