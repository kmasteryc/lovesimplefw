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

class Routes
{
    protected $routes;
    protected $path;
    protected $method;
    protected $request;
    protected $controller;

    public function __construct()
    {
//        $this->request = $request;
//        $this->getRoutesConfig();
    }

    public function getController(Request $request)
    {
        $this->request = $request;
        $this->routes = require (appDir('config/routes.php'));

        $this->path = $request->getPathInfo();
        $this->method = strtoupper($request->getMethod());

        if (in_array(strtoupper($request->get("_method")), ['PUT','PATCH','DELETE'])){
            $this->method = $request->get("_method");
        }

        $this->dispatchRoute();

        if ($this->controller != '') {
            return $this->controller;
        } else {
            return Response::create("Not found!", 404);
        }
    }

    private function dispatchRoute()
    {
        $requestURIS = explode('/', $this->request->getPathInfo());
        $realURIS_segments = [];
        foreach ($requestURIS as $URI) {
            if ($URI != '') {
                $realURIS_segments[] = $URI;
            }
        }
        $count_realURIS = count($realURIS_segments)?:0;
        $routes_in_method = $this->routes[$this->method];
        $this->removeEndSlash($routes_in_method);
        $routes = array_keys($routes_in_method);

        $load_default = true;
        foreach ($routes as $route) {
            $route_segments = explode('/', $route);
            $count_segments = count($route_segments);

            if ($count_realURIS === $count_segments) {
                $route_with_config = $routes_in_method[$route];
                $params = $this->compareUriToRoute($realURIS_segments, $route_segments, $route_with_config);
                if ($params !== false) {
                    array_push($params, $this->request);
                    $this->setController($route_with_config['page'], $params);
                    $load_default = false;
                }
            }
        }
        if ($load_default){
            $this->setController($this->routes['default']['page'], [$this->request]);
        }
    }
    private function setController($route, $param){
        $controller = $this->getControllerFromRoute($route);
        $method = $this->getMethodFromRoute($route);
        $controller_in_namespace = "\\LoveSimple\\Controllers\\".$controller;
        $this->controller = call_user_func_array([new $controller_in_namespace, $method], $param);
    }
    private function getControllerFromRoute($route){
        return explode('@', $route)[0];
    }

    private function getMethodFromRoute($route){
        return explode('@', $route)[1];
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