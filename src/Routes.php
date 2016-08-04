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
    protected $controller;

    public function __construct()
    {
        parent::__construct();
        $this->getRoutes();
    }

    public function getRoutes()
    {
        $this->routes = require(__DIR__ . "/Config/routes.php");
    }

    public function getController(Request $request)
    {
        $this->path = $request->getPathInfo();
        $this->method = strtolower($request->getMethod());
        $this->_request = $request;
        if ($this->dispatchRoute() === true) {
            return $this->controller;
        } else {
            return Response::create("Not found!", 404);
        }
    }

    public function dispatchRoute()
    {
        $URIS = explode('/',$this->_request->getPathInfo());

        $controller = $URIS[1];

        $bind_params = [];
        $route_match = '';

        foreach ($this->routes as $url=>$route){
            $url_segments = explode('/',$url);
            if ($controller == $url_segments[1] && count($URIS) == count($url_segments)){
                $mix = array_slice($url_segments,2);
                $i = 0;
                foreach ($mix as $param)
                {
                    if (strpos($param,'?}') === false && strpos($param,'}') === false){
                        if ($URIS[$i+2] == $param)
                        {
                            $route_match = $route;
                        }
                    }else{
                        $bind_params[$param] = $URIS[$i+2];
                    }
                    $i++;
                }
            }
        }
        if ($route_match != ''){
            $route_arg = explode('@',$route_match);
            $controller = $route_arg[0];
            $method = $route_arg[1];
            array_push($bind_params,$this->_request);
            $this->controller = call_user_func_array([$this->container->get($controller),$method],$bind_params);
            return true;
        }else{
            return false;
        }
    }
}