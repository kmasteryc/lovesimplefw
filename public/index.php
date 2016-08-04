<?php
require "../vendor/autoload.php";
require "../src/Helper.php";
$config = require "../src/Config/config.php";

if ($config['env'] == 'DEV'){
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}

use Symfony\Component\HttpFoundation\Request;
use LoveSimple\App;

$request = Request::createFromGlobals();

$app = new App;
$response = $app->process($request);
$response->send();