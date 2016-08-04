<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 22:54
 */
namespace LoveSimple;
use Symfony\Component\HttpFoundation\Response;

class Controller extends DIContainer{
    protected $twig;
    public function __construct()
    {
        parent::__construct();
        $this->twig = $this->container->get('twig');
    }
    public function view($page, $data = []){

        $data['assets']['css'] = $GLOBALS['config']['css'];
        $data['assets']['js'] = $GLOBALS['config']['js'];
        if (isset($data['js']) && count($data['js']) > 0)
        {
            foreach ($data['js'] as $js){
                array_push($data['assets']['js'], asset($js));
            }
        }

        if (isset($data['css']) && count($data['css']) > 0)
        {
            foreach ($data['css'] as $css){
                array_push($data['assets']['css'], asset($css));
            }
        }

        $page = str_replace('.',DIRECTORY_SEPARATOR,$page).'.html';
        $data['data'] = $data;
        $data['page'] = $page;

        $content = $this->twig->render('layout.html', $data);
        return Response::create($content);
    }
}