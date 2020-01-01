<?php
$config = json_decode(file_get_contents('config.json'));

session_start();

if ($_SESSION["state"] !== true) header('Location: /');