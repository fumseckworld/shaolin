<?php
namespace Testing\sqlite\base {


    use Exception;
    use Testing\DatabaseTest;

    class BaseTest extends DatabaseTest
    {

        public function setUp(): void
        {

            $this->table = 'base';
        }

        public function test_show()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->show_databases();
            $this->sqlite()->bases()->show();

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
         * @throws Exception
         */
        public function test_hidden()
        {
            $this->assertEquals([],$this->sqlite()->bases()->hidden_bases());

            $this->assertNotEmpty($this->sqlite()->bases()->hidden_tables());
            $this->assertEmpty($this->sqlite()->bases()->hidden_bases());
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


        public function test_exec()
        {
            $this->expectException(Exception::class);

            $this->sqlite()->bases()->change_collation();
            $this->sqlite()->bases()->change_charset();
            $bidon = faker()->text(5);


            $this->sqlite()->bases()->set_collation($bidon)->change_collation();
            $this->sqlite()->bases()->set_charset($bidon)->change_charset();
        }
        /**
         * @throws \Exception
         */
        public function test_change()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->bases()->set_charset('UTF8')->change_charset();
            $this->sqlite()->bases()->set_collation('C')->change_collation();

        }


    }
}
