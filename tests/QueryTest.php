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

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
    
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->like($this->mysql()->tables(),'b')->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->like($this->postgresql()->tables(),'b')->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->like($this->sqlite()->tables(),'b')->get());

        
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->between('id',1,16)->get());



        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());


        $this->assertNotContains("ORDER BY ",$this->mysql()->query()->set_query_mode(Query::DELETE)->where('id','=',16)->sql());
        $this->assertNotContains("ORDER BY ",$this->postgresql()->query()->set_query_mode(Query::DELETE)->where('id','=',16)->sql());
        $this->assertNotContains("ORDER BY ",$this->sqlite()->query()->set_query_mode(Query::DELETE)->where('id','=',16)->sql());

        $this->expectException(\Exception::class);

        $this->mysql()->where('id','azd','5');
        $this->postgresql()->where('id','azd','5');
        $this->sqlite()->where('id','azd','5');

        $this->mysql()->query()->where('id','azd','5')->get();
        $this->postgresql()->query()->where('id','azd','5')->get();
        $this->sqlite()->query()->where('id','azd','5')->get();


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
