<?php
session_start();

require_once 'vendor/autoload.php';

require 'src/Config/config.php';

function is_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$app = new \Agnes\App;
$app->run();

?>
