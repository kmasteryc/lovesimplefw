<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:58
 */

namespace LoveSimple\Controllers;

use Symfony\Component\HttpFoundation\Request;
use LoveSimple\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index($name, Request $request)
    {
        return $this->view('home.index', [
            'js' => ['test.js'],
            'name' => $name
        ]);
    }
}