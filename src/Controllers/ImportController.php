<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 11/08/2016
 * Time: 00:21
 */
namespace LoveSimple\Controllers;

use LoveSimple\Controller;
use GuzzleHttp\Client;
use LoveSimple\Models\Article;
use LoveSimple\Models\Cate;
use LoveSimple\Libs\Menu;

class ImportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectMenu($cates);
        return $this->view('imports.create', ['cates' => $cates_html]);
    }

    public function downloadImg()
    {
        $done = 1;
        Article::get()->each(function($article) use ($done){

//            $article = Article::find(3);
            $content = $article->article_content;
            $reg = "/src=(\"|')(http:\/\/[a-zA-Z.0-9\/-]+)/";
            $count = preg_match_all($reg, $content, $matches);

            if ($count > 0) {
                $arr_replace = [];
                $imgs = $matches[2];
                foreach ($imgs as $img) {
                    if (strpos($img, config('url')) === false) {
                        $article_folder = "$article->id-$article->article_slug";
                        $dir = appDir("public/assets/img/upload/$article_folder/");
                        @mkdir($dir);
                        $img_data = file_get_contents($img);
                        $img_name = randStr() . basename($img);
                        $do_write = file_put_contents($dir . $img_name, $img_data);
                        if ($do_write !== false) {
                            $arr_replace[$img] = asset("img/upload/$article_folder/$img_name");
                        };
                        // Delete old img
                        echo $img;
                        @unlink($dir.$img);
                    }
                    $done++;
                }
                foreach ($arr_replace as $old=>$new){
                    $content = str_replace($old,$new,$content);
                }
                $article->article_content = $content;
                $article->save();
            }

        });

        echo "Download $done images!";
    }

    public function store()
    {
        $url = $this->requestVars->get('url');
        $client = new Client;
        $response = $client->get($url)->getBody();

        $list_article = $this->getListArticle($response);

        var_dump(array_slice($list_article, 1, 1));

        foreach ($list_article as $article_link) {
            $article = $response = $client->get($article_link)->getBody();

            $new_article = new Article;
            $new_article->article_title = $this->getTitle($article);
            $new_article->article_info = $this->getInfo($article);
            $new_article->article_content = $this->getContent($article);
            $new_article->cate_id = $this->requestVars->get('cate_id');

            var_dump($new_article->toArray());
            $new_article->save();
        }
    }

    private function getListArticle($content)
    {
        preg_match_all('/h3 class="title_news"><a href="(http:\/\/vnexpress.net\/tin-tuc[a-zA-Z:\/.0-9-]+)/', $content, $matches);
        if (count($matches) < 2) throw new \Exception("Regular expression not match!");
        return $matches[1];
    }

    private function getTitle($content)
    {
        preg_match('/<h1>(.*)<\/h1>/', $content, $matches);
        if (count($matches) < 2) throw new \Exception("Cant get Title!");
        return $matches[1];
    }

    private function getInfo($content)
    {
        preg_match('/short_intro.*">(.*)<\/h3>/', $content, $matches);
        if (count($matches) < 2) return "ERROR INFO!!!!!!!!!";
        return $matches[1];
    }

    private function getImg($content)
    {
        preg_match('/data-natural-width.*src="(.*)">/', $content, $matches);
        if (count($matches) < 2) {
            return "http://placehold.it/400x200";
        }
        return $matches[1];
    }

    private function getContent($content)
    {
        preg_match_all('/<p( class="Normal")?>[\s]+(.*)<\/p>/', $content, $matches);
        if (count($matches) < 2) throw new \Exception("Cant get Content!");
        $content_arr = $this->removeTag($matches[2]);
        $new_content = "<img src='" . $this->getImg($content) . "'>
                        <p>" . implode('</p><p>', $content_arr) . "</p>";
        return $new_content;
    }

    private function removeTag($matches = [])
    {
        $res = [];
        foreach ($matches as $match) {
            $res[] = preg_replace('/<[\/]?(a|span|font)[^>]*>/i', '', $match);
        }
        return $res;
    }
}