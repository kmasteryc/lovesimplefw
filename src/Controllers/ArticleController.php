<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 09/08/2016
 * Time: 00:13
 */

namespace LoveSimple\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use LoveSimple\Controller;

use LoveSimple\Libs\Menu;
use LoveSimple\Models\Cate;
use LoveSimple\Models\Article;
use LoveSimple\Models\Tag;

class ArticleController extends Controller
{
    public function index($request)
    {
        $perpage = 15;
        $cur_page = $this->request->query->get('page');
        $articles = Article::with('cate')->select('article_title','id')->get();

        $paginator = new LengthAwarePaginator($articles, $articles->count(), $perpage, $cur_page);
        $paginator->setPath(baseDir("article/index"));

        $result = $paginator->getCollection()
            ->slice($cur_page * $perpage, $perpage)
            ->put('links', $paginator->links());

        return $this->view('articles.index', [
            'articles' => $result
        ]);
    }

    public function create()
    {
        //Validation and flash message
        $this->session->set('user_name', 'admin');
        $this->session->set('user_level', 2);

        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectMenu($cates);
        return $this->view('articles.create', [
            'js' => ['ckeditor/ckeditor.js'],
            'cates' => $cates_html]);
    }

    public function store()
    {
        $tag_ids = Tag::getTagIdsFromString($this->requestVars->get('tags'));

        $article = new Article;
        $article->article_title = $this->requestVars->get('article_title');
        $article->article_info = $this->requestVars->get('article_info');
        $article->article_content = $this->requestVars->get('article_content');
        $article->cate_id = $this->requestVars->get('cate_id');
        $article->save();
        $article->tags()->sync($tag_ids);

        return $this->redirect(baseDir('article/index'));
    }

    public function edit($id)
    {
        $article = Article::find($id);
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectedMenuNoHide($cates, $article->cate);
        return $this->view('articles.edit', [
            'js' => ['ckeditor/ckeditor.js'],
            'article' => $article,
            'cates' => $cates_html
        ]);
    }

    public function update($id)
    {
        $tag_ids = Tag::getTagIdsFromString($this->requestVars->get('tags'));
        $article = Article::find($id);
        $article->article_title = $this->requestVars->get('article_title');
        $article->article_info = $this->requestVars->get('article_info');
        $article->article_content = $this->requestVars->get('article_content');
        $article->cate_id = $this->requestVars->get('cate_id');
        $article->save();
        $article->tags()->sync($tag_ids);
        return $this->redirect(baseDir('article/index'));
    }

    public function show($cate_slug, $article_slug)
    {

        $article = Article::whereArticleSlug($article_slug)->first();
        $cates = Cate::whereCateSlug($cate_slug)->get();
        $title = $article->article_title;
        $breadcrumb = showBreadCrumb($article->cate);

        return $this->view('articles.show', compact('article', 'cates', 'title', 'breadcrumb'));
    }

    public function delete($id)
    {
        $article = Article::find($id);
        $article->tags()->detach();
        $article->delete();
        
        return $this->redirect(baseDir('article/index'));
    }
}