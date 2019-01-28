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
        $this->assertContains($this->base,$this->mysql()->show_databases());
        $this->assertContains($this->base,$this->mysql()->bases()->show());

        $this->assertTrue($this->mysql()->bases()->exist($this->base));
        $this->assertTrue($this->mysql()->base_exist($this->base));
        $this->assertTrue($this->postgresql()->bases()->exist($this->base));
        $this->assertTrue($this->postgresql()->base_exist($this->base));

    }


    /**
     * @throws Exception
     */
    public function test_multiples()
    {
        $this->assertTrue($this->mysql()->bases()->create_multiples('a','b','c'));
        $this->assertTrue($this->mysql()->bases()->drop_multiples('a','b','c'));

    }
    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $this->assertTrue($this->mysql()->bases()->has());
        $this->assertTrue($this->mysql()->has_bases());
    }

    /**
     * @throws \Exception
     */
    public function test_create()
    {
        $base = 'application';

        $this->assertTrue($this->mysql()->bases()->remove('alex','marion','sandra'));
        $this->assertTrue($this->mysql()->bases()->set_charset('utf8')->set_collation('utf8_general_ci')->create('alex','marion','sandra'));
        $this->assertTrue($this->mysql()->bases()->remove('alex','marion','sandra'));

        $this->assertTrue($this->mysql()->bases()->create($base));
        $this->assertTrue($this->mysql()->bases()->drop($base));

    }

    public function test_hidden()
    {
        $this->assertEquals([],$this->mysql()->bases()->hidden_bases());
        $this->assertEquals(['zen'],$this->mysql()->bases()->hidden(['zen'])->hidden_bases());

        $this->assertNotEmpty($this->mysql()->bases()->hidden_tables());
        $this->assertEmpty($this->mysql()->bases()->hidden()->hidden_bases());
    }
    /**
     * @throws \Exception
     */
    public function test_charset()
    {
        $this->assertNotEmpty($this->mysql()->collations());
        $this->assertNotEmpty($this->mysql()->charsets());

        $this->assertNotEmpty($this->mysql()->bases()->collations());
        $this->assertNotEmpty($this->mysql()->bases()->charsets());
    }
    /**
     * @throws \Exception
     */
    public  function test_dump()
    {
        $this->assertTrue(dumper($this->mysql()->connect(),true,''));
        $this->assertTrue(dumper($this->mysql()->connect(),false,$this->table));
        $this->assertTrue($this->mysql()->bases()->dump());
    }


    public function test_exec()
    {
        $this->expectException(Exception::class);

        $this->mysql()->bases()->change_collation();
        $this->mysql()->bases()->change_charset();
        $bidon = faker()->text(5);


        $this->mysql()->bases()->set_collation($bidon)->change_collation();
        $this->mysql()->bases()->set_charset($bidon)->change_charset();
    }
    /**
     * @throws \Exception
     */
    public function test_change()
    {
        $this->assertTrue($this->mysql()->bases()->set_charset('utf8')->change_charset());
        $this->assertTrue($this->mysql()->bases()->set_collation('utf8_general_ci')->change_collation());

        $this->assertTrue($this->mysql()->change_base_charset('utf8'));
        $this->assertTrue($this->mysql()->change_base_collation('utf8_general_ci'));
    }


}
