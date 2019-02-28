<?php

namespace Testing\router;

use Exception;
use Imperium\Routing\Router;
use Testing\DatabaseTest;

class RouterTest extends DatabaseTest
{

    /**
     * @throws Exception
     */
    public function test_callback()
    {
        $this->assertEquals('AdminController@login',Router::callback('login'));
    }

    /**
     * @throws Exception
     */
    public function test_admin()
    {
        $this->assertEquals('/',Router::admin('home'));
        $this->assertEquals('/query',Router::admin('query',POST));
    }

    /**
     * @throws Exception
     */
    public function test_name_not_found()
    {
        $this->expectException(Exception::class);
        name('alexandra');
        name('a',POST);
        name('b',GET);
    }

    /**
     * @throws Exception
     */
    public function test_name()
    {
        $this->assertEquals('/remove/:table/:id',name('remove'));
        $this->assertEquals('/',name('home'));
        $this->assertEquals('/login',name('login'));
        $this->assertEquals('/login',name('login',POST));

    }

}