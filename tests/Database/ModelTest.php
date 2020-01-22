<?php


namespace Testing\Database;


use App\Model\User;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function test_all()
    {
        $this->assertCount(100, User::all());
        $this->assertEquals(100, User::sum());
        $this->assertNotEmpty(User::all());
    }

    public function test_columns()
    {
        $this->assertContains('id', User::columns());
        $this->assertNotEmpty(User::columns());
    }

    public function test_between()
    {
        $this->assertCount(11, User::between(10, 20, 'id')->execute());
    }

    public function test_get()
    {
        $this->assertCount(1, User::get(1));
        $this->assertCount(1, User::get(10));
        $this->assertNotEmpty(User::get(10));

        $this->assertCount(1, User::find(1));
        $this->assertCount(1, User::find(10));
        $this->assertNotEmpty(User::find(10));

    }

    public function test_paginate()
    {
        $x = User::paginate(function ($x){return $x->id;},1);
        $this->assertNotEmpty($x->pagination());
        $this->assertNotEmpty($x->content());
    }
    public function test_different()
    {
        $this->assertNotEquals(User::different(10, 'id')->execute(), User::different(11, 'id')->execute());
    }

    public function test_search()
    {
        $this->assertNotEmpty(User::search('a'));
    }
}