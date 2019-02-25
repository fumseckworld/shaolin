<?php


namespace  Shaolin\Controllers;

use Imperium\Controller\Controller;
use Imperium\Request\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AdminController extends Controller
{
    /**
     * @return string
     * @throws \Exception
     */
    public function login()
    {
        $form = login('/login',id(),'username','password','login','a');

        return $this->view('login',compact('form'));
    }

    /**
     * @return RedirectResponse
     * @throws \Exception
     */
    public function logout(): RedirectResponse
    {
        return $this->auth()->logout();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function home()
    {
        return $this->view('admin');
    }

    /**
     * @return RedirectResponse
     * @throws \Exception
     */
    public function check()
    {
        $pass = Request::get('password');
        $username   = Request::get('username');

        return $this->auth()->redirect_url(name('home'))->login($username,$pass);
    }

}