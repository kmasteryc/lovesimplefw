<?php
namespace LoveSimple\Controllers;

use LoveSimple\Controller;
use LoveSimple\Models\Cate;

class HomeController extends Controller
{
    public function index()
    {
        $cates = Cate::with('articles')->get();

        $this->bench->collection->start();
        $cates = $cates->filter(function ($cate) {
            return $cate->articles->count() != 0;
        })
            ->map(function ($cate) {
                $cate->articles = $cate->articles->slice(0, 5);
                return $cate;
            });
//        dd($cates[2]->articles);
        $this->bench->collection->stop();
        return $this->view('home.index', compact('cates'));
    }
}