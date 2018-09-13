<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 06/09/2018
 * Time: 14:28
 */

namespace tests;


use Exception;
use Imperium\Databases\Eloquent\Query\Query;
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
        $empty = 'the table is empty';
        $success = 'records was found';

        $this->assertTrue($this->get_mysql()->model()->truncate());
        $result = query_result($this->get_mysql()->model(),Query::SELECT,$this->get_mysql()->records(),$this->get_mysql()->columns(),$success,$empty,$empty);

        $this->assertCount(0,$this->get_mysql()->model()->all());
        $this->assertContains($empty,$result);

        $this->assertTrue($this->get_pgsql()->model()->truncate());
        $result = query_result($this->get_pgsql()->model(),Query::SELECT,$this->get_pgsql()->records(),$this->get_pgsql()->columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->get_pgsql()->model()->all());
        $this->assertContains($empty,$result);

        $this->assertTrue($this->get_sqlite()->model()->truncate());
        $result = query_result($this->get_sqlite()->model(),Query::SELECT,$this->get_sqlite()->records(),$this->get_sqlite()->columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->get_sqlite()->model()->all());
        $this->assertContains($empty,$result);
    }

    /**
     * @throws Exception
     */
    public function test_insert()
    {
        $number = 5;
        for ($i = 0; $i != $number; ++$i)
        {
            $data = [
                'id' => null,
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20),
                'date' => faker()->date()
            ];

            $this->assertTrue($this->get_mysql()->model()->insert($data,$this->table));
            $this->assertTrue($this->get_pgsql()->model()->insert($data,$this->table));
            $this->assertTrue($this->get_sqlite()->model()->insert($data,$this->table));
        }

        $this->assertCount($number,$this->get_mysql()->model()->all());
        $this->assertCount($number,$this->get_pgsql()->model()->all());
        $this->assertCount($number,$this->get_sqlite()->model()->all());
    }

    /**
     * @throws Exception
     */
    public function test_driver()
    {
        $this->assertTrue($this->get_mysql()->connect()->mysql());
        $this->assertTrue($this->get_pgsql()->connect()->postgresql());
        $this->assertTrue($this->get_sqlite()->connect()->sqlite());

        $this->assertFalse($this->get_mysql()->connect()->postgresql());
        $this->assertFalse($this->get_pgsql()->connect()->sqlite());
        $this->assertFalse($this->get_sqlite()->connect()->mysql());
    }
}