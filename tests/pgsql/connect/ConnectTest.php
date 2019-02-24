<?php

namespace Testing\pgsql\connect {


    use Imperium\Connexion\Connect;
    use Testing\DatabaseTest;

    class ConnectTest extends DatabaseTest
    {



        /**
         * @throws \Exception
         */
        public function test_transaction()
        {
            $m_queries = app()->connect();

            $table = 'base';

            $data = ['id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()];

            $all_mysql  = $this->postgresql()->model()->count($table);

            $m_queries->queries(insert_into($table,$data));



            $m = $this->postgresql()->connect()->transaction();

            $this->assertTrue($m->commit());
            $this->assertTrue(different($this->postgresql()->model()->count($table),$all_mysql));
        }

        public function test_not()
        {

            $this->assertTrue($this->postgresql()->connect()->not(SQLITE));
            $this->assertTrue($this->postgresql()->connect()->not(MYSQL));
            $this->assertFalse($this->postgresql()->connect()->not(POSTGRESQL));
            $this->assertTrue($this->postgresql()->connect()->postgresql());

        }





    }
}