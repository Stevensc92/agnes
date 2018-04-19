<?php
session_start();

require_once 'vendor/autoload.php';

require 'src/Config/config.php';

$app = new \Agnes\App;
$app->run();

?>
