<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 20:36
 */
namespace LoveSimple;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

trait DIContainer{

    public function container($name='')
    {
        $container = new ContainerBuilder();
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
        $loader->load(appDir('config/dependencies.yaml'));
        if ($name != ''){
            return $container->get($name);
        }
        return $container;
    }
}