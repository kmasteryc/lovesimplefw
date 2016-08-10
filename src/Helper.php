<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 04/08/2016
 * Time: 21:17
 */
use LoveSimple\Models\Cate;

function asset($file)
{
    return baseDir() . 'assets/' . $file;
}

;

/**
 * @param $url
 * @return string: return base absolute URL
 */
function baseDir($url)
{
    $protocol = strtolower(strstr($_SERVER['SERVER_PROTOCOL'], '/', true)) . '://';
    return $protocol . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . $url;
}

/**
 * @param string $dir
 * @return string: return real absolute path
 */
function appDir($dir = '')
{
    $app_dir = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
//    dd($app_dir);
    if ($dir != '') {
        $app_dir .= DIRECTORY_SEPARATOR . $dir;
    }
    return $app_dir;
}

/**
 * @param $var
 * @return void: Echo varible without stop executing script
 */
function ddd($var)
{
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}

/**
 * @param $link : Link yaml file
 * @return mixed: Return decoded yaml file
 */
function yaml_decode($link)
{
    $yaml = file_get_contents($link);
    return \Symfony\Component\Yaml\Yaml::parse($yaml);
}

/**
 * @param string $key
 * @return mixed: Get specific config from config file
 */
function config($key = '')
{
    if ($key == '') {
        return yaml_decode(__DIR__ . "/../config/parameters.yaml")['parameters'];
    } else {
        return yaml_decode(__DIR__ . "/../config/parameters.yaml")['parameters'][$key];
    }
}

function showBreadCrumb(Cate $current_cate)
{
//    $html = '<div class="row">';
    $html = '<ol class="breadcrumb">';
    $html .= '<li><a href="index.html">Trang chủ</a></li>';
    if ($current_cate->cate_parent != 0){
        $parent_cate = Cate::find($current_cate->cate_parent)->first();
        $html .= "<li ><a href = '".baseDir("chuyen-muc/$parent_cate->cate_slug")."' > $parent_cate->cate_title</a ></li >";
    }
    $html .= "<li class='active'><a href = '".baseDir("chuyen-muc/$current_cate->cate_slug")."' > $current_cate->cate_title</a ></li >";

//    <li ><a href = "#" > Technology</a ></li >
//        <li class="active"> Mobile</li >
    $html .= '</ol>';
//    $html .= '</div>';
    return $html;
}
