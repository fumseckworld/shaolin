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
        $p_queries = collection();
        $s_queries = collection();

        $table = 'base';

        $m_queries->add(insert_into($this->mysql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));
        $p_queries->add(insert_into($this->postgresql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));
        $s_queries->add(insert_into($this->sqlite()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));


        $all_mysql  = $this->mysql()->model()->from($table )->count();
        $all_pgsql  = $this->mysql()->model()->from($table)->count();
        $all_sqlite = $this->sqlite()->model()->from($table)->count();

        $m = $this->mysql()->connect()->transaction();
        $p = $this->postgresql()->connect()->transaction();
        $s = $this->sqlite()->connect()->transaction();

        $m->queries($m_queries->join(','));
        $p->queries($p_queries->join(','));
        $s->queries($s_queries->join(','));

        $this->assertTrue($m->commit());
        $this->assertTrue(different($this->mysql()->model()->from($table)->count(),$all_mysql));

        $this->assertTrue($p->commit());
        $this->assertTrue(different($this->postgresql()->model()->from($table)->count(),$all_pgsql));

        $this->assertTrue($s->commit());
        $this->assertTrue(different($this->sqlite()->model()->from($table)->count(),$all_sqlite));
    }

    public function test_not()
    {
        $this->assertTrue($this->mysql()->connect()->not(Connect::SQLITE));
        $this->assertTrue($this->mysql()->connect()->not(Connect::POSTGRESQL));
        $this->assertFalse($this->mysql()->connect()->not(Connect::MYSQL));

        $this->assertTrue($this->postgresql()->connect()->not(Connect::MYSQL));
        $this->assertTrue($this->postgresql()->connect()->not(Connect::SQLITE));
        $this->assertFalse($this->postgresql()->connect()->not(Connect::POSTGRESQL));

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
        $p_queries = collection();
        $s_queries = collection();

        $table = 'base';

        $m_queries->add(insert_into($this->mysql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));
        $p_queries->add(insert_into($this->postgresql()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));
        $s_queries->add(insert_into($this->sqlite()->model(), $table,'id',faker()->name,faker()->numberBetween(1,100),faker()->randomNumber(8),faker()->firstNameFemale,faker()->text(10),faker()->date(),faker()->date()));


        $all_mysql  = $this->mysql()->model()->from($table )->count();
        $all_pgsql  = $this->mysql()->model()->from($table)->count();
        $all_sqlite = $this->sqlite()->model()->from($table)->count();

        $m = $this->mysql()->connect()->transaction();
        $p = $this->postgresql()->connect()->transaction();
        $s = $this->sqlite()->connect()->transaction();

        $m->queries($m_queries->join(','));
        $p->queries($p_queries->join(','));
        $s->queries($s_queries->join(','));

        $m->rollback();
        $this->assertTrue(equal($this->mysql()->model()->from($table)->count(),$all_mysql));

        $p->rollback();
        $this->assertTrue(equal($this->postgresql()->model()->from($table)->count(),$all_pgsql));

        $s->rollback();
        $this->assertTrue(equal($this->sqlite()->model()->from($table)->count(),$all_sqlite));
    }




}