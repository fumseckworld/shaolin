<?php
namespace tests\base;


use Exception;
use Testing\DatabaseTest;

class BaseTest extends DatabaseTest
{

    public function setUp()
    {
        $this->table = 'base';
    }

    public function test_show()
    {
        
        $this->expectException(Exception::class);
        $this->sqlite()->show_databases();
    }


    /**
     * @throws Exception
     */
    public function test_multiples()
    {
        $this->assertTrue($this->sqlite()->bases()->create_multiples('a','b','c'));
        $this->assertTrue($this->sqlite()->bases()->drop_multiples('a','b','c'));

    }
    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $this->expectException(Exception::class);
        $this->sqlite()->bases()->has();
        $this->sqlite()->has_bases();
    }

    /**
     * @throws \Exception
     */
    public function test_create()
    {
        $base = 'application';

        $this->assertTrue($this->sqlite()->bases()->remove('alex','marion','sandra'));
        $this->assertTrue($this->sqlite()->bases()->create('alex','marion','sandra'));
        $this->assertTrue($this->sqlite()->bases()->remove('alex','marion','sandra'));

        $this->assertTrue($this->sqlite()->bases()->create($base));
        $this->assertTrue($this->sqlite()->bases()->drop($base));

    }
    /**
     * @throws \Exception
     */
    public function test_charset()
    {
        $this->expectException(Exception::class);
        $this->sqlite()->collations();
        $this->sqlite()->charsets();

    }
    /**
     * @throws \Exception
     */
    public  function test_dump()
    {
        $this->assertTrue(dumper($this->sqlite()->connect(),true,''));
        $this->assertFalse(dumper($this->sqlite()->connect(),false,current_table()));
        $this->assertTrue($this->sqlite()->bases()->dump());
    }


    public function test_exec()
    {
        $this->expectException(Exception::class);

        $this->sqlite()->bases()->change_collation();
        $this->sqlite()->bases()->change_charset();


    }
}
