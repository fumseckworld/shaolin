<?php


namespace tests\table;

use Imperium\Imperium;
use Imperium\Tables\Table;
use Testing\DatabaseTest;

class TableTest extends DatabaseTest
{

    /**
     * @var Table
     */
    private $mysql_table;

    /**
     * @var Table
     */
    private $pgsql_table;

    /**
     * @var Table
     */
    private $sqlite_table;

    public function setUp()
    {
        $this->table = 'tbl';
        $this->mysql_table = $this->mysql()->tables()->from($this->table);
        $this->pgsql_table = $this->postgresql()->tables()->from($this->table);
        $this->sqlite_table = $this->sqlite()->tables()->from($this->table);
    }

    public function test_columns_info()
    {
        $this->assertNotEmpty($this->mysql_table->get_columns_info());
        $this->assertNotEmpty($this->pgsql_table->get_columns_info());
        $this->assertNotEmpty($this->sqlite_table->get_columns_info());
    }


    /**
     * @throws \Exception
     */
    public function test_seed()
    {
        $this->assertTrue($this->mysql_table->seed(50));
        $this->assertTrue($this->pgsql_table->seed(50));
        $this->assertTrue($this->sqlite_table->seed(50));
    }

    /**
     * @throws \Exception
     */
    public function test_insert_multiples()
    {
        $data= [];

        $number = 100;
        for ($i = 0; $i != $number ; $i++)
        {
            $data[] = [
                'id' => null ,
                'name' => faker()->name,
                'age' => faker()->numberBetween(1,100),
                'phone' => faker()->randomNumber(8),
                'sex' => faker()->firstNameMale,
                'status' => faker()->text(20),
                'days' => faker()->date(),
                'date' => faker()->date(),
            ];
        }

        $this->assertTrue($this->mysql_table->insert_multiples($data));
        $this->assertTrue($this->pgsql_table->insert_multiples($data));
        $this->assertTrue($this->sqlite_table->insert_multiples($data));
    }
    /**
     * @throws \Exception
     */
    public function test_select()
    {

        $this->assertNotEmpty($this->mysql_table->select(6));
        $this->assertNotEmpty($this->pgsql_table->select(6));
        $this->assertNotEmpty($this->sqlite_table->select(6));

    }


    /**
     * @throws \Exception
     */
    public function test_change()
    {
        $this->assertTrue($this->mysql_table->set_charset('utf8')->change_charset());
        $this->assertTrue($this->mysql_table->set_collation('utf8_general_ci')->change_collation());

        $this->assertTrue($this->pgsql_table->set_charset('utf8')->change_charset());
        $this->assertTrue($this->pgsql_table->set_collation('en_US.utf8')->change_collation());

        $this->assertFalse($this->sqlite_table->set_charset('utf8')->change_charset());
        $this->assertFalse($this->sqlite_table->set_collation('utf8_general_ci')->change_collation());


        $this->assertTrue($this->mysql_table->convert('utf8','utf8_general_ci'));
        $this->assertTrue($this->pgsql_table->convert('UTF8','en_US.utf8'));
    }

    /**
     * @throws \Exception
     */
    public function test_primary_key()
    {
        $expected = 'id';
        $this->assertEquals($expected,$this->mysql_table->primary_key());
        $this->assertEquals($expected,$this->pgsql_table->primary_key());
        $this->assertEquals($expected,$this->sqlite_table->primary_key());
    }

    /**
     * @throws \Exception
     */
    public function test_show()
    {


        $this->assertContains($this->table,$this->mysql_table->show());
        $this->assertContains($this->table,$this->mysql()->show_tables());

        $this->assertContains($this->table,$this->pgsql_table->show());
        $this->assertContains($this->table,$this->postgresql()->show_tables());

        $this->assertContains($this->table,$this->sqlite_table->show());
        $this->assertContains($this->table,$this->sqlite()->show_tables());


    }

