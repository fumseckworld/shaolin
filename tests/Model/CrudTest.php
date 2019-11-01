<?php

namespace Testing\Model;

use App\Controllers\AdminController;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;
use Imperium\Testing\Unit;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CrudTest extends Unit
{


    /**
     * @var AdminController
     */
    private $crud;

    public function setUp(): void
    {
        $this->crud = new AdminController();
    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function test_show()
    {
        $this->assertTrue($this->crud->show('users')->isOk());

    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function test_home()
    {
        $this->assertTrue($this->crud->home()->isOk());
        $this->assertTrue($this->crud->clear('countries')->isRedirect('/countries'));

    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
	public function test_delete()
	{
		$this->assertTrue($this->crud->destroy('users',20)->isRedirect('/'));
		
	}

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function test_create()
    {
        $this->assertTrue($this->crud->add('users')->isRedirect('/'));

    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function test_generate_form()
    {
        $this->assertTrue($this->crud->create('users')->isOk());
    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws LoaderError
     * @throws NotFoundException
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function test_edit()
    {
        $this->assertTrue($this->crud->edit('users',30)->isOk());
    }

    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function test_refresh()
    {
        $this->expectException(Kedavra::class);
        $this->crud->refresh('users',30);
    }
}