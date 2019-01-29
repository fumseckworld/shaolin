<?php
namespace Testing\pgsql\base {


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
            $this->assertContains($this->base,$this->postgresql()->show_databases());
            $this->assertContains($this->base,$this->postgresql()->bases()->show());

            $this->assertTrue($this->postgresql()->bases()->exist($this->base));
            $this->assertTrue($this->postgresql()->base_exist($this->base));
            $this->assertTrue($this->postgresql()->bases()->exist($this->base));
            $this->assertTrue($this->postgresql()->base_exist($this->base));

        }


        /**
         * @throws Exception
         */
        public function test_multiples()
        {
            $this->assertTrue($this->postgresql()->bases()->create_multiples('a','b','c'));
            $this->assertTrue($this->postgresql()->bases()->drop_multiples('a','b','c'));

        }
        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->postgresql()->bases()->has());
            $this->assertTrue($this->postgresql()->has_bases());
        }

        /**
         * @throws \Exception
         */
        public function test_create()
        {
            $base = 'application';

            $this->assertTrue($this->postgresql()->bases()->remove('alex','marion','sandra'));
            $this->assertTrue($this->postgresql()->bases()->set_charset('UTF8')->set_collation('C')->create('alex','marion','sandra'));
            $this->assertTrue($this->postgresql()->bases()->remove('alex','marion','sandra'));

            $this->assertTrue($this->postgresql()->bases()->create($base));
            $this->assertTrue($this->postgresql()->bases()->drop($base));

        }

        public function test_hidden()
        {
            $this->assertEquals([],$this->postgresql()->bases()->hidden_bases());
            $this->assertEquals(['zen'],$this->postgresql()->bases()->hidden(['zen'])->hidden_bases());

            $this->assertNotEmpty($this->postgresql()->bases()->hidden_tables());
            $this->assertEmpty($this->postgresql()->bases()->hidden()->hidden_bases());
        }
        /**
         * @throws \Exception
         */
        public function test_charset()
        {
            $this->assertNotEmpty($this->postgresql()->collations());
            $this->assertNotEmpty($this->postgresql()->charsets());

            $this->assertNotEmpty($this->postgresql()->bases()->collations());
            $this->assertNotEmpty($this->postgresql()->bases()->charsets());
        }
        /**
         * @throws \Exception
         */
        public  function test_dump()
        {
            $this->assertTrue(dumper($this->postgresql()->connect(),true,''));
            $this->assertTrue(dumper($this->postgresql()->connect(),false,$this->table));
            $this->assertTrue($this->postgresql()->bases()->dump());
        }


        public function test_exec()
        {
            $this->expectException(Exception::class);

            $this->postgresql()->bases()->change_collation();
            $this->postgresql()->bases()->change_charset();
            $bidon = faker()->text(5);


            $this->postgresql()->bases()->set_collation($bidon)->change_collation();
            $this->postgresql()->bases()->set_charset($bidon)->change_charset();
        }
        /**
         * @throws \Exception
         */
        public function test_change()
        {
            $this->assertTrue($this->postgresql()->bases()->set_charset('UTF8')->change_charset());
            $this->assertTrue($this->postgresql()->bases()->set_collation('C')->change_collation());

            $this->assertTrue($this->postgresql()->change_base_charset('UTF8'));
            $this->assertTrue($this->postgresql()->change_base_collation('C'));
        }


    }
}
