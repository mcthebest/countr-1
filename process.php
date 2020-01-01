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

