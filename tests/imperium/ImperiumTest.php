<?php

namespace tests\imperium;


use Exception;
use Imperium\Collection\Collection;
use Imperium\Html\Form\Form;
use Imperium\Imperium;
use Imperium\Users\Users;
use Testing\DatabaseTest;

class ImperiumTest extends DatabaseTest
{


    public function setUp()
    {
        $this->table = 'imperium';
    }

    /**
     * @throws \Exception
     */
    public function test_json()
    {
        $filename = 'app.json';
        $this->assertTrue($this->mysql()->json()->set_name($filename)->add($this->mysql()->show_databases(),'database')->generate());
        $this->assertTrue($this->postgresql()->json()->set_name($filename)->add($this->postgresql()->show_databases(),'database')->generate());

        $this->assertTrue($this->mysql()->bases_users_tables_to_json($filename));
        $this->assertTrue($this->postgresql()->bases_users_tables_to_json($filename));
        $this->assertTrue($this->mysql()->bases_users_tables_to_json($filename));

        $query = "SELECT * FROM $this->table";
        $this->assertTrue($this->mysql()->json()->sql($this->mysql()->connect(), $query,'records')->generate());
        $this->assertTrue($this->postgresql()->json()->sql($this->postgresql()->connect(),$query,'records')->generate());
        $this->assertTrue($this->sqlite()->json()->set_name($filename)->sql($this->sqlite()->connect(),$query,'records')->generate());


        $this->assertTrue($this->mysql()->sql_to_json($filename,$query));
        $this->assertTrue($this->postgresql()->sql_to_json($filename,$query));
        $this->assertTrue($this->sqlite()->sql_to_json($filename,$query));

        $this->assertTrue($this->mysql()->create_json($filename,$this->mysql()->show_databases()));
        $this->assertTrue($this->postgresql()->create_json($filename,$this->postgresql()->show_databases()));
        $this->assertTrue($this->sqlite()->create_json($filename,$this->sqlite()->show_tables()));

        $this->assertTrue($this->mysql()->json()->set_name($filename)->add($this->mysql()->show_databases())->generate());
        $this->assertNotEmpty($this->mysql()->json()->decode($filename));
        $this->assertNotEmpty($this->mysql()->json_decode($filename));

        $this->assertTrue($this->postgresql()->json()->set_name($filename)->add($this->postgresql()->show_databases())->generate());
        $this->assertNotEmpty($this->postgresql()->json()->decode($filename));
        $this->assertNotEmpty($this->postgresql()->json_decode($filename));

        $this->assertTrue($this->sqlite()->json()->set_name($filename)->add($this->sqlite()->show_tables())->generate());
        $this->assertNotEmpty($this->sqlite()->json()->decode($filename));
        $this->assertNotEmpty($this->sqlite()->json_decode($filename));


        $this->expectException(Exception::class);
        $this->sqlite()->bases_users_tables_to_json($filename);


    }

    public function test_get_host()
    {
        $this->assertEquals('localhost',$this->mysql()->connect()->get_host());
        $this->assertEquals('localhost',$this->postgresql()->connect()->get_host());
    }

    /**
     * @throws \Exception
     */
    public function test_remove_column()
    {
        $this->assertFalse($this->mysql()->tables()->select($this->table)->remove_column('id'));
        $this->assertFalse($this->postgresql()->remove_column('id'));
        $this->assertFalse($this->sqlite()->remove_column('id'));

        $this->assertTrue($this->mysql()->remove_column('date'));
        $this->assertTrue($this->postgresql()->remove_column('date'));
        $this->assertFalse($this->sqlite()->remove_column('date'));
    }



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

        $this->assertTrue($this->mysql()->tables()->select($current_table_name)->field(Imperium::INT,'id',true)->field(Imperium::VARCHAR,'name',false,255)->create());
        $this->assertTrue($this->postgresql()->tables()->select($current_table_name)->field(Imperium::SERIAL,'id',true)->field(Imperium::CHARACTER_VARYING,'name',false,255)->create());

        $this->assertTrue($this->sqlite()->tables()->select($current_table_name)->field(Imperium::INTEGER,'id',true)->field(Imperium::TEXT,'name',false,255)->create());