    /**
     * @throws \Exception
     */
    public function test_has_columns_type()
    {

        $this->assertTrue($this->mysql_table->has_types('datetime'));
        $this->assertTrue($this->pgsql_table->has_types('character varying'));
        $this->assertTrue($this->sqlite_table->has_types('DATETIME','INTEGER'));

        $this->assertFalse($this->mysql_table->has_types('integer'));
        $this->assertFalse($this->pgsql_table->has_types('text'));
        $this->assertFalse($this->sqlite_table->has_types('character varying'));
    }
    /**
     * @throws \Exception
     */
    public function test_get_tpm_name()
    {
        $this->assertNotEmpty($this->mysql_table->get_current_tmp_table());
        $this->assertNotEmpty($this->pgsql_table->get_current_tmp_table());
        $this->assertNotEmpty($this->sqlite_table->get_current_tmp_table());
    }
    /**
     * @throws \Exception
     */
    public function test_rename()
    {


        $old = 'name';
        $new = 'username';

        $this->assertTrue($this->mysql_table->rename_column($old, $new));
        $this->assertTrue($this->mysql_table->rename_column($new, $old));


        $this->assertTrue($this->pgsql_table->rename_column($old, $new));
        $this->assertTrue($this->pgsql_table->rename_column($new, $old));


        $this->assertTrue($this->sqlite_table->rename_column($old, $new));
        $this->assertTrue($this->sqlite_table->rename_column($new, $old));

        $this->assertTrue($this->mysql()->rename_column($old, $new));
        $this->assertTrue($this->mysql()->rename_column($new, $old));

        $this->assertTrue($this->postgresql()->rename_column($old, $new));
        $this->assertTrue($this->postgresql()->rename_column($new, $old));

        $this->assertTrue($this->sqlite()->rename_column($old, $new));
        $this->assertTrue($this->sqlite()->rename_column($new, $old));


        $new = 'alex';

        $this->assertTrue($this->mysql_table->rename($new));
        $this->assertTrue($this->pgsql_table->rename($new));
        $this->assertTrue($this->sqlite_table->rename($new));

        $this->assertTrue($this->mysql_table->rename($this->table));
        $this->assertTrue($this->pgsql_table->rename($this->table));
        $this->assertTrue($this->sqlite_table->rename($this->table));


        $this->assertTrue($this->mysql()->rename_table($this->table,$new));
        $this->assertTrue($this->postgresql()->rename_table($this->table,$new));
        $this->assertTrue($this->sqlite()->rename_table($this->table,$new));

        $this->assertTrue($this->mysql()->rename_table($new,$this->table));
        $this->assertTrue($this->postgresql()->rename_table($new,$this->table));
        $this->assertTrue($this->sqlite()->rename_table($new,$this->table));



    }


    /**
     * @throws \Exception
     */
    public function test_type()
    {
        foreach ($this->mysql_table->columns() as $column)
            $this->assertNotEmpty($this->mysql_table->type($column));

        foreach ($this->pgsql_table->columns() as $column)
            $this->assertNotEmpty($this->pgsql_table->type($column));

        foreach ($this->sqlite_table->columns() as $column)
            $this->assertNotEmpty($this->sqlite_table->type($column));
    }

    /**
     * @throws \Exception
     */
    public function test_has()
    {

        $this->assertTrue($this->mysql_table->has());
        $this->assertTrue($this->pgsql_table->has());
        $this->assertTrue($this->sqlite_table->has());

        $this->assertTrue($this->mysql_table->has_column('id'));
        $this->assertTrue($this->pgsql_table->has_column('id'));
        $this->assertTrue($this->sqlite_table->has_column('id'));

        $this->assertFalse($this->mysql_table->has_column('ids'));
        $this->assertFalse($this->pgsql_table->has_column('ids'));
        $this->assertFalse($this->sqlite_table->has_column('ids'));
    }


    /**
     * @throws \Exception
     */

    public function test_remove_by_id()
    {
        $this->assertTrue($this->mysql_table->remove(20));
        $this->assertTrue($this->pgsql_table->remove(20));
        $this->assertTrue($this->sqlite_table->remove(20));
    }
    /**
     * @throws \Exception
     */
    public function test_the_last_field()
    {

        $columns = $this->mysql_table->columns();
        $end = end($columns);
        $this->assertFalse($this->mysql_table->is_the_last_field('id',$this->mysql_table->columns()));
        $this->assertFalse($this->mysql_table->is_the_last_field('name',$this->mysql_table->columns()));

        $this->assertFalse($this->pgsql_table->is_the_last_field('id',$this->mysql_table->columns()));
        $this->assertFalse($this->pgsql_table->is_the_last_field('name',$this->mysql_table->columns()));

        $this->assertFalse($this->sqlite_table->is_the_last_field('id',$this->mysql_table->columns()));
        $this->assertFalse($this->sqlite_table->is_the_last_field('name',$this->mysql_table->columns()));

        $this->assertTrue($this->mysql_table->is_the_last_field($end,$this->mysql_table->columns()));
        $this->assertTrue($this->pgsql_table->is_the_last_field($end,$this->mysql_table->columns()));
        $this->assertTrue($this->sqlite_table->is_the_last_field($end,$this->mysql_table->columns()));


    }

