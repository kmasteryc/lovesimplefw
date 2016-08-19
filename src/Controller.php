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
use LoveSimple\Models\Tag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use LoveSimple\Libs\Menu;

class Controller
{

    protected $twig;
    protected $request;
    protected $session;
    protected $bench;

    public function __construct()
    {
        // Start session
        $this->bootstrapSession();
        // Initialize Twig
        $this->bootstrapTwig();

        $this->request = Request::createFromGlobals();
        $this->bench = new Bench;
        $this->bench->startapp->start();
    }

    private function bootstrapSession()
    {
        $this->session = new Session;
        $this->session->start();
    }

    private function bootstrapTwig()
    {
        $twig_env = new \Twig_Loader_Filesystem(__DIR__ . '/Views');
        $twig_debug = new \Twig_Extension_Debug;
        $twig = new \Twig_Environment($twig_env, ['debug' => true]);
        $twig->addExtension($twig_debug);
        $this->twig = $twig;
    }

    public function view($page, $data = [])
    {
        if (isset($data['js']) && count($data['js']) > 0) {
            foreach ($data['js'] as $js) {
                $data['assets']['js'][] = $js;
            }
        }

        if (isset($data['css']) && count($data['css']) > 0) {
            foreach ($data['css'] as $css) {
                $data['assets']['css'][] = $css;
            }
        }

        $page = str_replace('.', DIRECTORY_SEPARATOR, $page) . '.html';

        // View compose. Global varibles for passing to views
        $this->bench->composer->start();
        $data['data'] = $data;
        $data['page'] = $page;
        $data['user'] = $this->session->get('user_name');
        $data['url'] = config('url');
        $data['menus'] = (new Menu)->displayNavMenu(Models\Cate::get());
        $data['all_cates'] = Cate::withCount('articles')->get();
        $data['all_tags'] = Tag::withCount('articles')->get()->sortBy('articles_count')->take(10);

        $data['popular_articles'] = Article::orderBy('article_view', 'DESC')->with('cate')->take(5)->get();
        $data['new_articles'] = Article::orderBy('created_at', 'DESC')->with('cate')->take(10)->get();

        $this->bench->composer->stop();
        $this->bench->render->start();
        $content = $this->twig->render('layout.html', $data);
        $this->bench->render->stop();
        return Response::create($content);
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    public function back(){
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function __destruct()
    {
        $this->bench->startapp->stop();
        // TODO: Implement __destruct() method.
        if (config('env') == 'DEV') echo("<p>".$this->bench."</p>");
    }

	public function is_admin(){
		if ($this->session->get('user_name') != 'admin'){
			exit();
		}
	}
}