<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 07/08/2016
 * Time: 20:59
 */

namespace LoveSimple\Controllers;

use LoveSimple\Controller;

use LoveSimple\Libs\Menu;
use LoveSimple\Models\Cate;
use Illuminate\Pagination\LengthAwarePaginator;
class CateController extends Controller
{
    public function index()
    {
	    $this->is_admin();
        $cates = Cate::all();
        $cates_html = (new Menu)->displayMenuInTable($cates);
        return $this->view('cates.index', ['cates' => $cates_html]);
    }

    public function create()
    {
	    $this->is_admin();
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectMenu($cates);
        return $this->view('cates.create', ['cates' => $cates_html]);
    }

    public function edit($id)
    {
	    $this->is_admin();
        $cate = Cate::find($id);
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectedMenu($cates, $cate);
        return $this->view('cates.edit', [
            'cate' => $cate,
            'cates' => $cates_html
        ]);
    }

    public function store()
    {
	    $this->is_admin();
        $cate = new Cate;
        $cate->cate_title = $this->requestVars->get('cate_title');
        $cate->cate_parent = $this->requestVars->get('cate_parent');

        $cateValidator = v::attribute('cate_title', v::stringType()->length(5, null)->alpha()->space())
            ->attribute('cate_parent', v::intType());

        if ($cateValidator === false) {
            echo "Something wrong here!";
        }

        $cate->save();

        return $this->redirect(baseDir('cate/index'));
    }

    public function update($id)
    {
	    $this->is_admin();
        $cate = Cate::find($id);
        $cate->cate_title = $this->requestVars->get('cate_title');
        $cate->cate_parent = $this->requestVars->get('cate_parent');
        $cate->save();

        return $this->redirect(baseDir('cate/index'));
    }

    public function delete($id)
    {
	    $this->is_admin();
        $cate = Cate::find($id);
        $cate->delete();
        return $this->redirect(baseDir('cate/index'));
    }

    public function show($cate_slug)
    {
        $cate = Cate::whereCateSlug($cate_slug)->first();
        $breadcrumb = showBreadCrumb($cate);
        $title = $cate->cate_title;

        $cate_with_child = $cate
            ->getMeAndMyChilds()
            ->get();
        $cate_with_child = $cate_with_child->filter(function ($cate) {
            return $cate->articles->count() != 0;
        });

        $total_relative_cates = $cate_with_child->count();
        // Parent with child
        if ($total_relative_cates > 1) {
            $cate_with_child->map(function ($cate) {
                $cate->articles = $cate->articles->slice(0, 5);
                return $cate;
            });

            return $this->view('cates.show', [
                'cates' => $cate_with_child,
                'breadcrumb' => $breadcrumb,
                'title' => $title
            ]);
        } else {

            $perpage = 15;
            $cur_page = $this->request->query->get('page');

            $articles = $cate_with_child->first()->articles;

            $paginator = new LengthAwarePaginator($articles, $articles->count(), $perpage, $cur_page);
            $paginator->setPath(baseDir("chuyen-muc/$cate_slug"));

            $articles = $paginator->getCollection()
                ->slice(($cur_page-1) * $perpage, $perpage);
            $links = $paginator->links();
            
            return $this->view('cates.show_single', [
                'articles' => $articles,
                'breadcrumb' => $breadcrumb,
                'links' => $links,
                'title' => $title
            ]);
        }
    }
}