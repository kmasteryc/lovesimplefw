<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 02/08/2016
 * Time: 21:58
 */

namespace LoveSimple\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;


class ContactController
{
    protected $twig;
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    public function get(){
        $form = $this->twig->render('forms/contact.html',['title'=>'My Form']);
        $content = $this->twig->render('index.html', ['content'=>$form]);
        return Response::create($content);
    }
    public function post(Request $request){
        var_dump($request->getP);
        $form = $this->twig->render('forms/contact.html',[
            'title'=>'My Form',
            'thank'=>'Thank you for supporting us!',
            'post_data'=> 'Hi'
        ]);
        $content = $this->twig->render('index.html', ['content'=>$form]);
        return Response::create($content);
    }
}