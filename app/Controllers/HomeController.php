<?php


namespace App\Controllers;


use Eywa\Exception\Kedavra;
use Eywa\Http\Controller\Controller;
use Eywa\Http\Response\Response;

class HomeController extends Controller
{

    public function before_action()
    {

    }

    public function after_action()
    {

    }

    /**
     * @return Response
     * @throws Kedavra
     */
    public function home(): Response
    {
       return $this->view('cache','cache','cache');
    }

    /**
     * @return Response
     *
     * @throws Kedavra
     *
     */
    public function not_found(): Response
    {
       return $this->view('a','cache','cache');
    }

    /**
     * @param string $name
     * @return Response
     * @throws Kedavra
     */
    public function hello(string $name): Response
    {
        return $this->view('hello','name',$name,compact('name'));
    }
}