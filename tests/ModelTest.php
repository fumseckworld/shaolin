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
use Imperium\Databases\Eloquent\Query\Query;
use PDO;
use Testing\DatabaseTest;

class ModelTest extends DatabaseTest
{
    /**
     * @throws \Exception
     */
    public function test_show_tables()
    {
        $this->assertContains($this->table,$this->get_mysql()->model()->show_tables());
        $this->assertContains($this->table,$this->get_pgsql()->model()->show_tables());
        $this->assertContains($this->table,$this->get_sqlite()->model()->show_tables());

        $this->assertContains($this->table,$this->get_mysql()->show_tables());
        $this->assertContains($this->table,$this->get_pgsql()->show_tables());
        $this->assertContains($this->table,$this->get_sqlite()->show_tables());


        $this->assertNotContains($this->table,$this->get_mysql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->get_pgsql()->show_tables([$this->table]));
        $this->assertNotContains($this->table,$this->get_sqlite()->show_tables([$this->table]));


    }

    /**
     * @throws Exception
     */
    public function test_request()
    {
        $table = $this->table;
        $this->assertNotEmpty($this->get_mysql()->model()->request('SHOW DATABASES'));
        $this->assertNotEmpty($this->get_pgsql()->model()->request("SELECT * FROM $table"));
        $this->assertNotEmpty($this->get_sqlite()->model()->request("SELECT * FROM $table"));
    }

    /**
    * @throws Exception
    */
    public function test_execute()
    {
        $table = $this->table;
        $this->assertTrue($this->get_mysql()->model()->execute('SHOW DATABASES'));
        $this->assertTrue($this->get_pgsql()->model()->execute("SELECT * FROM $table"));
        $this->assertTrue($this->get_sqlite()->model()->execute("SELECT * FROM $table"));
    }

    /**
     * @throws Exception
     */
    public function test_pdo()
    {
        $this->assertInstanceOf(PDO::class,$this->get_mysql()->pdo());
        $this->assertInstanceOf(PDO::class,$this->get_mysql()->model()->pdo());

        $this->assertInstanceOf(PDO::class,$this->get_pgsql()->pdo());
        $this->assertInstanceOf(PDO::class,$this->get_pgsql()->model()->pdo());

        $this->assertInstanceOf(PDO::class,$this->get_sqlite()->pdo());
        $this->assertInstanceOf(PDO::class,$this->get_sqlite()->model()->pdo());


    }
    /**
     * @throws \Exception
     */
    public function test_find()
    {

        $this->assertCount(1,$this->get_mysql()->find(10));
        $this->assertCount(1,$this->get_pgsql()->find(10));
        $this->assertCount(1,$this->get_sqlite()->find(10));

    }

    /**
     * @throws \Exception
     */
    public function test_find_or_fail()
    {

        $this->assertCount(1,$this->get_mysql()->findOrFail(10));
        $this->assertCount(1,$this->get_pgsql()->findOrFail(10));
        $this->assertCount(1,$this->get_sqlite()->findOrFail(10));

        $this->expectException(Exception::class);
        $this->get_mysql()->findOrFail(800);
        $this->get_pgsql()->findOrFail(800);
        $this->get_sqlite()->findOrFail(800);

    }

    /**
     * @throws Exception
     */
    public function test_remove()
    {
        $this->assertTrue($this->get_mysql()->remove(4));
        $this->assertTrue($this->get_pgsql()->remove(4));
        $this->assertTrue($this->get_sqlite()->remove(4));
    }

    /**
     * @throws Exception
     */
    public function test_truncate()
    {
        $empty = 'the table is empty';
        $success = 'records was found';

        $this->assertTrue($this->get_mysql()->model()->truncate());
        $result = query_result($this->get_mysql()->model(),Query::SELECT,$this->get_mysql()->records(),$this->get_mysql()->columns(),$success,$empty,$empty);

        $this->assertCount(0,$this->get_mysql()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->get_mysql()->model()->empty());

        $this->assertTrue($this->get_pgsql()->model()->truncate());
        $result = query_result($this->get_pgsql()->model(),Query::SELECT,$this->get_pgsql()->records(),$this->get_pgsql()->columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->get_pgsql()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->get_pgsql()->model()->empty());

        $this->assertTrue($this->get_sqlite()->model()->truncate());
        $result = query_result($this->get_sqlite()->model(),Query::SELECT,$this->get_sqlite()->records(),$this->get_sqlite()->columns(),$success,$empty,$empty);
        $this->assertCount(0,$this->get_sqlite()->model()->all());
        $this->assertContains($empty,$result);
        $this->assertTrue( $this->get_sqlite()->model()->empty());
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
        $this->assertTrue($this->get_mysql()->update(6,$data));
        $this->assertTrue($this->get_pgsql()->update(6,$data));
        $this->assertTrue($this->get_sqlite()->update(6,$data));
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

            $this->assertTrue($this->get_mysql()->model()->insert($data,$this->table));
            $this->assertTrue($this->get_pgsql()->model()->insert($data,$this->table));
            $this->assertTrue($this->get_sqlite()->model()->insert($data,$this->table));
        }

        $this->assertCount($number,$this->get_mysql()->model()->all());
        $this->assertEquals($number,$this->get_mysql()->model()->count());

        $this->assertCount($number,$this->get_pgsql()->model()->all());
        $this->assertEquals($number,$this->get_pgsql()->model()->count());

        $this->assertCount($number,$this->get_sqlite()->model()->all());
        $this->assertEquals($number,$this->get_sqlite()->model()->count());
    }


    /**
     * @throws Exception
     */
    public function test_driver()
    {
        $this->assertTrue($this->get_mysql()->connect()->mysql());
        $this->assertTrue($this->get_pgsql()->connect()->postgresql());
        $this->assertTrue($this->get_sqlite()->connect()->sqlite());

        $this->assertFalse($this->get_mysql()->connect()->postgresql());
        $this->assertFalse($this->get_pgsql()->connect()->sqlite());
        $this->assertFalse($this->get_sqlite()->connect()->mysql());
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