        $this->assertTrue($this->mysql()->remove_table($current_table_name));
        $this->assertTrue($this->postgresql()->remove_table($current_table_name));
        $this->assertTrue($this->sqlite()->remove_table($current_table_name));

    }

    /**
     * @throws \Exception
     */
    public function test_add_databases()
    {
        $name = 'alexandra';
        $this->assertTrue($this->mysql()->add_database($name,'utf8','utf8_general_ci'));
        $this->assertTrue($this->mysql()->remove_database($name));

        $this->assertTrue($this->postgresql()->add_database($name,'UTF-8','C'));
        $this->assertTrue($this->postgresql()->remove_database($name));

        $this->assertTrue($this->mysql()->add_database($name));
        $this->assertTrue($this->mysql()->remove_database($name));


        $this->assertTrue($this->postgresql()->add_database($name));
        $this->assertTrue($this->postgresql()->remove_database($name));
    }
    /**
     * @throws \Exception
     */
    public function test_exist()
    {
        $this->assertTrue($this->mysql()->table_exist($this->table));

        $this->assertTrue($this->postgresql()->table_exist($this->table));

        $this->assertTrue($this->sqlite()->table_exist($this->table));

    }



    /**
     * @throws \Exception
     */
    public function test_remove_user()
    {
        $name = 'marion';

        $this->assertTrue(add_user($this->mysql()->users(),$name,$name));
        $this->assertTrue($this->mysql()->remove_user($name));

        $this->assertTrue(add_user($this->postgresql()->users(),$name,$name));
        $this->assertTrue($this->postgresql()->remove_user($name));
    }

    /**
     * @throws \Exception
     */

    public function test_pass()
    {
        $this->assertTrue($this->mysql()->change_user_password(self::MYSQL_USER,self::MYSQL_PASS));
        $this->assertTrue($this->postgresql()->change_user_password(self::POSTGRESQL_USER,self::POSTGRESQL_PASS));
    }
    /**
     * @throws \Exception
     */
    public function test_show_columns()
    {
        $this->assertNotEmpty($this->mysql()->tables()->select($this->table)->get_columns_types());
        $this->assertNotEmpty($this->postgresql()->tables()->select($this->table)->get_columns_types());
        $this->assertNotEmpty($this->sqlite()->tables()->select($this->table)->get_columns_types());

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

    /**
     * @throws Exception
     */
    public function test_seed()
    {

        $this->assertTrue($this->mysql()->seed_database(50,['phinxlog']));
        $this->assertTrue($this->postgresql()->seed_database(50,['phinxlog']));
        $this->assertTrue($this->sqlite()->seed_database(50,['phinxlog','sqlite_sequence']));

    }


    /**
     * @throws Exception
     */
    public function test_add_user()
    {
        $this->assertTrue($this->mysql()->add_user('linux','linux'));
        $this->assertTrue($this->postgresql()->add_user('linux','linux'));

        $this->assertTrue($this->mysql()->remove_user('linux'));
        $this->assertTrue($this->postgresql()->remove_user('linux'));
    }

    /**
     * @throws Exception
     */
    public function test_find()
    {
        $this->assertNotEmpty($this->mysql()->find(50));
        $this->assertNotEmpty($this->mysql()->find_or_fail(50));

        $this->assertNotEmpty($this->postgresql()->find(50));
        $this->assertNotEmpty($this->postgresql()->find_or_fail(50));

        $this->assertNotEmpty($this->sqlite()->find(50));
        $this->assertNotEmpty($this->sqlite()->find_or_fail(50));
    }

    /**
     * @throws Exception
     */
    public function test_dump()
    {
        $this->assertTrue($this->mysql()->dump());
        $this->assertTrue($this->postgresql()->dump());
        $this->assertTrue($this->sqlite()->dump());

        $this->assertTrue($this->mysql()->dump(false,$this->table));
        $this->assertTrue($this->postgresql()->dump(false,$this->table));
        $this->assertTrue($this->sqlite()->dump(false,$this->table));
    }

    /**
     * @throws Exception
     */
    public function test_change()
    {
        $this->assertTrue($this->mysql()->change_base_collation("utf8_general_ci"));
        $this->assertTrue($this->postgresql()->change_base_collation("C"));

        $this->assertTrue($this->mysql()->change_table_charset($this->table,"utf8"));
        $this->assertTrue($this->postgresql()->change_table_charset($this->table,"utf8"));

        $this->assertTrue($this->mysql()->change_table_collation($this->table,"utf8_general_ci"));
        $this->assertTrue($this->postgresql()->change_table_collation($this->table,"en_US.utf8"));
    }

    public function test_collection()
    {
       $this->assertInstanceOf(Collection::class,$this->mysql()->collection());
       $this->assertInstanceOf(Collection::class,$this->postgresql()->collection());
       $this->assertInstanceOf(Collection::class,$this->sqlite()->collection());

       $this->assertEquals([],$this->mysql()->collection()->collection());
       $this->assertEquals([],$this->postgresql()->collection()->collection());
       $this->assertEquals([],$this->sqlite()->collection()->collection());

       $this->assertEquals(['3'],$this->mysql()->collection(['3'])->collection());
       $this->assertEquals(['2'],$this->postgresql()->collection(['2'])->collection());
       $this->assertEquals(['1'],$this->sqlite()->collection(['1'])->collection());
    }

    /**
     * @throws Exception
     */
    public function test_user()
    {
        $this->assertInstanceOf(Users::class,$this->mysql()->users());
        $this->assertInstanceOf(Users::class,$this->postgresql()->users());

        $this->assertNotEmpty($this->mysql()->users()->show());
        $this->assertNotEmpty($this->postgresql()->users()->show());
    }
    public function test_form()
    {
        $this->assertInstanceOf(Form::class,$this->mysql()->form());
        $this->assertInstanceOf(Form::class,$this->postgresql()->form());
        $this->assertInstanceOf(Form::class,$this->sqlite()->form());

        $this->assertEquals('</form>', $this->mysql()->form()->get());
        $this->assertEquals('</form>', $this->postgresql()->form()->get());
        $this->assertEquals('</form>', $this->sqlite()->form()->get());
    }
}