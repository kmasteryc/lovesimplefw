<?php
namespace LoveSimple\Controllers;

use LoveSimple\Controller;
use LoveSimple\Models\Article;
use LoveSimple\Models\Cate;

class HomeController extends Controller
{
    public function index()
    {
        $cates = Cate::with('articles')->get();

        $cates = $cates->filter(function ($cate) {
            return $cate->articles->count() != 0;
        })
            ->map(function ($cate) {
                $cate->articles = $cate->articles->slice(0, 5);
                return $cate;
            });

        $slide_articles = Article::orderBy('created_at','DESC')->take(3)->with('cate')->get();

        return $this->view('home.index', [
            'cates' => $cates,
            'slide_articles' => $slide_articles,
        ]);
    }
}