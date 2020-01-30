<?php


namespace App\Controllers;


use App\Models\User;
use Eywa\Exception\Kedavra;
use Eywa\Http\Controller\Controller;
use Eywa\Http\Response\Response;

class HomeController extends Controller
{

    protected static string $layout = "layout.php" ;

    protected static string $directory = "home";

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
        $form = $this->form('hello',GET,[],['name'=> 'marc'])->add('name','textarea',['placeholder'=> 'type your name'])->get();

        $users = User::all();

       return $this->view('welcome','welcome','welcome',compact('form','users'));
    }

    /**
     * @return Response
     *
     * @throws Kedavra
     *
     */
    public function success(): Response
    {
        return  $this->view('a','a','a');
    }
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