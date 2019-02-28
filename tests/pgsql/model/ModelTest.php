<?php

namespace Testing\pgsql\model {

    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\Model\Model;
    use PDO;
    use Imperium\Query\Query;
    use Testing\DatabaseTest;

    class ModelTest extends DatabaseTest
    {

        /**
         * @var Model
         */
        private $model;

        /**
         * @var string
         */
        private $table;

        /**
         * @throws Exception
         */
        public function setUp():void
        {
            $this->table = 'model';
            $this->model = $this->postgresql()->model()->from($this->table);
        }

        /**
         * @throws \Exception
         */
        public function test_find()
        {
            $this->assertCount(1,$this->model->find(2));
            $this->assertCount(1,$this->model->find_or_fail(2));

            $records = $this->model->all();

            foreach ($records as  $record)
            {
                foreach ($this->model->columns() as $k => $v)
                {
                    $this->assertNotEmpty($this->model->by($v,EQUAL,$record->$v));

                }
            }
        }
        public function test_only()
        {
            $this->assertNotEmpty($this->model->where('id', EQUAL,55)->only('name')->get());

        }
        /**
         * @throws Exception
         */
        public function test_search()
        {
            $this->assertNotEmpty($this->model->search('a'));
            $this->assertNotEmpty($this->model->search(4));
        }

        /**
         * @throws Exception
         */
        public function test_news_and_last()
        {
            $this->assertNotEmpty($this->model->news('id',20));
            $this->assertNotEmpty($this->model->news('id',20,5));

            $this->assertNotEmpty($this->model->last('id',20));
            $this->assertNotEmpty($this->model->last('id',20,5));

        }
        public function test_cool()
        {
            $bool = $this->model
                ->set('id','id')
                ->set('phone',faker()->randomNumber(8))
                ->set('name', faker()->name)
                ->set('date', faker()->date())
                ->set('sex', 'F')
                ->set('days', faker()->date())
                ->set('age',  faker()->numberBetween(1,100))
                ->set('status','dead')
                ->save();
            $this->assertTrue($bool);
        }

        public function test_get_query()
        {
            $this->assertInstanceOf(Query::class,$this->model->query());
        }


        public function test_all()
        {
            $this->assertNotEmpty($this->model->all());

        }
        public function test_show()
        {
            $record  = $this->model->show(
                'table-responsive','table-dark','?table=',1,'table','remove','sure',
                '','','remove','edit','edit','previous','next','id','desc','search','','',
                '','',''

            );

            $this->assertStringContainsString('?table=', $record);
            $this->assertStringContainsString('remove', $record);
            $this->assertStringContainsString('/', $record);
            $this->assertStringContainsString('sure', $record);
            $this->assertStringContainsString('next', $record);
            $this->assertStringContainsString('previous', $record);
            $this->assertStringContainsString('remove', $record);
            $this->assertStringContainsString('class="btn btn-danger"', $record);
            $this->assertStringContainsString('class="btn btn-primary"', $record);
        }

        public function test_edit()
        {

            $form = edit($this->table,5,'/',id(),'update','');
            $this->assertNotEmpty($form);

        }

        public function test_primary()
        {
            $this->assertEquals('id',$this->model->primary());
        }

        public function test_create()
        {

            $form = create($this->table,'/',id(),'create','');
            $this->assertNotEmpty($form);
        }

        /**
         * @throws Exception
         */
        public function test_truncate()
        {
            $empty = 'the table is empty';
            $success = 'records was found';

            $sql ='';

            $this->assertTrue($this->model->truncate($this->table));

            $result = query_result($this->table,SELECT,$this->model->all('id'),$success,$empty,$empty,$sql);

            $this->assertCount(0,$this->model->all());
            $this->assertStringContainsString($empty,$result);
            $this->assertTrue( $this->model->is_empty($this->table));

        }

