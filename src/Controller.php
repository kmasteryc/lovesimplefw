<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 03/08/2016
 * Time: 22:54
 */
namespace LoveSimple;
use Hoa\Bench\Bench;
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
    protected $bench;

    public function __construct()
    {
        $this->twig = $this->container('twig');

        $this->request = Request::createFromGlobals();
        $this->requestVars = $this->request->request;
        $this->bench = new Bench;

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
        $this->bench->test->start();
        echo "WTF";
        $this->bench->test->stop();
        $this->bench->loadmenucate->start();
        $data['all_cates'] = Cate::all();
        $this->bench->loadmenucate->stop();
        $this->bench->loadpopularandnewarticle->start();
        $data['popular_articles'] = Article::orderBy('article_view','DESC')->with('cate')->take(5)->get();
        $data['new_articles'] = Article::orderBy('created_at','DESC')->with('cate')->take(10)->get();
        $this->bench->loadpopularandnewarticle->stop();
//        echo($data['menus']);
//        exit();
        $this->bench->render->start();
        $content = $this->twig->render('layout3.html', $data);
        $this->bench->render->stop();
        return Response::create($content);
    }

    public function redirect($url){
        return new RedirectResponse($url);
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        if (config('env') == 'DEV') echo($this->bench);
    }
}