<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 06/09/2018
 * Time: 14:28
 */

namespace tests;

 
use Exception;
use Imperium\Connexion\Connect;
use Imperium\Imperium;
use Imperium\Model\Model;
use PDO;
use Testing\DatabaseTest;

class ModelTest extends DatabaseTest
{
    /**
     * @throws \Exception
     */
    public function test_show_tables()
    {
        $this->assertContains($this->table,$this->mysql()->model()->show_tables());
        $this->assertContains($this->table,$this->postgresql()->model()->show_tables());
        $this->assertContains($this->table,$this->sqlite()->model()->show_tables());

        $this->assertContains($this->table,$this->mysql()->show_tables());
        $this->assertContains($this->table,$this->postgresql()->show_tables());
        $this->assertContains($this->table,$this->sqlite()->show_tables());


        $this->assertNotContains($this->table,$this->mysql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->postgresql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->sqlite()->show_tables([$this->table]));


    }

    /**
     * @throws Exception
     *
     */
    public function test_request()
    {
        $table = $this->table;
        $this->assertNotEmpty($this->mysql()->model()->request('SHOW DATABASES'));
        $this->assertNotEmpty($this->postgresql()->model()->request("SELECT * FROM $table"));
        $this->assertNotEmpty($this->sqlite()->model()->request("SELECT * FROM $table"));
    }

    /**
     * @throws Exception
     */
    public function test_construct()
    {
        $this->assertInstanceOf(Model::class,new Model($this->mysql()->connect(),$this->mysql()->tables(),$this->table));
        $this->assertInstanceOf(Model::class,new Model($this->postgresql()->connect(),$this->postgresql()->tables(),$this->table));
        $this->assertInstanceOf(Model::class,new Model($this->sqlite()->connect(),$this->sqlite()->tables(),$this->table));
    }
    /**
    * @throws Exception
    */
    public function test_execute()
    {
        $table = $this->table;
        $this->assertTrue($this->mysql()->model()->execute('SHOW DATABASES'));
        $this->assertTrue($this->postgresql()->model()->execute("SELECT * FROM $table"));
        $this->assertTrue($this->sqlite()->model()->execute("SELECT * FROM $table"));
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

        $this->assertCount(1,$this->mysql()->find(10));
        $this->assertCount(1,$this->postgresql()->find(10));
        $this->assertCount(1,$this->sqlite()->find(10));

    }

    /**
     * @throws \Exception
     */
    public function test_find_or_fail()
    {

        $this->assertCount(1,$this->mysql()->find_or_fail(10));
        $this->assertCount(1,$this->postgresql()->find_or_fail(10));
        $this->assertCount(1,$this->sqlite()->find_or_fail(10));

        $this->expectException(Exception::class);
        $this->mysql()->find_or_fail(800);
        $this->postgresql()->find_or_fail(800);
        $this->sqlite()->find_or_fail(800);

    }

    /**
     * @throws Exception
     */
    public function test_remove()
    {
        $this->assertTrue($this->mysql()->remove_record(4));
        $this->assertTrue($this->postgresql()->remove_record(4));
        $this->assertTrue($this->sqlite()->remove_record(4));
    }

    /**
     * @throws Exception
     */
    public function test_truncate()
    {
        $empty = 'the table is empty';
        $success = 'records was found';

        $this->assertTrue($this->mysql()->model()->truncate());
        $result = query_result($this->mysql()->model(),Imperium::SELECT,$this->mysql()->all(),$this->mysql()->show_columns(),$success,$empty,$empty);

        $this->assertCount(0,$this->mysql()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->mysql()->model()->empty());

        $this->assertTrue($this->postgresql()->model()->truncate());
        $result = query_result($this->postgresql()->model(),Imperium::SELECT,$this->postgresql()->all(),$this->postgresql()->show_columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->postgresql()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->postgresql()->model()->empty());

        $this->assertTrue($this->sqlite()->model()->truncate());
        $result = query_result($this->sqlite()->model(),Imperium::SELECT,$this->sqlite()->all(),$this->sqlite()->show_columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->sqlite()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->sqlite()->model()->empty());
    }

    /**
     * @throws Exception
     */

    public function test_update()
    {
        $data = [
            'id' => 6,
            'name' => faker()->name(),
            'age' => faker()->numberBetween(1,80),
            'sex' => rand(1,2) == 1 ? 'M': 'F',
            'status' => faker()->text(20),
            'date' => faker()->date()
        ];
        $this->assertTrue($this->mysql()->update_record(6,$data,[]));
        $this->assertTrue($this->postgresql()->update_record(6,$data,[]));
        $this->assertTrue($this->sqlite()->update_record(6,$data,[]));
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
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20),
                'date' => faker()->date()
            ];

            $this->assertTrue($this->mysql()->model()->insert($data,$this->table));
            $this->assertTrue($this->postgresql()->model()->insert($data,$this->table));
            $this->assertTrue($this->sqlite()->model()->insert($data,$this->table));
        }

        $this->assertCount($number,$this->mysql()->model()->all());
        $this->assertEquals($number,$this->mysql()->model()->count());

        $this->assertCount($number,$this->postgresql()->model()->all());
        $this->assertEquals($number,$this->postgresql()->model()->count());

        $this->assertCount($number,$this->sqlite()->model()->all());
        $this->assertEquals($number,$this->sqlite()->model()->count());
    }


    /**
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
        $x = new Connect(Connect::MYSQL,'',self::MYSQL_USER,self::MYSQL_PASS);
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::POSTGRESQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS);
        $this->assertInstanceOf($expected,$x->instance());


        $x = new Connect(Connect::SQLITE,'','','');
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::MYSQL,$this->base,self::MYSQL_USER,self::MYSQL_PASS);
        $this->assertInstanceOf($expected,$x->instance());

        $x = new Connect(Connect::POSTGRESQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS);
        $this->assertInstanceOf($expected,$x->instance());


        $x = new Connect(Connect::SQLITE,$this->base,'','');
        $this->assertInstanceOf ($expected,$x->instance());

        $this->expectException(Exception::class);

        $x = new Connect(Connect::POSTGRESQL,'',self::MYSQL_USER,self::MYSQL_PASS);
        $x->instance();
        $x = new Connect(Connect::MYSQL,'',self::POSTGRESQL_USER,self::POSTGRESQL_PASS);
        $x->instance();
        $x =  new Connect(Connect::POSTGRESQL,$this->base,self::MYSQL_USER,self::MYSQL_PASS);
        $x->instance();
        $x = new Connect(Connect::MYSQL,$this->base,self::POSTGRESQL_USER,self::POSTGRESQL_PASS);
        $x->instance();
    }
}