<?php


namespace App\Controllers;


use Eywa\Http\Controller\Controller;

class HomeController extends Controller
{

    public function home()
    {
       return $this->view('cache','cache','cache');
    }

    public function not_found()
    {
       return $this->view('a','cache','cache');
    }
}