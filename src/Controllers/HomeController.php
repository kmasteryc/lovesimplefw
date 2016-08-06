<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:58
 */

namespace LoveSimple\Controllers;

use LoveSimple\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIndex($name = 'You')
    {
        return $this->view('home.index', [
            'name' => $name,
            'js' => ['jquery.min.js']
        ]);
    }
    public function postIndex(Request $request){
        return $this->view('home.index', [
            'name' => $request->get('name')
        ]);
    }
}