<?php


namespace Testing\App;


use Eywa\Database\Connexion\Connect;
use Eywa\Database\Query\Sql;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;
use Eywa\Testing\Unit;

class ApplicationTest extends Unit
{
    public function test_ioc()
    {
        $this->assertInstanceOf(Connect::class,app()->ioc(Connect::class)->get());
        $this->assertInstanceOf(Response::class,app()->view('a','a','a'));
        $this->assertInstanceOf(Sql::class,app()->sql('users'));
        $this->assertInstanceOf(Request::class,app()->request());
        $this->assertNull(app()->get('a'));
        $this->assertNull(app()->post('a'));
        $this->assertNull(app()->cookie('a'));
        $this->assertNull(app()->server('a'));
        $this->assertNull(app()->file('a'));
        $this->assertNull(app()->file('a'));
    }
}