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
use Respect\Validation\Validator as v;

class CateController extends Controller
{
    public function index()
    {
        $cates = Cate::all();
        $cates_html = (new Menu)->displayMenuInTable($cates);

        return $this->view('cates.index', ['cates' => $cates_html]);
    }

    public function create()
    {
        $cates = Cate::all();
        $cates_html = (new Menu)->displaySelectMenu($cates);
        return $this->view('cates.create', ['cates' => $cates_html]);
    }

    public function edit($id)
    {
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
        $cate = new Cate;
        $cate->cate_title = $this->requestVars->get('cate_title');
        $cate->cate_parent = $this->requestVars->get('cate_parent');

        $cateValidator = v::attribute('cate_title', v::stringType()->length(5, null)->alpha()->space())
                        ->attribute('cate_parent', v::intType());

        if ($cateValidator === false)
        {
            echo "Something wrong here!";
        }

        $cate->save();

        return $this->redirect(baseDir('cate/index'));
    }

    public function update($id)
    {
        $cate = Cate::find($id);
        $cate->cate_title = $this->requestVars->get('cate_title');
        $cate->cate_parent = $this->requestVars->get('cate_parent');
        $cate->save();

        return $this->redirect(baseDir('cate/index'));
    }

    public function delete($id)
    {
        $cate = Cate::find($id);
        $cate -> delete();
        return $this->redirect(baseDir('cate/index'));
    }
}