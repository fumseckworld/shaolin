<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 06/09/2018
 * Time: 14:28
 */

namespace tests;


use Exception;
use Testing\DatabaseTest;

class ModelTest extends DatabaseTest
{
    /**
     * @throws \Exception
     */
    public function test_show_tables()
    {
        $this->assertContains($this->table,$this->get_mysql()->model()->show_tables());
        $this->assertContains($this->table,$this->get_pgsql()->model()->show_tables());
        $this->assertContains($this->table,$this->get_sqlite()->model()->show_tables());

        $this->assertContains($this->table,$this->get_mysql()->show_tables());
        $this->assertContains($this->table,$this->get_pgsql()->show_tables());
        $this->assertContains($this->table,$this->get_sqlite()->show_tables());


        $this->assertNotContains($this->table,$this->get_mysql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->get_pgsql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->get_sqlite()->show_tables([$this->table]));

    }

    /**
     * @throws \Exception
     */
    public function test_find()
    {

        $this->assertCount(1,$this->get_mysql()->find(10));
        $this->assertCount(1,$this->get_pgsql()->find(10));
        $this->assertCount(1,$this->get_sqlite()->find(10));

    }

    /**
     * @throws \Exception
     */
    public function test_find_or_fail()
    {

        $this->assertCount(1,$this->get_mysql()->findOrFail(10));
        $this->assertCount(1,$this->get_pgsql()->findOrFail(10));
        $this->assertCount(1,$this->get_sqlite()->findOrFail(10));

        $this->expectException(Exception::class);
        $this->get_mysql()->findOrFail(800);
        $this->get_pgsql()->findOrFail(800);
        $this->get_sqlite()->findOrFail(800);

    }

    /**
     * @throws Exception
     */
    public function test_remove()
    {
        $this->assertTrue($this->get_mysql()->remove(4));
        $this->assertTrue($this->get_pgsql()->remove(4));
        $this->assertTrue($this->get_sqlite()->remove(4));
    }

    /**
     * @throws Exception
     */
    public function test_truncate()
    {
        $this->assertTrue($this->get_mysql()->model()->truncate());
        $this->assertCount(0,$this->get_mysql()->model()->all());
        $this->assertTrue($this->get_pgsql()->model()->truncate());
        $this->assertCount(0,$this->get_pgsql()->model()->all());
        $this->assertTrue($this->get_sqlite()->model()->truncate());
        $this->assertCount(0,$this->get_sqlite()->model()->all());
    }
}