<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 04/08/2016
 * Time: 21:17
 */


function asset($file){
    return baseDir().'assets/'.$file;
};

function baseDir(){
    $protocol = strtolower(strstr($_SERVER['SERVER_PROTOCOL'],'/',true)).'://';
    return $protocol.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR;
}
function appDir(){
    return __DIR__.DIRECTORY_SEPARATOR;
}
function dd($var){
    ddd($var);
    exit();
}
function ddd($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}