<?php


namespace App\Controllers;
use App\Models\User;
use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Http\Controller\Controller;
use Eywa\Http\Response\Response;

class HomeController extends Controller
{

    protected static string $layout = "layout.php";

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
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function home(): Response
    {

        $form = $this->form('server')->add('name', 'text','Type your name', ['placeholder' => 'type your name'])->add('username','text','your name')->add('bio','textarea','your story')->get();

        $users = User::all();
        
        return $this->view('welcome', 'welcome', 'welcome', compact('form', 'users'));
    }

    /**
     * @return Response
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function success(): Response
    {
        return $this->view('a', 'a', 'a');
    }

    /**
     * @return Response
     *
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function show_server(): Response
    {
        return $this->view('a', 'a', 'a');
    }

    /**
     * @return Response
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function not_found(): Response
    {
        return $this->view('a', 'cache', 'cache');
    }

    /**
     * @param string $name
     * @return Response
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function hello(string $name): Response
    {
        return $this->view('hello', 'name', $name, compact('name'));
    }
}