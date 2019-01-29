<?php

namespace Testing\mysql\query {


    use Exception;
    use Imperium\Query\Query;
    use Symfony\Component\DependencyInjection\Tests\Compiler\E;
    use Testing\DatabaseTest;


    class QueryTest extends DatabaseTest
    {


        /**
         * @var Query
         */
        private $query;
        /**
         * @var string
         */
        private $second_table;


        public function setUp()
        {
            $this->table = 'query';
            $this->second_table = 'helpers';
            $this->query = $this->mysql()->query()->from($this->table);

        }


        /**
         * @throws \Exception
         */
        public function test_where()
        {

            $this->assertNotEmpty($this->query->where('id','=',5)->only(['date'])->get());


            $b = '1988-07-15 00:00:00';
            $e = '2010-07-30 00:00:00';

            $this->assertNotEmpty($this->query->mode(Query::SELECT)->where('id','!=',1)->get());

            $this->assertNotEmpty($this->query->mode(Query::SELECT)->where('name','!=','will')->get());

            $this->assertNotEmpty($this->query->mode(Query::SELECT)->like('a')->get());


            $this->assertNotEmpty($this->query->mode(Query::SELECT)->between('id',1,16)->get());

            $this->assertNotEmpty($this->query->mode(Query::SELECT)->columns(['id'])->between('id',1,16)->get());

            $this->assertNotEmpty($this->query->mode(Query::SELECT)->columns(['id'])->between('date',$b,$e)->get());


            $this->assertNotContains("ORDER BY ",$this->query->mode(Query::DELETE)->where('id','=',16)->sql());


        }

        /**
         * @throws Exception
         */
        public function test_union()
        {


            $limit = 10;

            $columns = ['id','name'];

            $this->assertNotEmpty($this->query->mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION)->union($this->table,$this->second_table,$columns, $columns)->limit($limit,0)->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION_ALL)->union($this->table,$this->second_table,$columns,$columns)->limit($limit,0)->get());



            $this->assertNotEmpty($this->query->mode(Query::UNION)->union($this->table,$this->second_table,[], [])->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());

            $this->assertNotEmpty($this->query->mode(Query::UNION_ALL)->union($this->table,$this->second_table,[],[])->limit($limit,0)->get());


            $this->assertCount($limit,$this->query->mode(Query::UNION)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());

            $this->assertCount($limit,$this->query->mode(Query::UNION_ALL)->union($this->table,$this->second_table,[], [])->limit($limit,0)->get());


        }

        public function test_join_execp()
        {


            $this->expectException(Exception::class);

            $this->query->mode(Query::FULL_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get();
            $this->query->mode(Query::FULL_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get();

        }

        public function test_join()
        {
            $this->assertNotEmpty($this->query->mode(INNER_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(INNER_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id','id')->get());

            $this->assertNotEmpty($this->query->mode(CROSS_JOIN)->join(DIFFERENT,$this->table,$this->second_table,'id','id','id')->get());

            $this->assertNotEmpty($this->query->mode(CROSS_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(CROSS_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id','id')->get());

            $this->assertNotEmpty($this->query->mode(LEFT_JOIN)->join(DIFFERENT,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(LEFT_JOIN)->join(DIFFERENT,$this->table,$this->second_table,'id','id','id')->get());

            $this->assertNotEmpty($this->query->mode(LEFT_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(LEFT_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id','id')->get());


            $this->assertNotEmpty($this->query->mode(RIGHT_JOIN)->join(DIFFERENT,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(RIGHT_JOIN)->join(DIFFERENT,$this->table,$this->second_table,'id','id','id')->get());


            $this->assertNotEmpty($this->query->mode(RIGHT_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(RIGHT_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id','id')->get());

            $this->assertNotEmpty($this->query->mode(INNER_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id')->get());
            $this->assertNotEmpty($this->query->mode(INNER_JOIN)->join(EQUAL,$this->table,$this->second_table,'id','id','id')->get());


        }


    }
}
