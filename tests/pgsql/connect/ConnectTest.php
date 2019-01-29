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
            $m_queries = collection();

            $table = 'base';

            $m_queries->add(insert_into($this->postgresql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));

            $all_postgresql  = $this->postgresql()->model()->count($table);

            $m = $this->postgresql()->connect()->transaction();

            $m->queries($m_queries->join(','));

            $this->assertTrue($m->commit());
            $this->assertTrue(different($this->postgresql()->model()->count($table),$all_postgresql));
        }

        public function test_not()
        {
            $this->assertTrue($this->postgresql()->connect()->not(Connect::SQLITE));
            $this->assertTrue($this->postgresql()->connect()->not(Connect::MYSQL));
            $this->assertFalse($this->postgresql()->connect()->not(Connect::POSTGRESQL));

        }
        /**
         * @throws \Exception
         */
        public function test_rollback()
        {
            $m_queries = collection();

            $table = 'base';

            $m_queries->add(insert_into($this->postgresql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));


            $all_postgresql  = $this->postgresql()->model()->from($table )->count($table);

            $m = $this->postgresql()->connect()->transaction();

            $m->queries($m_queries->join(','));

            $m->rollback();
            $this->assertTrue(equal($this->postgresql()->model()->count($table),$all_postgresql));

        }




    }
}