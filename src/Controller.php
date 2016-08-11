<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 22:54
 */
namespace LoveSimple;
use LoveSimple\Models\Cate;
use LoveSimple\Models\Article;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use LoveSimple\Libs\Menu;

class Controller{
    use DIContainer;
    protected $twig;
    protected $requestVars;
    protected $request;
    protected $session;

    public function __construct()
    {
        $this->twig = $this->container('twig');

        $this->request = Request::createFromGlobals();
        $this->requestVars = $this->request->request;

        $this->session = new Session;
        $this->session->start();
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
        $data['user'] = ['name'=>'admin', 'level'=>2];
        $data['url'] = config('url');
        $data['menus'] = (new Menu)->displayNavMenu(Models\Cate::get());
        $data['all_cates'] = Cate::all();
        $data['popular_articles'] = Article::orderBy('article_view','DESC')->with('cate')->take(5)->get();
        $data['new_articles'] = Article::orderBy('created_at','DESC')->with('cate')->take(10)->get();
//        echo($data['menus']);
//        exit();
        $content = $this->twig->render('layout3.html', $data);
        return Response::create($content);
    }

    public function redirect($url){
        return new RedirectResponse($url);
    }
}