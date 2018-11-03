<?php

namespace tests\query;

 
use Exception;
use Imperium\Query\Query;
use Testing\DatabaseTest;


class QueryTest extends DatabaseTest 
{

    /**
     * @var Query
     */
    private $mysql_query;

    /**
     * @var Query
     */
    private $pgsql_query;

    /**
     * @var Query
     */
    private $sqlite_query;
    private $second_table;


    public function setUp()
    {
        $this->table = 'query';
        $this->second_table = 'base';
        $this->mysql_query = $this->mysql()->query()->from($this->table);
        $this->pgsql_query = $this->postgresql()->query()->from($this->table);
        $this->sqlite_query = $this->sqlite()->query()->from($this->table);

    }

    /**
     * @throws Exception
     */
    public function test_set()
    {
        $this->expectException(Exception::class);

        $mode = faker()->text(5);
        $this->mysql_query->set_query_mode($mode)->get();
        $this->pgsql_query->set_query_mode($mode)->get();
        $this->sqlite_query->set_query_mode($mode)->get();
        $this->mysql_query->get();
        $this->pgsql_query->get();
        $this->sqlite_query->get();
    }

    /**
     * @throws \Exception
     */
    public function test_where()
    {


        $this->assertNotEmpty($this->mysql()->model()->change_table($this->table)->where('id','=',5)->only('date')->get());
        $this->assertNotEmpty($this->mysql()->model()->change_table($this->table)->where('id','=',50)->only('date')->get());

        $this->assertNotEmpty($this->postgresql()->model()->change_table($this->table)->where('id','=',5)->only('date')->get());
        $this->assertNotEmpty($this->postgresql()->model()->change_table($this->table)->where('id','=',50)->only('date')->get());

        $this->assertNotEmpty($this->sqlite()->model()->change_table($this->table)->where('id','=',5)->only('date')->get());
        $this->assertNotEmpty($this->sqlite()->model()->change_table($this->table)->where('id','=',50)->only('date')->get());


        $b = '1988-07-15 00:00:00';
        $e = '2010-07-30 00:00:00';

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->where('id','!=',1)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->where('id','!=',1)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->where('id','!=',1)->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->where('name','!=','will')->get());
    
        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->like($this->mysql()->tables(),'a')->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->like($this->postgresql()->tables(),'a')->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->like($this->sqlite()->tables(),'a')->get());

        
        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->between('id',1,16)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->between('id',1,16)->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('id',1,16)->get());


        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('date',$b,$e)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('date',$b,$e)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::SELECT)->set_columns(['id'])->between('date',$b,$e)->get());




        $this->assertNotContains("ORDER BY ",$this->mysql_query->set_query_mode(Query::DELETE)->where('id','=',16)->sql());
        $this->assertNotContains("ORDER BY ",$this->pgsql_query->set_query_mode(Query::DELETE)->where('id','=',16)->sql());
        $this->assertNotContains("ORDER BY ",$this->sqlite_query->set_query_mode(Query::DELETE)->where('id','=',16)->sql());

        $this->expectException(\Exception::class);

        $this->mysql()->where('id','azd','5');
        $this->postgresql()->where('id','azd','5');
        $this->sqlite()->where('id','azd','5');

        $this->mysql_query->where('id','azd','5')->get();
        $this->pgsql_query->where('id','azd','5')->get();
        $this->sqlite_query->where('id','azd','5')->get();

    }

    /**
     * @throws Exception
     */
    public function test_union()
    {


         $limit = 10;

         $columns = ['id','name'];

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns, $columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns, $columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());
        


        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());

        $this->assertNotEmpty($this->mysql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());
        $this->assertNotEmpty($this->pgsql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());
        $this->assertNotEmpty($this->sqlite_query->set_query_mode(Query::UNION_ALL)->union( $this->table,$this->second_table,[],[])->limit($limit,0)->get());


        $this->assertCount($limit,$this->mysql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->pgsql_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->sqlite_query->set_query_mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());


        $this->assertCount($limit,$this->mysql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->pgsql_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());
        $this->assertCount($limit,$this->sqlite_query->set_query_mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());


    }



    /**
     * @throws \Exception
     */
    public function test_count()
    {
        $this->assertEquals(150,$this->mysql_query->count());
        $this->assertEquals(150,$this->pgsql_query->count());
        $this->assertEquals(150,$this->sqlite_query->count());
    }


}
