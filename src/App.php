<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:16
 */
namespace LoveSimple;

use Symfony\Component\HttpFoundation\Request;

class App extends DIContainer
{
    protected $route;
    public function __construct()
    {
        parent::__construct();
        $this->route = new Routes;
    }

    public function process(Request $request)
    {
        return $this->route->getController($request);
    }

}