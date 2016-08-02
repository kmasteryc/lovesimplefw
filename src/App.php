<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:16
 */
namespace LoveSimple;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class App
{
    protected $container;

    public function __construct()
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__));
        $loader->load('services.yaml');
    }

    public function run(Request $request)
    {
        $path = $request->getPathInfo();
        $method = strtolower($request->getMethod());

        $routes = [
            '/' => 'basic_page_controller',
            '/index' => 'basic_page_controller',
            '/about' => 'basic_page_controller',
            '/contact' => 'contact_controller'
        ];

        if (isset($routes[$path]))
        {
            $controller = $this->container->get($routes[$path]);
            if(is_callable([$controller,$method])){
                return call_user_func_array([$controller,$method], [$request]);
            }
        }

        return Response::create("Not found!", 404);
    }

}