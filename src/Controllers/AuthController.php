<?php
/**
 * Created by PhpStorm.
 * User: kmasteryc
 * Date: 09/08/2016
 * Time: 00:13
 */

namespace LoveSimple\Controllers;

use LoveSimple\Controller;
use LoveSimple\Models\User;

class AuthController extends Controller
{

	public function login()
	{
		return $this->view('auths.login');
	}

	public function doLogin()
	{
		$name = htmlentities($this->request->get('name'));
		$password = htmlentities($this->request->get('password'));
		$user = User::where('name',$name)->first();

		if(password_verify($password, $user->password) === true){
			$this->session->set('user_name', $user->name);
		};

		return $this->redirect(baseDir());
	}
}