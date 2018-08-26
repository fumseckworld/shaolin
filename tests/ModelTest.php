<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 24/08/2018
 * Time: 11:34
 */

namespace tests;


use Exception;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Model\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{

    /**
     * @var Model
     */
    private $model;

    public function setUp()
    {
        $pdo = connect(Connexion::MYSQL,'zen','root','root');
        $i = table(Connexion::MYSQL,'zen','root','root','dump');
        $table = 'doctors';

        $this->model = new Model($pdo,$i,$table);
    }

    /**
     * @throws Exception
     */
    public function testException()
    {
        $this->expectException(Exception::class);
        $this->model->findOrFail(0);
        $this->model->findOrFail(800);
        $this->model->findOrFail(500);
    }

    /**
     * @throws Exception
     */
    public function testFind()
    {

        $this->assertNotEmpty($this->model->find(1));
        $this->assertNotEmpty($this->model->find(5));

    }

    public function testShow()
    {
        $this->assertNotEmpty($this->model->showTables());
    }

    public function testWhere()
    {
        $this->assertNotEmpty($this->model->where('id','=',3));
        $this->assertNotEmpty($this->model->where('id','!=',3));
        $this->assertNotEmpty($this->model->where('id','>',3));
        $this->assertNotEmpty($this->model->where('id','<',3));
        $this->assertNotEmpty($this->model->where('id','<=',3));
        $this->assertNotEmpty($this->model->where('id','>=',3));

    }

    public function testAll()
    {
        $this->assertNotEmpty($this->model->all());
    }

    /**
     * @throws Exception
     */
    public function testFindOrFailSuccess()
    {

        $this->assertNotEmpty($this->model->findOrFail(1));
        $this->assertNotEmpty($this->model->findOrFail(52));
        $this->assertNotEmpty($this->model->findOrFail(8));
        $this->assertNotEmpty($this->model->findOrFail(18));
    }

    public function testDelete()
    {
        $this->assertTrue($this->model->delete(2));
        $this->assertTrue($this->model->delete(20));
    }
    public function testUpdate()
    {

            $data = [
                'id' => 20,
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20),
                'date' => faker()->date()];
        $this->assertTrue($this->model->update(20,$data));

    }

    public function testTruncate()
    {
        $this->assertTrue($this->model->truncate());
        $this->assertTrue($this->model->isEmpty());
    }


    public function testSave()
    {

        for ($i=0;$i!=200;$i++)
        {
            $data = [

                'id' => null,
                'name' => faker()->name(),
                'age' => faker()->numberBetween(1,80),
                'sex' => rand(1,2) == 1 ? 'M': 'F',
                'status' => faker()->text(20),
                'date' => faker()->date()];
            $this->assertTrue($this->model->save($data));
        }


    }
    public function testCount()
    {
        $this->assertEquals(200,$this->model->count());
        $this->assertFalse($this->model->isEmpty());
    }

    public function testGetColumns()
    {
        $columns = $this->model->getColumns();
        $this->assertContains('id',$columns);
        $this->assertContains('name',$columns);
        $this->assertContains('age',$columns);
        $this->assertContains('sex',$columns);
        $this->assertContains('status',$columns);
    }

    public function testModelInstance()
    {
        $pdo = connect(Connexion::MYSQL,'zen','root','root');
        $i = table(Connexion::MYSQL,'zen','root','root','dump');
        $table = 'doctors';

        $this->assertInstanceOf(Model::class, model($pdo,$i,$table));
        $this->assertInstanceOf(Model::class, new Model($pdo,$i,$table));
    }
}