    /**
     * @throws \Exception
     */
    public function test_truncate()
    {
        $this->assertTrue($this->mysql_table->truncate($this->table));
        $this->assertTrue($this->pgsql_table->truncate($this->table));
        $this->assertTrue($this->sqlite_table->truncate($this->table));

        $this->assertTrue($this->mysql()->empty_table($this->table));
        $this->assertTrue($this->postgresql()->empty_table($this->table));
        $this->assertTrue($this->sqlite()->empty_table($this->table));

    }
    /**
     * @throws \Exception
     */
    public function test_append_column()
    {
        $column = 'moria';

        $instance = $this->mysql_table;


        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,false,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,false,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,true,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::VARCHAR,255,true,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));


        $instance = $this->pgsql_table;

        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,false,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,false,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,true,false));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

        $this->assertTrue($instance->append_column($column,Imperium::CHARACTER_VARYING,255,true,true));
        $this->assertTrue($instance->has_column($column));
        $this->assertTrue($instance->remove_column($column));

    }


    /**
     * @throws \Exception
     */
    public function test_ignore()
    {
         $this->assertNotContains($this->table,$this->mysql()->tables()->hidden([$this->table])->show());
         $this->assertNotContains($this->table,$this->postgresql()->tables()->hidden([$this->table])->show());
         $this->assertNotContains($this->table,$this->sqlite()->tables()->hidden([$this->table])->show());

         $this->assertContains($this->table,$this->mysql()->tables()->hidden([])->show());
         $this->assertContains($this->table,$this->postgresql()->tables()->hidden([])->show());
         $this->assertContains($this->table,$this->sqlite()->tables()->hidden([])->show());
    }


    /**
     * @throws \Exception
     */
    public function test_columns_to_string()
    {

        $this->assertEquals('id, name, age, phone, sex, status, days, date',$this->mysql_table->columns_to_string());
        $this->assertEquals('id, name, age, phone, sex, status, days, date',$this->pgsql_table->columns_to_string());
        $this->assertEquals('id, name, age, phone, sex, status, days, date',$this->sqlite_table->columns_to_string());
    }


    /**
     * @throws \Exception
     */
    public function test_count()
    {

        $this->assertEquals(0,$this->mysql_table->count($this->table));
        $this->assertEquals(0,$this->pgsql_table->count($this->table));
        $this->assertEquals(0,$this->sqlite_table->count($this->table));

    }


    /**
     * @throws \Exception
     */
    public function test_found()
    {
        $table = 7;

        $this->assertEquals($table,$this->mysql_table->found());
        $this->assertEquals($table,$this->pgsql_table->found());
        $this->assertEquals($table,$this->sqlite_table->found());

    }


    /**
     * @throws \Exception
     */
    public function test_current()
    {

        $this->assertEquals($this->table,$this->mysql_table->get_current_table());
        $this->assertEquals($this->table,$this->pgsql_table->get_current_table());
        $this->assertEquals($this->table,$this->sqlite_table->get_current_table());
    }


    /**
     * @throws \Exception
     */
    public function test_columns_not_exist()
    {
        $this->assertTrue($this->mysql_table->column_not_exist('excalibur'));
        $this->assertTrue($this->pgsql_table->column_not_exist('excalibur'));
        $this->assertTrue($this->sqlite_table->column_not_exist('excalibur'));

        $this->assertFalse($this->mysql_table->column_not_exist('id'));
        $this->assertFalse($this->pgsql_table->column_not_exist('id'));
        $this->assertFalse($this->sqlite_table->column_not_exist('id'));
    }
    /**
     * @throws \Exception
     */
    public function test_dump()
    {

        $this->assertTrue($this->mysql_table->dump());
        $this->assertTrue($this->pgsql_table->dump());

        $this->assertTrue($this->mysql_table->dump($this->table));
        $this->assertTrue($this->pgsql_table->dump($this->table));
        $this->assertFalse($this->sqlite_table->dump($this->table));
    }


    /**
     * @throws \Exception
     */
    public function test_modify_column()
    {
        $this->assertTrue($this->mysql_table->modify_column('status',Imperium::VARCHAR,200));
        $this->assertTrue($this->pgsql_table->modify_column('status',Imperium::CHARACTER_VARYING,200));
        $this->assertFalse($this->sqlite_table->modify_column('status',Imperium::TEXT,200));
    }

}
