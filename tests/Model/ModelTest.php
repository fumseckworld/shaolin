<?php

namespace Testing\Model;

use Imperium\Model\Model;
use Imperium\Testing\Unit;
use Imperium\Exception\Kedavra;

class ModelTest extends Unit
{
    /**
     * @var Model
     */
    private $model;

    public function setup(): void
    {
        $this->model = app()->model()->from('users');
    }

    public function test_count()
    {
        $this->assertEquals(100,$this->model->count());
        $this->assertFalse($this->model->empty('users'));
    }

    public function test_all()
    {
        $this->assertNotEmpty($this->model->all());
    }
    public function test_dump()
    {
        
        $this->assertTrue($this->model->dump());
        $this->assertTrue($this->model->dump_base());
        $this->assertTrue($this->model->dump('users'));
    }

    public function test_primary()
    {
        $this->assertEquals('id',$this->model->primary());
    }
    public function test_find()
    {
        $this->assertNotEmpty($this->model->find(1));
        $this->assertNotEmpty($this->model->find_or_fail(1));
        $this->assertNotEmpty($this->model->by('id',10));
        $this->assertNotEmpty($this->model->by_or_fail('id',10));
        $this->assertNotEmpty($this->model->where('id',DIFFERENT,5));
        $this->assertNotEmpty($this->model->find(10));
        $this->assertNotEmpty($this->model->find_or_fail(10));
        $this->assertNotEmpty($this->model->last('id',5));
        $this->assertNotEmpty($this->model->last('id',50));
        $this->assertNotEmpty($this->model->news('id',5));
        $this->assertNotEmpty($this->model->news('id',10));
    }



    public function test_only()
    {
        $this->assertNotContains('id',$this->model->only('lastname')->get());
    }
    public function test_remove()
    {
        $this->assertTrue($this->model->remove(10));
        $this->expectException(Kedavra::class);
        $this->model->by_or_fail('id',10);
        $this->model->find_or_fail(10);
    }

    public function test_truncate()
    {
        $this->assertTrue($this->model->truncate('users'));

        $this->assertEmpty($this->model->all());
        $this->assertTrue($this->model->empty('users'));
    }

    public function test_save()
    {

        foreach($this->model->columns() as $column)
            $this->model->set($column,$column);

        $this->assertTrue($this->model->save());
        $this->assertCount(1,$this->model->all());
    }
}