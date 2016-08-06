<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 20:34
 */
namespace LoveSimple;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Routes extends DIContainer
{
    protected $routes;
    protected $path;
    protected $method;
    private $_request;
    protected $controller = '';

    public function __construct()
    {
        parent::__construct();
        $this->getRoutesConfig();
    }

    public function getController(Request $request)
    {
        $this->path = $request->getPathInfo();
        $this->method = strtolower($request->getMethod());
        $this->_request = $request;
        $this->dispatchRoute();

        if ($this->controller != '') {
            return $this->controller;
        } else {
            return Response::create("Not found!", 404);
        }
    }

    private function getRoutesConfig()
    {
        $this->routes = require(__DIR__ . "/Config/routes.php");
    }

    private function dispatchRoute()
    {
        $requestURIS = explode('/', $this->_request->getPathInfo());
        $realURIS_segments = [];
        foreach ($requestURIS as $URI) {
            if ($URI != '') {
                $realURIS_segments[] = $URI;
            }
        }
//        ddd($realURIS_segments);
        $count_realURIS = count($realURIS_segments);

        $routes_in_method = $this->routes[$this->_request->getMethod()];
//        dd($routes_in_method);
        $this->removeEndSlash($routes_in_method);
        $routes = array_keys($routes_in_method);



        foreach ($routes as $route) {
            echo $route;
            $route_segments = explode('/', $route);
            $count_segments = count($route_segments);
            if ($count_realURIS === $count_segments) {
//                dd($routes_in_method);
                $route_with_config = $routes_in_method[$route];
                $result = $this->compareUriToRoute($realURIS_segments, $route_segments, $route_with_config);
                if ($result !== false) {

                    $controller_method = explode('@', $route_with_config['page']);
                    $controller = $controller_method[0];
                    $method = $controller_method[1];
                    array_push($result, $this->_request);
                    $this->controller = call_user_func_array([$this->container->get($controller), $method], $result);

                }
            }
        }
    }

    private function removeEndSlash(array &$arr)
    {
        $res = [];
        foreach ($arr as $item=>$value) {
            $parts = explode('/', $item);
            if ($parts[count($parts) - 1] == '') {
                array_pop($parts);
            }
            $new_key = implode('/', $parts);
            $res[$new_key] = $value;
        }
        $arr = $res;
    }

    private function compareUriToRoute(array $URI_segments, array $route_segments, array $route_with_config)
    {
        $params = [];
        $match = true;
        foreach ($route_segments as $k => $route_segment) {
            if (strpos($route_segment, '{') || strpos($route_segment, '}')) {
                $extracted_var = str_replace(['{','}'],'',$route_segment);
                if (array_key_exists($extracted_var, $route_with_config)){
                    $pattern = $route_with_config[$extracted_var];
                    //Convert to negative pattern
                    $pattern = str_replace("[","[^",$pattern);
                    // If false pattern true => false
                    if (preg_match("/$pattern/",$URI_segments[$k]) == true){
                        $match = false;
                    }else{
                        $params[$extracted_var] = $URI_segments[$k];
                    }
                }else{
                    $params[$extracted_var] = $URI_segments[$k];
                }
            } else {
                if ($URI_segments[$k] !== $route_segment) {
                    $match = false;
                }
            }
        }
        if ($match === false) {
            return false;
        } else {
            return $params;
        }
    }

}