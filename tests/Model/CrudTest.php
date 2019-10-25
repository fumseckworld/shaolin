<?php

namespace Testing\Model;

use App\Controllers\AdminController;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Dump\Dump;
use Imperium\Exception\Kedavra;
use Imperium\Import\Import;
use Imperium\Testing\Unit;
use App\Models\Users;
use stdClass;

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
	 * @throws Kedavra
	 */
    public function test_show()
    {
        $this->assertTrue($this->crud->show('users')->isOk());

    }

    /**
	 * @throws Kedavra
	 */
    public function test_home()
    {
        $this->assertTrue($this->crud->home()->isOk());
        $this->assertTrue($this->crud->clear('countries')->isRedirect('/countries'));

    }
	
/**
	 * @throws Kedavra
	 */
	public function test_delete()
	{
		$this->assertTrue($this->crud->destroy('users',20)->isRedirect('/'));
		
	}

    /**
     * @throws Kedavra
     */
    public function test_create()
    {
        $this->assertTrue($this->crud->add('users')->isRedirect('/'));

    }
    public function test_generate_form()
    {
        $this->assertTrue($this->crud->create('users')->isOk());
    }

    public function test_edit()
    {
        $this->assertTrue($this->crud->edit('users',30)->isOk());
    }
    public function test_refresh()
    {
        $this->expectException(Kedavra::class);
        $this->crud->refresh('users',30);
    }
}