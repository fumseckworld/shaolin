<?php

namespace tests\imperium;


use Imperium\Imperium;
use Testing\DatabaseTest;

class ImperiumTest extends DatabaseTest
{

    /**
     * @throws \Exception
     */
    public function test_show_users()
    {
        $this->assertNotEmpty($this->mysql()->show_users());
        $this->assertNotEmpty($this->postgresql()->show_users());

        $this->assertContains('root',$this->mysql()->show_users());
        $this->assertContains('postgres',$this->postgresql()->show_users());
        $this->assertTrue($this->mysql()->user_exist('root'));

        $this->assertTrue($this->postgresql()->user_exist('postgres'));

        $this->assertNotContains('root',$this->mysql()->show_users(['root']));
        $this->assertNotContains('postgres',$this->postgresql()->show_users(['postgres']));
    }

    /**
     * @throws \Exception
     */
    public function test_drop()
    {
        $current_table_name = 'luxoria';

        $this->assertTrue($this->mysql()->tables()->set_current_table($current_table_name)->append_field(Imperium::INT,'id',true)->create());
        $this->assertTrue($this->postgresql()->tables()->set_current_table($current_table_name)->append_field(Imperium::SERIAL,'id',true)->create());
        $this->assertTrue($this->sqlite()->tables()->set_current_table($current_table_name)->append_field(Imperium::INTEGER,'id',true)->create());

        $this->assertTrue($this->mysql()->remove_table($current_table_name));
        $this->assertTrue($this->postgresql()->remove_table($current_table_name));
        $this->assertTrue($this->sqlite()->remove_table($current_table_name));

    }


    /**
     * @throws \Exception
     */
    public function test_exist()
    {
        $this->assertTrue($this->mysql()->table_exist($this->table));

        $this->assertTrue($this->postgresql()->table_exist($this->table));

        $this->assertTrue($this->sqlite()->table_exist($this->second_table));

    }

    /**
     * @throws \Exception
     */
    public function test_show_columns()
    {
        $this->assertNotEmpty($this->mysql()->tables()->set_current_table($this->table)->get_columns_types());
        $this->assertNotEmpty($this->postgresql()->tables()->set_current_table($this->table)->get_columns_types());
        $this->assertNotEmpty($this->sqlite()->tables()->set_current_table($this->table)->get_columns_types());

        $this->assertNotEmpty($this->mysql()->show_columns());
        $this->assertNotEmpty($this->postgresql()->show_columns());
        $this->assertNotEmpty($this->sqlite()->show_columns());
    }

    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $this->assertTrue($this->mysql()->has_column('id'));
        $this->assertTrue($this->postgresql()->has_column('name'));
        $this->assertTrue($this->sqlite()->has_column('name'));

        $this->assertFalse($this->mysql()->has_column('c'));
        $this->assertFalse($this->postgresql()->has_column('c'));
        $this->assertFalse($this->sqlite()->has_column('c'));

        $this->mysql()->users()->hidden([]);
        $this->postgresql()->users()->hidden([]);
        
        $this->assertTrue($this->mysql()->has_users());
        $this->assertTrue($this->postgresql()->has_users());

        $this->assertTrue($this->mysql()->has_tables());
        $this->assertTrue($this->postgresql()->has_tables());
        $this->assertTrue($this->sqlite()->has_tables());

        $this->assertTrue($this->mysql()->has_bases());
        $this->assertTrue($this->postgresql()->has_bases());

        $this->expectException(\Exception::class);
        $this->sqlite()->has_bases();
        $this->sqlite()->has_users();
    }


}