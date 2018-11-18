<?php

namespace tests\json;


use Exception;
use Testing\DatabaseTest;

class JsonTest extends DatabaseTest
{

    public function setUp()
    {
        $this->table = 'model';
    }

    /**
     * @throws \Exception
     */
    public function test_create()
    {
        $json = json('app.json');
        $json->add($this->mysql()->show_databases(),'bases')->add($this->mysql()->show_users(),'users');

        $this->assertTrue($json->generate());
    }

    public function test_json_encode_exception()
    {
        $this->expectException(Exception::class);
        $this->mysql()->json()->set_name('app.json')->encode(78995);
        $this->postgresql()->json()->set_name('app.json')->encode(78995);
        $this->sqlite()->json()->set_name('app.json')->encode(78995);
    }

    public function test_json()
    {
        $this->assertNotEmpty($this->mysql()->json()->set_name('app.json')->add(['a' => 1],'fantasy')->encode());
        $this->assertNotEmpty($this->postgresql()->json()->set_name('app.json')->add(['a' => 1],'fantasy')->encode());
        $this->assertNotEmpty($this->sqlite()->json()->set_name('app.json')->add(['a' => 1],'fantasy')->encode());
        
    }
    /**
     * @throws \Exception
     */
    public function test_bases_to_json()
    {

        $this->assertTrue(bases_to_json($this->mysql()->bases(),'base.json','base'));
        $this->assertTrue(bases_to_json($this->postgresql()->bases(),'base.json','base'));

        $this->assertTrue(bases_to_json($this->mysql()->bases(),'base.json'));
        $this->assertTrue(bases_to_json($this->postgresql()->bases(),'base.json'));
    }

    /**
     * @throws \Exception
     */
    public function test_sql()
    {
        $json = json('sql.json');
        $this->assertTrue($json->sql($this->mysql()->connect(),"SELECT * FROM $this->table")->generate());
    }
    /**
     * @throws \Exception
     */
    public function test_user_to_json()
    {

        $this->assertTrue(users_to_json($this->mysql()->users(),'base.json','users'));
        $this->assertTrue(users_to_json($this->postgresql()->users(),'base.json','users'));

        $this->assertTrue(users_to_json($this->mysql()->users(),'base.json'));
        $this->assertTrue(users_to_json($this->postgresql()->users(),'base.json'));
    }

    /**
     * @throws \Exception
     */
    public function test_tables_to_json()
    {

        $this->assertTrue(tables_to_json($this->mysql()->tables(),'base.json','tables'));
        $this->assertTrue(tables_to_json($this->postgresql()->tables(),'base.json','tables'));

        $this->assertTrue(tables_to_json($this->mysql()->tables(),'base.json'));
        $this->assertTrue(tables_to_json($this->postgresql()->tables(),'base.json'));
    }

    /**
     * @throws \Exception
     */
    public function test_decode()
    {
        $x = '{"users":["mysql.session","mysql.sys","root"],"bases":["information_schema","mysql","performance_schema","sys","zen"]}';
        $json = json($x);
        $this->assertNotEmpty($json->decode());

        $json = json('d.json');
        $json->add($this->mysql()->show_users(),'users')->add($this->mysql()->show_databases(),'bases')->add($this->mysql()->show_tables(),'tables')->generate();

        $this->assertNotEmpty($json->decode());

    }
}