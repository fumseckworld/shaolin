<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 14/09/2018
 * Time: 15:03
 */

namespace tests;


use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Tables\Table;
use Testing\DatabaseTest;

class TableTest extends DatabaseTest
{


    /**
     * @throws \Exception
     */
    public function test_primary_key()
    {
        $expected = 'id';
        $this->assertEquals($expected,$this->get_mysql()->table()->get_primary_key());
        $this->assertEquals($expected,$this->get_pgsql()->table()->get_primary_key());
        $this->assertEquals($expected,$this->get_sqlite()->table()->get_primary_key());
    }

    /**
     * @throws \Exception
     */
    public function test_show()
    {

            $table = 'country';
        $this->assertContains($table,$this->get_mysql()->table()->show());
        $this->assertContains($table,$this->get_mysql()->show_tables());

        $this->assertContains($table,$this->get_pgsql()->show_tables());
        $this->assertContains($table,$this->get_pgsql()->table()->show());

        $this->assertContains($table,$this->get_sqlite()->show_tables());
        $this->assertContains($table,$this->get_sqlite()->table()->show());


    }
    /**
     * @throws \Exception
     */
    public function test_rename()
    {
        $table = 'country';
        $new = 'alex';
        $this->assertTrue($this->get_mysql()->table()->set_current_table($table)->rename($new));
        $this->assertTrue($this->get_pgsql()->table()->set_current_table($table)->rename($new));
        $this->assertTrue($this->get_sqlite()->table()->set_current_table($table)->rename($new));

        $this->assertTrue($this->get_mysql()->table()->set_current_table($new)->rename($table));
        $this->assertTrue($this->get_pgsql()->table()->set_current_table($new)->rename($table));
        $this->assertTrue($this->get_sqlite()->table()->set_current_table($new)->rename($table));

    }

    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $table = 'country';
        $this->assertTrue($this->get_mysql()->table()->has());
        $this->assertTrue($this->get_pgsql()->table()->has());
        $this->assertTrue($this->get_sqlite()->table()->has());

        $this->assertTrue($this->get_mysql()->table()->set_current_table($table)->has_column('id'));
        $this->assertTrue($this->get_pgsql()->table()->set_current_table($table)->has_column('id'));
        $this->assertTrue($this->get_sqlite()->table()->set_current_table($table)->has_column('id'));

        $this->assertFalse($this->get_mysql()->table()->set_current_table($table)->has_column('ids'));
        $this->assertFalse($this->get_pgsql()->table()->set_current_table($table)->has_column('ids'));
        $this->assertFalse($this->get_sqlite()->table()->set_current_table($table)->has_column('ids'));
    }

    /**
     * @throws \Exception
     */
    public function test_create()
    {
        $name = 'alexandra';
        $this->assertTrue($this->get_mysql()->table()->set_current_table($name)->append_field(Table::INT,'id',true)->append_field(Table::VARCHAR,'name',false,255)->append_field(Table::DATE,'date')->create());
    }

    /**
     * @throws \Exception
     */
    public function test_append_column()
    {
        $column = 'phone';

        $table = $this->table;

        $instance = $this->get_mysql()->table()->set_current_table($table);
        $this->assertTrue($instance->append_column($column,Table::VARCHAR,255,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Table::VARCHAR,255,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));


        $instance = $this->get_pgsql()->table()->set_current_table($table);
        $this->assertTrue($instance->append_column($column,Table::CHARACTER_VARYING,255,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Table::CHARACTER_VARYING,255,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

    }

    /**
     * @throws \Exception
     */
    public function test_truncate()
    {
        $table = 'country';

        $this->assertTrue($this->get_mysql()->table()->truncate($table));
        $this->assertTrue($this->get_mysql()->table()->truncate($table));
        $this->assertTrue($this->get_mysql()->table()->truncate($table));

        $this->assertTrue($this->get_mysql()->table()->truncate($table,Eloquent::MODE_ALL_TABLES));
        $this->assertTrue($this->get_pgsql()->table()->truncate($table,Eloquent::MODE_ALL_TABLES));
        $this->assertTrue($this->get_sqlite()->table()->truncate($table,Eloquent::MODE_ALL_TABLES));

    }
}