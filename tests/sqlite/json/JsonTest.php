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
        $json->add($this->sqlite()->all($this->table,'id'),'bases');

        $this->assertTrue($json->generate());
    }

    public function test_json_encode_exception()
    {
        $this->expectException(Exception::class);
        $this->sqlite()->json()->set_name('app.json')->encode(78995);
    }

    public function test_json()
    {
        $this->assertNotEmpty($this->sqlite()->json()->set_name('app.json')->add(['a' => 1],'fantasy')->encode());

    }
    /**
     * @throws \Exception
     */
    public function test_bases_to_json()
    {

        $this->expectException(Exception::class);

        bases_to_json($this->sqlite()->bases(),'base.json','base');

    }

    /**
     * @throws \Exception
     */
    public function test_sql()
    {
        $json = json('sql.json');
        $this->assertTrue($json->sql($this->sqlite()->connect(),"SELECT * FROM $this->table")->generate());
    }

    /**
     * @throws \Exception
     */
    public function test_tables_to_json()
    {

        $this->assertTrue(tables_to_json($this->sqlite()->table(),'tables.json','tables'));

    }

    /**
     * @throws \Exception
     */
    public function test_decode()
    {
        $x = '{"users":["sqlite.session","sqlite.sys","root"],"bases":["information_schema","sqlite","performance_schema","sys","zen"]}';
        $json = json($x);
        $this->assertNotEmpty($json->decode());


    }
}