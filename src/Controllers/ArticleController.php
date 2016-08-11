<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 09/08/2016
 * Time: 00:13
 */

namespace LoveSimple\Controllers;

use LoveSimple\Controller;

use LoveSimple\Libs\Menu;
use LoveSimple\Models\Cate;
use LoveSimple\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        dd(Article::with('cate')->paginate(5));
        return $this->view('articles.index', [
            'articles' => Article::with('cate')->paginate(5)
        ]);
    }

    public function create()
    {
        //Validation and flash message
        $this->session->set('user_name','admin');
        $this->session->set('user_level', 2);

        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectMenu($cates);
        return $this->view('articles.create', [
            'js' => ['ckeditor/ckeditor.js'],
            'cates' => $cates_html]);
    }

    public function store()
    {
        $article = new Article;
        $article->article_title = $this->requestVars->get('article_title');
        $article->article_info = $this->requestVars->get('article_info');
        $article->article_content = $this->requestVars->get('article_content');
        $article->cate_id = $this->requestVars->get('cate_id');

//        $cateValidator = v::attribute('cate_title', v::stringType()->length(5, null)->alpha()->space())
//            ->attribute('cate_parent', v::intType());

//        if ($cateValidator === false)
//        {
//            echo "Something wrong here!";
//        }

        $article->save();

        return $this->redirect(baseDir('article/index'));
    }
    public function edit($id){
        $article = Article::find($id);
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectedMenuNoHide($cates, $article->cate);
        return $this->view('articles.edit', [
            'js' => ['ckeditor/ckeditor.js'],
            'article' => $article,
            'cates' => $cates_html
        ]);
    }
    public function update($id){
        $article = Article::find($id);
        $article->article_title = $this->requestVars->get('article_title');
        $article->article_info = $this->requestVars->get('article_info');
        $article->article_content = $this->requestVars->get('article_content');
        $article->cate_id = $this->requestVars->get('cate_id');
        $article->save();
        return $this->redirect(baseDir('article/index'));
    }

    public function show($cate_slug, $article_slug){

        $article = Article::whereArticleSlug($article_slug)->first();
        $cates = Cate::whereCateSlug($cate_slug)->get();
        $title = $article->article_title;
        $breadcrumb = showBreadCrumb($article->cate);

        return $this->view('articles.show',compact('article','cates','title','breadcrumb'));
    }

    public function delete($id){
        Article::find($id)->delete();
        return $this->redirect(baseDir('article/index'));
    }
}