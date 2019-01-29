<?php

namespace Testing\mysql\connect {


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

            $m_queries->add(insert_into($this->mysql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));

            $all_mysql  = $this->mysql()->model()->count($table);

            $m = $this->mysql()->connect()->transaction();

            $m->queries($m_queries->join(','));

            $this->assertTrue($m->commit());
            $this->assertTrue(different($this->mysql()->model()->count($table),$all_mysql));
        }

        public function test_not()
        {
            $this->assertTrue($this->mysql()->connect()->not(Connect::SQLITE));
            $this->assertTrue($this->mysql()->connect()->not(Connect::POSTGRESQL));
            $this->assertFalse($this->mysql()->connect()->not(Connect::MYSQL));

        }
        /**
         * @throws \Exception
         */
        public function test_rollback()
        {
            $m_queries = collection();

            $table = 'base';

            $m_queries->add(insert_into($this->mysql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));


            $all_mysql  = $this->mysql()->model()->from($table )->count($table);

            $m = $this->mysql()->connect()->transaction();

            $m->queries($m_queries->join(','));

            $m->rollback();
            $this->assertTrue(equal($this->mysql()->model()->count($table),$all_mysql));

        }




    }
}