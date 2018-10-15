<?php


namespace tests\table;
 
use Imperium\Imperium;
use Testing\DatabaseTest;

class TableTest extends DatabaseTest
{

    /**
     * @throws \Exception
     */
    public function test_select()
    {
        $this->assertNotEmpty($this->mysql()->tables()->select_by_id(6));
        $this->assertNotEmpty($this->postgresql()->tables()->select_by_id(6));

        $this->assertNotEmpty($this->sqlite()->tables()->select_by_id(6));

    }



    /**
     * @throws \Exception
     */
    public function test_primary_key()
    {
        $expected = 'id';
        $this->assertEquals($expected,$this->mysql()->tables()->get_primary_key());
        $this->assertEquals($expected,$this->postgresql()->tables()->get_primary_key());
        $this->assertEquals($expected,$this->sqlite()->tables()->get_primary_key());
    }

    /**
     * @throws \Exception
     */
    public function test_show()
    {

        $table = 'patients';
        $this->assertContains($table,$this->mysql()->tables()->show());
        $this->assertContains($table,$this->mysql()->show_tables());

        $this->assertContains($table,$this->postgresql()->show_tables());
        $this->assertContains($table,$this->postgresql()->tables()->show());

        $this->assertContains($table,$this->sqlite()->show_tables());
        $this->assertContains($table,$this->sqlite()->tables()->show());


    }
    /**
     * @throws \Exception
     */
    public function test_rename()
    {


        $old = 'name';
        $new = 'username';

        $this->assertTrue($this->mysql()->tables()->rename_column($old, $new));
        $this->assertTrue($this->mysql()->tables()->rename_column($new, $old));


        $this->assertTrue($this->postgresql()->tables()->rename_column($old, $new));
        $this->assertTrue($this->postgresql()->tables()->rename_column($new, $old));


        $this->assertFalse($this->sqlite()->tables()->rename_column($old, $new));
        $this->assertFalse($this->sqlite()->tables()->rename_column($new, $old));

        $this->assertTrue($this->mysql()->rename_column($old, $new));
        $this->assertTrue($this->mysql()->rename_column($new, $old));

        $this->assertTrue($this->postgresql()->rename_column($old, $new));
        $this->assertTrue($this->postgresql()->rename_column($new, $old));

        $this->assertFalse($this->sqlite()->rename_column($old, $new));
        $this->assertFalse($this->sqlite()->rename_column($new, $old));


        $table = 'patients';
        $new = 'alex';

        $this->assertTrue($this->mysql()->tables()->set_current_table($table)->rename($new));
        $this->assertTrue($this->postgresql()->tables()->set_current_table($table)->rename($new));
        $this->assertTrue($this->sqlite()->tables()->set_current_table($table)->rename($new));

        $this->assertTrue($this->mysql()->tables()->set_current_table($new)->rename($table));
        $this->assertTrue($this->postgresql()->tables()->set_current_table($new)->rename($table));
        $this->assertTrue($this->sqlite()->tables()->set_current_table($new)->rename($table));


        $this->assertTrue($this->mysql()->rename_table($table,$new));
        $this->assertTrue($this->postgresql()->rename_table($table,$new));
        $this->assertTrue($this->sqlite()->rename_table($table,$new));

        $this->assertTrue($this->mysql()->rename_table($new,$table));
        $this->assertTrue($this->postgresql()->rename_table($new,$table));
        $this->assertTrue($this->sqlite()->rename_table($new,$table));



    }


    /**
     * @throws \Exception
     */
    public function test_type()
    {
        foreach ($this->mysql()->set_current_table($this->table)->show_columns() as $column)
            $this->assertNotEmpty($this->mysql()->tables()->type($column));

        foreach ($this->postgresql()->set_current_table($this->table)->show_columns() as $column)
            $this->assertNotEmpty($this->postgresql()->tables()->type($column));

        foreach ($this->sqlite()->set_current_table($this->table)->show_columns() as $column)
            $this->assertNotEmpty($this->sqlite()->tables()->type($column));
    }

    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $table = 'patients';
        $this->assertTrue($this->mysql()->tables()->has());
        $this->assertTrue($this->postgresql()->tables()->has());
        $this->assertTrue($this->sqlite()->tables()->has());

        $this->assertTrue($this->mysql()->tables()->set_current_table($table)->has_column('id'));
        $this->assertTrue($this->postgresql()->tables()->set_current_table($table)->has_column('id'));
        $this->assertTrue($this->sqlite()->tables()->set_current_table($table)->has_column('id'));

        $this->assertFalse($this->mysql()->tables()->set_current_table($table)->has_column('ids'));
        $this->assertFalse($this->postgresql()->tables()->set_current_table($table)->has_column('ids'));
        $this->assertFalse($this->sqlite()->tables()->set_current_table($table)->has_column('ids'));
    }


    /**
     * @throws \Exception
     */
    public function test_append_column()
    {
        $column = 'phone';

        $table = $this->table;

        $instance = $this->mysql()->tables()->set_current_table($table);
        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::DATE,0,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));


        $instance = $this->postgresql()->tables()->set_current_table($table);
        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));


        $this->assertTrue($instance->append_column($column,Imperium::DATE,0,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));
        
        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));


        $instance = $this->sqlite()->tables()->set_current_table($table);
        $this->assertTrue($instance->append_column($column,Imperium::TEXT,255,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

    }


    /**
     * @throws \Exception
     */
    public function test_count()
    {

        $this->assertNotEmpty($this->mysql()->tables()->count());
        $this->assertNotEmpty($this->postgresql()->tables()->count());
        $this->assertNotEmpty($this->sqlite()->tables()->count());

        $this->assertNotEmpty($this->mysql()->tables()->count($this->second_table,Imperium::MODE_ALL_TABLES));
        $this->assertNotEmpty($this->postgresql()->tables()->count($this->second_table,Imperium::MODE_ALL_TABLES));
        $this->assertNotEmpty($this->sqlite()->tables()->count($this->second_table,Imperium::MODE_ALL_TABLES));

    }
    public function test_current()
    {
        $table = $this->table;
        $this->assertEquals($table,$this->mysql()->tables()->get_current_table());
        $this->assertEquals($table,$this->postgresql()->tables()->get_current_table());
        $this->assertEquals($table,$this->sqlite()->tables()->get_current_table());
    }

    /**
     * @throws \Exception
     */
    public function test_dump()
    {
        $this->assertTrue($this->mysql()->tables()->dump());
        $this->assertTrue($this->postgresql()->tables()->dump());
        $this->assertTrue($this->sqlite()->tables()->dump());

        $this->assertTrue($this->mysql()->tables()->dump($this->table));
        $this->assertTrue($this->postgresql()->tables()->dump($this->table));
        $this->assertTrue($this->sqlite()->tables()->dump($this->table));
    }


    /**
     * @throws \Exception
     */
    public function test_truncate()
    {
        $table = 'patients';

        $this->assertTrue($this->mysql()->tables()->truncate($table));
        $this->assertTrue($this->mysql()->tables()->truncate($table));
        $this->assertTrue($this->mysql()->tables()->truncate($table));

        $this->assertTrue($this->mysql()->tables()->truncate($table));
        $this->assertTrue($this->postgresql()->tables()->truncate($table));
        $this->assertTrue($this->sqlite()->tables()->truncate($table));

    }
}