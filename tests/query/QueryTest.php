<?php

namespace tests\query;

 
use Exception;
use Imperium\Query\Query;
use Testing\DatabaseTest;


class QueryTest extends DatabaseTest 
{


    /**
     * @throws Exception
     */
    public function test_set()
    {
        $this->expectException(Exception::class);

        $mode = faker()->text(5);
        $this->mysql()->query()->set_query_mode($mode)->get();
        $this->postgresql()->query()->set_query_mode($mode)->get();
        $this->sqlite()->query()->set_query_mode($mode)->get();
        $this->mysql()->query()->get();
        $this->postgresql()->query()->get();
        $this->sqlite()->query()->get();
    }

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
    
        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::SELECT)->like($this->mysql()->tables(),'a')->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::SELECT)->like($this->postgresql()->tables(),'a')->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::SELECT)->like($this->sqlite()->tables(),'a')->get());

        
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
     * @throws Exception
     */
    public function test_union()
    {


         $limit = 10;

         $columns = ['id','name'];

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns, $columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns, $columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        


        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());

        $this->assertNotEmpty($this->mysql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());
        $this->assertNotEmpty($this->postgresql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite()->query()->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->limit($limit,0)->get());


        $this->assertCount($limit,$this->mysql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->postgresql()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->sqlite()->query()->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());


        $this->assertCount($limit,$this->mysql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->postgresql()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->sqlite()->query()->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());


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
