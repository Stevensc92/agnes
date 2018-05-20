<?php
session_start();
date_default_timezone_set('Europe/Paris');

require_once 'vendor/autoload.php';

require 'src/Config/config.php';

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$app = new \Agnes\App;
$app->run();

?>
