<?php
require "../vendor/autoload.php";
use Symfony\Component\HttpFoundation\Request;
use LoveSimple\App;


$request = Request::createFromGlobals();

$app = new App;
$response = $app->run($request);
$response->send();