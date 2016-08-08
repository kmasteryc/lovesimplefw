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
    public function index(){
        return $this->view('articles.index',[
            'articles' => Article::all()
        ]);
    }
}