<?php


namespace Testing;


use Imperium\Controller\Controller;

class Admin extends Controller
{
    /**
    * @return string
    * @throws \Twig_Error_Loader
    * @throws \Twig_Error_Runtime
    * @throws \Twig_Error_Syntax
    * @throws \Exception
    */
    public function login()
    {
        $form = login('/login',id(),'username','password','login','a');

        return $this->view('login',compact('form'));
    }
    public function home()
    {
        return $this->view('admin');
    }


}