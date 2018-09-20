<?php

namespace tests;

 
use Imperium\Query\Query;
use Testing\DatabaseTest;


class QueryTest extends DatabaseTest 
{

    /**
     * @throws \Exception
     */
    public function test_where()
    {
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->where('id','!=',1)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->where('id','!=',1)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->where('id','!=',1)->get());
    
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->like($this->mysql()->tables(),'b')->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->like($this->postgresql()->tables(),'b')->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->like($this->sqlite()->tables(),'b')->get());

        
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get()); 
    }

    /**
     * @throws \Exception
     */
    public function test_count()
    {
        $this->assertEquals(5,$this->mysql()->query()->count());
        $this->assertEquals(5,$this->postgresql()->query()->count());
        $this->assertEquals(5,$this->sqlite()->query()->count());
    }


}
