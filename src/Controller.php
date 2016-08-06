<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 22:54
 */
namespace LoveSimple;
use Symfony\Component\HttpFoundation\Response;

class Controller{
    use DIContainer;
    protected $twig;
    public function __construct()
    {
        $this->twig = $this->container('twig');
    }
    public function view($page, $data = []){

        if (isset($data['js']) && count($data['js']) > 0)
        {
            foreach ($data['js'] as $js){
                $data['assets']['js'][] = $js;
            }
        }

        if (isset($data['css']) && count($data['css']) > 0)
        {
            foreach ($data['css'] as $css){
                $data['assets']['css'][] = $css;
            }
        }

        $page = str_replace('.',DIRECTORY_SEPARATOR,$page).'.html';
        $data['data'] = $data;
        $data['page'] = $page;
        $data['url'] = config('url');

        $content = $this->twig->render('layout.html', $data);
        return Response::create($content);
    }
}