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
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index($name = 'Khanh')
    {
        return $this->view('home.index', [
            'js' => ['test.js'],
            'name' => $name
        ]);
    }
    public function edit($id){
        return $this->view('home.index', [
            'name' => $id
        ]);
    }
    public function store(){
        return Response::create("You can only get here by using POST method!");
    }
}