        /**
         * @throws Exception
         */
        public function test_insert()
        {


            $number = 5;
            for ($i = 0; $i != $number; ++$i)
            {
                $data = [
                    'id' => 'id',
                    'name' => faker()->name,
                    'age' => faker()->numberBetween(1,100),
                    'phone' => faker()->randomNumber(8),
                    'sex' => faker()->firstNameMale,
                    'status' => faker()->text(20),
                    'days' => faker()->date(),
                    'date' => faker()->date()
                ];

                $this->assertTrue($this->model->insert_new_record($data));
            }

            $this->assertCount($number,$this->model->all());
            $this->assertEquals($number,$this->model->count($this->table));

        }



        /**
         * @throws \Exception
         */
        public function test_show_tables()
        {
            $this->assertContains($this->table,$this->model->show_tables());

        }

        /**
         * @throws Exception
         */
        public function test_is()
        {
            $this->assertTrue($this->model->is_postgresql());
            $this->assertFalse($this->model->is_mysql());
            $this->assertFalse($this->model->is_sqlite());

        }

        /**
         * @throws Exception
         *
         */
        public function test_request()
        {

            $req =  "select * from $this->table";
            $this->assertNotEmpty($this->model->request($req));
        }


        /**
         * @throws Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->model->seed(100));
        }
        /**
         * @throws Exception
         */
        public function test_execute()
        {
            $req =  "select * from $this->table";
            $this->assertTrue($this->model->execute($req));
        }

        /**
         * @throws Exception
         */
        public function test_pdo()
        {
            $this->assertInstanceOf(PDO::class,$this->postgresql()->connect()->instance());
            $this->assertInstanceOf(PDO::class,$this->postgresql()->model()->pdo());
        }

        /**
         * @throws \Exception
         */
        public function test_find_or_fail()
        {
            $this->expectException(Exception::class);
            $this->model->find_or_fail(800);

        }

        /**
         * @throws Exception
         */
        public function test_remove()
        {
            $this->assertTrue($this->model->remove(4));
        }


        /**
         * @throws Exception
         */

        public function test_update()
        {

            $data = [
                'id' => 'id',
                'name' => faker()->name,
                'age' => faker()->numberBetween(1,100),
                'phone' => faker()->randomNumber(8),
                'sex' => faker()->firstNameMale,
                'status' => faker()->text(20),
                'days' => faker()->date(),
                'date' => faker()->date()
            ];

            $this->assertTrue($this->model->update_record(4,$data));
        }



        /**
         * @throws Exception
         */
        public function test_save()
        {
            $number = 5;
            for ($i = 0; $i != $number; ++$i)
            {

                $data = [
                    'id' => 'id',
                    'name' => faker()->name,
                    'age' => faker()->numberBetween(1,100),
                    'phone' => faker()->randomNumber(8),
                    'sex' => faker()->firstNameMale,
                    'status' => faker()->text(20),
                    'days' => faker()->date(),
                    'date' => faker()->date()
                ];
                $this->assertTrue($this->model->insert_new_record($data));

            }
            $this->assertCount(109,$this->model->all('id'));
        }

        /**
         * @throws Exception
         */
        public function test_found()
        {
            $this->assertEquals(11,$this->model->found());
        }




        /**
         * @throws Exception
         */
        public function test_get_instance()
        {
            $expected = PDO::class;
            $x = new Connect(Connect::POSTGRESQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,'dump');
            $this->assertInstanceOf($expected,$x->instance());

            $x = new Connect(Connect::POSTGRESQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,'dump');
            $this->assertInstanceOf($expected,$x->instance());

            $this->expectException(Exception::class);

            $x = new Connect(Connect::MYSQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,'dump');
            $x->instance();
            $x = new Connect(Connect::MYSQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,'dump');
            $x->instance();
        }

        public function test_dump()
        {
            $this->assertTrue($this->postgresql()->model()->dump($this->table));
            $this->assertTrue($this->postgresql()->model()->dump_base());
        }
        public function test_import()
        {
            $this->assertTrue($this->postgresql()->model()->dump($this->table));

        }
    }
}
