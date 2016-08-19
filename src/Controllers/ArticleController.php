<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 09/08/2016
 * Time: 00:13
 */

namespace LoveSimple\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use LoveSimple\Controller;

use LoveSimple\Libs\Menu;
use LoveSimple\Models\Cate;
use LoveSimple\Models\Article;
use LoveSimple\Models\Tag;

class ArticleController extends Controller
{
    public function index()
    {
	    $this->is_admin();

        $perpage = 15;
        $cur_page = $this->request->query->get('page');
        $articles = Article::with('cate')->get();

        $paginator = new LengthAwarePaginator($articles, $articles->count(), $perpage, $cur_page);
        $paginator->setPath(baseDir("article/index"));

        $result = $paginator->getCollection()
            ->slice($cur_page * $perpage, $perpage);
        $links = $paginator->links();

        return $this->view('articles.index', [
            'articles' => $result,
            'links' => $links
        ]);
    }

    public function create()
    {
	    $this->is_admin();
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
	    $this->is_admin();

        $tag_ids = Tag::getTagIdsFromString($this->request->get('tags'));

        $article = new Article;
        $article->article_title = $this->request->get('article_title');
        $article->article_info = $this->request->get('article_info');
        $article->article_content = $this->request->get('article_content');
        $article->cate_id = $this->request->get('cate_id');
        $article->save();
        $article->tags()->sync($tag_ids);

        return $this->redirect(baseDir('article/index'));
    }

    public function edit($id)
    {
	    $this->is_admin();

        $this->session->set('user_name','aaaa');
        $this->session->set('user_level', 2);

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
	    $this->is_admin();

        $tag_ids = Tag::getTagIdsFromString($this->request->get('tags'));
        $article = Article::find($id);
        $article->article_title = $this->request->get('article_title');
        $article->article_info = $this->request->get('article_info');
        $article->article_content = $this->request->get('article_content');
        $article->cate_id = $this->request->get('cate_id');
        $article->save();
        $article->tags()->sync($tag_ids);
        return $this->back();
    }

    public function show($cate_slug, $article_slug)
    {
        $article = Article::whereArticleSlug($article_slug)->first();
        $cates = Cate::whereCateSlug($cate_slug)->get();
        $title = $article->article_title;
        $breadcrumb = showBreadCrumb($article->cate);

        $related_articles = Article::where('cate_id', $article->cate->id)->inRandomOrder()->take(3)->get();

        return $this->view('articles.show', compact('article', 'cates', 'title', 'breadcrumb', 'related_articles'));
    }

    public function showByTag($tag_slug){
        $perpage = 10;
        $cur_page = $this->request->query->get('page');
        $articles = Tag::whereTagSlug($tag_slug)
            ->first()
            ->articles()
            ->orderBy('created_at')
            ->with('cate')
            ->get();

        $paginator = new LengthAwarePaginator($articles, $articles->count(), $perpage, $cur_page);
        $paginator->setPath(baseDir("tag/$tag_slug"));

        $result = $paginator->getCollection()
            ->slice(($cur_page * $perpage), $perpage);
        $links = $paginator->links();

        return $this->view('articles.show_by_tag', [
            'articles' => $result,
            'tag_slug' => $tag_slug,
            'count' => $articles->count(),
            'links' => $links
        ]);
    }

    public function delete($id)
    {
	    $this->is_admin();

        $article = Article::find($id);
        $article->tags()->detach();
        $article->delete();
        
        return $this->redirect(baseDir('article/index'));
    }
}