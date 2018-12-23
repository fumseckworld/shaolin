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

        $this->assertContains($this->base,$this->postgresql()->show_databases());
        $this->assertContains($this->base,$this->postgresql()->bases()->show());

        $this->assertNotContains($this->base,$this->mysql()->show_databases([$this->base]));
        $this->assertNotContains($this->base,$this->mysql()->bases()->hidden([$this->base])->show());

        $this->assertNotContains($this->base,$this->postgresql()->show_databases([$this->base]));
        $this->assertNotContains($this->base,$this->postgresql()->bases()->hidden([$this->base])->show());
    }


    /**
     * @throws Exception
     */
    public function test_multiples()
    {

        $this->assertTrue($this->mysql()->bases()->create_multiples('a','b','c'));
        $this->assertTrue($this->postgresql()->bases()->create_multiples('a','b','c'));
        $this->assertTrue($this->sqlite()->bases()->create_multiples('a','b','c'));

        $this->assertTrue($this->mysql()->bases()->drop_multiples('a','b','c'));
        $this->assertTrue($this->postgresql()->bases()->drop_multiples('a','b','c'));
        $this->assertTrue($this->sqlite()->bases()->drop_multiples('a','b','c'));

    }
    /**
     * @throws \Exception
     */
    public function test_has()
    {
        $this->assertTrue($this->mysql()->bases()->has());
        $this->assertTrue($this->postgresql()->bases()->has());
        $this->assertTrue($this->mysql()->has_bases());
        $this->assertTrue($this->postgresql()->has_bases());
    }

    /**
     * @throws \Exception
     */
    public function test_create()
    {
        $base = 'shaolin';

        $this->assertTrue($this->mysql()->bases()->create($base));
        $this->assertTrue($this->mysql()->bases()->drop($base));

        $this->assertTrue($this->mysql()->bases()->create($base));
        $this->assertTrue(remove_bases($this->mysql()->bases(),$base));

        $this->assertTrue($this->sqlite()->bases()->create($base));
        $this->assertTrue($this->sqlite()->bases()->drop($base));

        $this->assertTrue($this->postgresql()->bases()->create($base));
        $this->assertTrue(remove_bases($this->postgresql()->bases(),$base));

        $this->assertTrue($this->postgresql()->bases()->create($base));
        $this->assertTrue($this->postgresql()->bases()->drop($base));

        $this->assertTrue($this->sqlite()->bases()->create($base));
        $this->assertTrue(remove_bases($this->sqlite()->bases(),$base));

        $this->assertTrue($this->postgresql()->bases()->set_charset('UTF8')->set_collation('C')->create($base));
        $this->assertTrue($this->postgresql()->bases()->drop($base));

        $this->assertTrue($this->mysql()->bases()->set_charset('utf8')->set_collation('utf8_general_ci')->create($base));
        $this->assertTrue($this->mysql()->bases()->drop($base));


    }

    /**
     * @throws \Exception
     */
    public function test_charset()
    {
        $this->assertNotEmpty($this->mysql()->collations());
        $this->assertNotEmpty($this->mysql()->charsets());

        $this->assertNotEmpty($this->postgresql()->collations());
        $this->assertNotEmpty($this->postgresql()->charsets());

        $this->assertNotEmpty($this->mysql()->bases()->collations());
        $this->assertNotEmpty($this->mysql()->bases()->charsets());

        $this->assertNotEmpty($this->postgresql()->bases()->collations());
        $this->assertNotEmpty($this->postgresql()->bases()->charsets());
    }
    /**
     * @throws \Exception
     */
    public  function test_dump()
    {
        $this->assertTrue(dumper($this->mysql()->connect(),true,''));
        $this->assertTrue(dumper($this->postgresql()->connect(),true,''));
        $this->assertTrue(dumper($this->sqlite()->connect(),true,''));

        $this->assertTrue(dumper($this->mysql()->connect(),false,$this->table));
        $this->assertTrue(dumper($this->postgresql()->connect(),false,$this->table));
        $this->assertFalse(dumper($this->sqlite()->connect(),false,$this->table));
        $this->assertTrue($this->mysql()->bases()->dump());
        $this->assertTrue($this->postgresql()->bases()->dump());
        $this->assertTrue($this->sqlite()->bases()->dump());
    }



    public function test_exec()
    {
        $this->expectException(Exception::class);

        $this->mysql()->bases()->change_collation();
        $this->mysql()->bases()->change_charset();
        $this->postgresql()->bases()->change_charset();
        $this->postgresql()->bases()->change_collation();
        $bidon = faker()->text(5);


        $this->mysql()->bases()->set_collation($bidon)->change_collation();
        $this->mysql()->bases()->set_charset($bidon)->change_charset();
        $this->postgresql()->bases()->set_charset($bidon)->change_charset();
        $this->postgresql()->bases()->set_collation($bidon)->change_collation();
    }
    /**
     * @throws \Exception
     */
    public function test_change()
    {
        $this->assertTrue($this->mysql()->bases()->set_charset('utf8')->change_charset());
        $this->assertTrue($this->mysql()->bases()->set_collation('utf8_general_ci')->change_collation());

        $this->assertTrue($this->postgresql()->bases()->set_collation('C')->change_collation());
        $this->assertTrue($this->postgresql()->bases()->set_charset('UTF8')->change_charset());

        $this->assertTrue($this->mysql()->change_base_charset('utf8'));
        $this->assertTrue($this->mysql()->change_base_collation('utf8_general_ci'));

        $this->assertTrue($this->postgresql()->change_base_charset('UTF8'));
        $this->assertTrue($this->postgresql()->change_base_collation('C'));

        $this->assertTrue($this->postgresql()->bases()->set_collation('C')->change_collation());
        $this->assertTrue($this->postgresql()->bases()->set_charset('UTF8')->change_charset());
    }


}
