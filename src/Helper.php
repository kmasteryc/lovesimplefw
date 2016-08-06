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
function appDir($dir = ''){
    $app_dir = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
//    dd($app_dir);
    if ($dir != ''){
        $app_dir .= DIRECTORY_SEPARATOR.$dir;
    }
    return $app_dir;
}

function ddd($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}
function yaml_decode($link){
    $yaml = file_get_contents($link);
    return \Symfony\Component\Yaml\Yaml::parse($yaml);
}
function config($key=''){
    if ($key == ''){
        return yaml_decode(__DIR__."/../config/parameters.yaml")['parameters'];
    }else{
        return yaml_decode(__DIR__."/../config/parameters.yaml")['parameters'][$key];
    }
}