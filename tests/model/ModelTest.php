<?php


namespace tests;

use Exception;
use Imperium\Connexion\Connect;
use Imperium\Imperium;
use Imperium\Model\Model;
use PDO;
use Imperium\Query\Query;
use Testing\DatabaseTest;

class ModelTest extends DatabaseTest
{

    /**
     * @var Model
     */
    private $mysql_model;

    /**
     * @var Model
     */
    private $pgsql_model;

    /**
     * @var Model
     */
    private $sqlite_model;

    /**
     * @throws Exception
     */
    public function setUp()
    {
        $this->table = 'model';
        $this->mysql_model = $this->mysql()->model()->change_table($this->table);
        $this->pgsql_model = $this->postgresql()->model()->change_table($this->table);
        $this->sqlite_model = $this->sqlite()->model()->change_table($this->table);
    }

    public function test_search()
    {
        $this->assertNotEmpty($this->mysql_model->search('a'));
        $this->assertNotEmpty($this->mysql_model->search(4));
        $this->assertNotEmpty($this->pgsql_model->search('a'));
        $this->assertNotEmpty($this->pgsql_model->search(4));
        $this->assertNotEmpty($this->sqlite_model->search('a'));
        $this->assertNotEmpty($this->sqlite_model->search(4));
    }

    public function test_cool()
    {


        $bool = $this->mysql_model
                    ->set('phone',faker()->randomNumber(8))
                    ->set('name', faker()->name)
                    ->set('date', faker()->date())
                    ->set('sex', 'F')
                    ->set('days', faker()->date())
                    ->set('age',  faker()->numberBetween(1,100))
                    ->set('status','dead')
                ->save();
        $this->assertTrue($bool);

        $bool = $this->pgsql_model
                    ->set('phone',faker()->randomNumber(8))
                    ->set('name', faker()->name)
                    ->set('date', faker()->date())
                    ->set('sex', 'F')
                    ->set('days', faker()->date())
                    ->set('age',  faker()->numberBetween(1,100))
                    ->set('status','dead')
                ->save();
        $this->assertTrue($bool);

        $bool = $this->sqlite_model
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
        $this->assertInstanceOf(Query::class,$this->mysql_model->query());
        $this->assertInstanceOf(Query::class,$this->pgsql_model->query());
        $this->assertInstanceOf(Query::class,$this->sqlite_model->query());
    }
    public function test_cool_not_correct()
    {

        $this->expectException(Exception::class);

        $this->mysql_model
            ->set('phone','lorem')
            ->set('name',50)
            ->set('date', 'dimanche')
            ->set('sex', 5369)
            ->set('days', 'a')
            ->set('age',  '50')
            ->set('status',300)
        ->save();

        $this->pgsql_model
            ->set('phone','lorem')
            ->set('name',50)
            ->set('date', 'dimanche')
            ->set('sex', 5369)
            ->set('days', 'a')
            ->set('age',  '50')
            ->set('status',300)
        ->save();

        $this->sqlite_model
            ->set('phone','lorem')
            ->set('name',50)
            ->set('date', 'dimanche')
            ->set('sex', 5369)
            ->set('days', 'a')
            ->set('age',  '50')
            ->set('status',300)
        ->save();
    }

    public function test_cool_exep()
    {
        $this->expectException(Exception::class);

        $this->mysql_model
            ->set('phone',faker()->randomNumber(8))
            ->set('name', faker()->name)
            ->set('date', faker()->date())
            ->set('days', faker()->date())
            ->set('age',  faker()->numberBetween(1,100))
            ->set('status','dead')
        ->save();

        $this->pgsql_model
            ->set('phone',faker()->randomNumber(8))
            ->set('name', faker()->name)
            ->set('date', faker()->date())
            ->set('age',  faker()->numberBetween(1,100))
            ->set('status','dead')
        ->save();

        $this->sqlite_model
            ->set('phone',faker()->randomNumber(8))
            ->set('name', faker()->name)
            ->set('sex', 'F')
            ->set('age',  faker()->numberBetween(1,100))
            ->set('status','dead')
        ->save();
    }

