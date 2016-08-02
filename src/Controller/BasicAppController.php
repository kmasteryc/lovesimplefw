<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:42
 */
namespace LoveSimple\Controller;

use LoveSimple\Service\ConfigService;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment;

class BasicAppController{

    protected $menu;
    protected $config;
    public function __construct(Twig_Environment $twig, ConfigService $config)
    {
        $this->twig = $twig;
        $this->config = $config;
    }
    public function get($path)
    {
        $page = ($path == '/') ? 'index' : substr($path,1);
        $menu = $this->config->getConfig();
        if (isset($menu[$page]))
        {
            $content = $this->twig->render('index.html',$menu[$page]);
            return Response::create($content);
        }
        return Response::create("Not Found 404!",404);
    }
}