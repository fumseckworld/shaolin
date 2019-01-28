<?php

namespace Testing\connect;


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

        $m_queries->add(insert_into($this->sqlite()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));

        $all_sqlite  = $this->sqlite()->model()->count($table);

        $m = $this->sqlite()->connect()->transaction();

        $m->queries($m_queries->join(','));

        $this->assertTrue($m->commit());
        $this->assertTrue(different($this->sqlite()->model()->count($table),$all_sqlite));
    }

    public function test_not()
    {
        $this->assertTrue($this->sqlite()->connect()->not(Connect::MYSQL));
        $this->assertTrue($this->sqlite()->connect()->not(Connect::POSTGRESQL));
        $this->assertFalse($this->sqlite()->connect()->not(Connect::SQLITE));

    }
    /**
     * @throws \Exception
     */
    public function test_rollback()
    {
        $m_queries = collection();

        $table = 'base';

        $m_queries->add(insert_into($this->sqlite()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));


        $all_sqlite  = $this->sqlite()->model()->from($table )->count($table);

        $m = $this->sqlite()->connect()->transaction();

        $m->queries($m_queries->join(','));

        $m->rollback();
        $this->assertTrue(equal($this->sqlite()->model()->count($table),$all_sqlite));

    }




}