    public function test_all()
    {
        $this->assertNotEmpty($this->mysql_model->all());
        $this->assertNotEmpty($this->pgsql_model->all());
        $this->assertNotEmpty($this->sqlite_model->all());
    }
    public function test_show()
    {
        $record  = $this->mysql_model->show('imperium',[],'?table=','/',1,5,'table','remove','sure','btn btn-danger','remove','fa fa-trash','edit','edit','fa fa-edit','btn btn-primary',true,true,true,'previous','next','id','desc');

        $this->assertContains('?table=', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('/', $record);
        $this->assertContains('sure', $record);
        $this->assertContains('next', $record);
        $this->assertContains('previous', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('class="btn btn-danger"', $record);
        $this->assertContains('class="btn btn-primary"', $record);
        $this->assertContains('fa fa-trash', $record);
        $this->assertContains('fa fa-edit', $record);

        $record  = $this->pgsql_model->show('imperium',[],'?table=','/',1,10,'table','remove','sure','btn btn-danger','remove','fa fa-trash','edit','edit','fa fa-edit','btn btn-primary',true,true,true,'previous','next','id','desc');

        $this->assertContains('?table=', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('/', $record);
        $this->assertContains('sure', $record);
        $this->assertContains('next', $record);
        $this->assertContains('previous', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('class="btn btn-danger"', $record);
        $this->assertContains('class="btn btn-primary"', $record);
        $this->assertContains('fa fa-trash', $record);
        $this->assertContains('fa fa-edit', $record);

        $record  = $this->sqlite_model->show('imperium',[],'?table=','/',1,10,'table','remove','sure','btn btn-danger','remove','fa fa-trash','edit','edit','fa fa-edit','btn btn-primary',true,true,true,'previous','next','id','desc');

        $this->assertContains('?table=', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('/', $record);
        $this->assertContains('sure', $record);
        $this->assertContains('next', $record);
        $this->assertContains('previous', $record);
        $this->assertContains('remove', $record);
        $this->assertContains('class="btn btn-danger"', $record);
        $this->assertContains('class="btn btn-primary"', $record);

    }

    public function test_edit()
    {

        $form = $this->mysql_model->edit(5,'/','edit','update','btn-primary');
        $this->assertNotEmpty($form);

        $form = $this->pgsql_model->edit(5,'/','edit','update','btn-primary');
        $this->assertNotEmpty($form);
        $form = $this->sqlite_model->edit(5,'/','edit','update','btn-primary');
        $this->assertNotEmpty($form);
    }

    public function test_create()
    {

        $form = $this->mysql_model->create('/','edit','update','btn-primary');
        $this->assertNotEmpty($form);

        $form = $this->pgsql_model->create('/','edit','update','btn-primary');
        $this->assertNotEmpty($form);
        $form = $this->sqlite_model->create('/','edit','update','btn-primary');
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

        $this->assertTrue($this->mysql_model->truncate());

        $result = query_result($this->mysql()->model(),$this->mysql_model->all(),$success,$empty,$empty,$sql);

        $this->assertCount(0,$this->mysql_model->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->mysql_model->is_empty());

        $this->assertTrue($this->pgsql_model->truncate());

        $result = query_result($this->postgresql()->model(),$this->mysql_model->all(),$success,$empty,$empty,$sql);

        $this->assertCount(0,$this->pgsql_model->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->pgsql_model->is_empty());

        $this->assertTrue($this->sqlite_model->truncate());

        $result = query_result($this->sqlite()->model(),$this->mysql_model->all(),$success,$empty,$empty,$sql);
        $this->assertCount(0,$this->sqlite_model->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->sqlite_model->is_empty());
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
                'id' => null,
                'name' => "'". faker()->name,
                'age' => faker()->numberBetween(1,100),
                'phone' => faker()->randomNumber(8),
                'sex' => faker()->firstNameMale,
                'status' =>  "'".faker()->text(20),
                'days' => faker()->date(),
                'date' => faker()->date(),
            ];

            $this->assertTrue($this->mysql_model->insert($data,$this->table));
            $this->assertTrue($this->pgsql_model->insert($data,$this->table));
            $this->assertTrue($this->sqlite_model->insert($data,$this->table));
        }

        $this->assertCount($number,$this->mysql_model->all());
        $this->assertEquals($number,$this->mysql_model->count());

        $this->assertCount($number,$this->pgsql_model->all());
        $this->assertEquals($number,$this->pgsql_model->count());

        $this->assertCount($number,$this->sqlite_model->all());
        $this->assertEquals($number,$this->sqlite_model->count());
    }



    /**
     * @throws \Exception
     */
    public function test_show_tables()
    {
        $this->assertContains($this->table,$this->mysql_model->show_tables());
        $this->assertContains($this->table,$this->pgsql_model->show_tables());
        $this->assertContains($this->table,$this->sqlite_model->show_tables());

        $this->assertContains($this->table,$this->mysql_model->show_tables());
        $this->assertContains($this->table,$this->pgsql_model->show_tables());
        $this->assertContains($this->table,$this->sqlite_model->show_tables());


        $this->assertNotContains($this->table,$this->mysql_model->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->pgsql_model->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->sqlite_model->show_tables([$this->table]));


    }

    /**
     * @throws Exception
     */
    public function test_is()
    {
        $this->assertTrue($this->mysql_model->is_mysql());
        $this->assertFalse($this->pgsql_model->is_mysql());
        $this->assertFalse($this->sqlite_model->is_mysql());

        $this->assertFalse($this->mysql_model->is_postgresql());
        $this->assertTrue($this->pgsql_model->is_postgresql());
        $this->assertFalse($this->sqlite_model->is_postgresql());

        $this->assertFalse($this->mysql_model->is_sqlite());
        $this->assertFalse($this->pgsql_model->is_sqlite());
        $this->assertTrue($this->sqlite_model->is_sqlite());

    }

    /**
     * @throws Exception
     *
     */
    public function test_request()
    {

        $req =  "select * from $this->table";
        $this->assertNotEmpty($this->mysql_model->request($req));
        $this->assertNotEmpty($this->pgsql_model->request($req));
        $this->assertNotEmpty($this->sqlite_model->request($req));
    }


    /**
     * @throws Exception
     */
    public function test_seed()
    {
        $this->assertTrue($this->mysql_model->seed(100));
        $this->assertTrue($this->pgsql_model->seed(100));
        $this->assertTrue($this->sqlite_model->seed(100));
    }
    /**
    * @throws Exception
    */
    public function test_execute()
    {

        $req =  "select * from $this->table";
        $this->assertTrue($this->mysql_model->execute($req));
        $this->assertTrue($this->pgsql_model->execute($req));
        $this->assertTrue($this->sqlite_model->execute($req));
    }

    /**
     * @throws Exception
     */
    public function test_pdo()
    {
        $this->assertInstanceOf(PDO::class,$this->mysql()->connect()->instance());
        $this->assertInstanceOf(PDO::class,$this->mysql()->model()->pdo());

        $this->assertInstanceOf(PDO::class,$this->postgresql()->connect()->instance());
        $this->assertInstanceOf(PDO::class,$this->postgresql()->model()->pdo());

        $this->assertInstanceOf(PDO::class,$this->sqlite()->connect()->instance());
        $this->assertInstanceOf(PDO::class,$this->sqlite()->model()->pdo());


    }
    /**
     * @throws \Exception
     */
    public function test_find()
    {

        $this->assertCount(1,$this->mysql_model->find(2));
        $this->assertCount(1,$this->pgsql_model->find(2));
        $this->assertCount(1,$this->sqlite_model->find(2));
    }

    /**
     * @throws \Exception
     */
    public function test_find_or_fail()
    {

        $this->assertCount(1,$this->mysql_model->find_or_fail(2));
        $this->assertCount(1,$this->pgsql_model->find_or_fail(2));
        $this->assertCount(1,$this->sqlite_model->find_or_fail(2));

        $this->expectException(Exception::class);
        $this->mysql_model->find_or_fail(800);
        $this->pgsql_model->find_or_fail(800);
        $this->sqlite_model->find_or_fail(800);

    }

    /**
     * @throws Exception
     */
    public function test_remove()
    {

        $this->assertTrue($this->mysql_model->remove(4));
        $this->assertTrue($this->pgsql_model->remove(4));
        $this->assertTrue($this->sqlite_model->remove(4));
    }


    /**
     * @throws Exception
     */

    public function test_update()
    {
        $data = [
            'id' => null,
            'name' => faker()->name,
            'age' => faker()->numberBetween(1,100),
            'phone' => faker()->randomNumber(8),
            'sex' => faker()->firstNameMale,
            'status' => faker()->text(20),
            'days' => faker()->date(),
            'date' => faker()->date(),
        ];
        $this->assertTrue($this->mysql_model->update(4,$data,$this->table,[]));
        $this->assertTrue($this->pgsql_model->update(4,$data,$this->table,[]));
        $this->assertTrue($this->sqlite_model->update(4,$data,$this->table,[]));
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
                'id' => null,
                'name' => faker()->name,
                'age' => faker()->numberBetween(1,100),
                'phone' => faker()->randomNumber(8),
                'sex' => faker()->firstNameMale,
                'status' => faker()->text(20),
                'days' => faker()->date(),
                'date' => faker()->date(),
            ];

            $this->assertTrue($this->mysql_model->insert($data,$this->table,[]));
            $this->assertTrue($this->pgsql_model->insert($data,$this->table,[]));
            $this->assertTrue($this->sqlite_model->insert($data,$this->table,[]));

        }

        $number = 109;
        $this->assertCount($number,$this->mysql_model->all());
        $this->assertEquals($number,$this->mysql_model->count());

        $this->assertCount($number,$this->pgsql_model->all());
        $this->assertEquals($number,$this->pgsql_model->count());

        $this->assertCount($number,$this->sqlite_model->all());
        $this->assertEquals($number,$this->sqlite_model->count());
    }

    /**
     * @throws Exception
     */
    public function test_found()

    {
        $this->assertEquals(7,$this->mysql_model->found());
        $this->assertEquals(7,$this->pgsql_model->found());
        $this->assertEquals(7,$this->sqlite_model->found());
    }
    /**
     * @throws Exception
     */
    public function test_get()
    {
        $id = 1;

        $param = 'id';

        $condition = '=';

        $this->expectException(Exception::class);

        $this->mysql_model->only('name')->get();
        $this->pgsql_model->only('name')->get();
        $this->sqlite_model->only('name')->get();

        $this->assertNotEmpty($this->mysql_model->where($param, $condition,$id)->only('name')->get());
        $this->assertNotEmpty($this->pgsql_model->where($param, $condition,$id)->only('name')->get());
        $this->assertNotEmpty($this->sqlite_model->where($param, $condition,$id)->only('name')->get());


        $this->assertNotEmpty($this->mysql_model->where($param, $condition,$id)->only('name','phone')->get());
        $this->assertNotEmpty($this->pgsql_model->where($param, $condition,$id)->only('name','phone')->get());
        $this->assertNotEmpty($this->sqlite_model->where($param, $condition,$id)->only('name','phone')->get());




    }

    /**
     * @throws Exception
     */
    public function test_news_and_last()
    {
        $this->assertNotEmpty($this->mysql_model->news('name',20));
        $this->assertNotEmpty($this->mysql_model->news('name',20,5));

        $this->assertNotEmpty($this->pgsql_model->news('name',20));
        $this->assertNotEmpty($this->pgsql_model->news('name',20,5));

        $this->assertNotEmpty($this->sqlite_model->news('name',20));
        $this->assertNotEmpty($this->sqlite_model->news('name',20,5));

        $this->assertNotEmpty($this->mysql_model->last('name',20));
        $this->assertNotEmpty($this->mysql_model->last('name',20,5));

        $this->assertNotEmpty($this->pgsql_model->last('name',20));
        $this->assertNotEmpty($this->pgsql_model->last('name',20,5));

        $this->assertNotEmpty($this->sqlite_model->last('name',20));
        $this->assertNotEmpty($this->sqlite_model->last('name',20,5));
    }

    /**
     *
     * @throws Exception
     */
    public function test_driver()
    {
        $this->assertTrue($this->mysql()->connect()->mysql());
        $this->assertTrue($this->postgresql()->connect()->postgresql());
        $this->assertTrue($this->sqlite()->connect()->sqlite());

        $this->assertFalse($this->mysql()->connect()->postgresql());
        $this->assertFalse($this->postgresql()->connect()->sqlite());
        $this->assertFalse($this->sqlite()->connect()->mysql());
    }

    /**
     * @throws Exception
     */
    public function test_get_instance()
    {
        $expected = PDO::class;
        $x = new Connect(Connect::MYSQL,'',self::MYSQL_USER,self::MYSQL_PASS,Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::POSTGRESQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf($expected,$x->instance());


        $x = new Connect(Connect::SQLITE,'','','',Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::MYSQL,$this->base,self::MYSQL_USER,self::MYSQL_PASS,Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::POSTGRESQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf($expected,$x->instance());


        $x = new Connect(Connect::SQLITE,$this->base,'','',Connect::LOCALHOST,5,'dump');
        $this->assertInstanceOf ($expected,$x->instance());

        $this->expectException(Exception::class);

        $x = new Connect(Connect::POSTGRESQL,'',self::MYSQL_USER,self::MYSQL_PASS,Connect::LOCALHOST,5,'dump');
        $x->instance();
        $x = new Connect(Connect::MYSQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,5,'dump');
        $x->instance();
        $x =  new Connect(Connect::POSTGRESQL,$this->base,self::MYSQL_USER,self::MYSQL_PASS,Connect::LOCALHOST,5,'dump');
        $x->instance();
        $x = new Connect(Connect::MYSQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS,Connect::LOCALHOST,5,'dump');
        $x->instance();
    }
}
