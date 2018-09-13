<?php
/**
 * Created by PhpStorm.
 * User: fumse
 * Date: 03/09/2018
 * Time: 17:07
 */

namespace tests;


use Imperium\Collection\Collection;
use Testing\DatabaseTest;

class CollectionTest extends DatabaseTest
{


    public function test_instance()
    {
        $data = [];
        $this->assertInstanceOf(Collection::class,collection());
        $this->assertInstanceOf(Collection::class,collection($data));
        $this->assertInstanceOf(Collection::class,collection(array('1',2,3,3,5)));
    }

    public function test_push()
    {
        $data = collection();

        $data->push(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],$data->getCollection());
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],$data->getCollection());
    }
    public function test_stack()
    {
        $data = collection();

        $data->stack(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
        $this->assertEquals([20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1],$data->getCollection());

    }

    public function test_init()
    {
        $data = collection();
        $this->assertEquals(0,$data->init());

    }

    public function test_end()
    {
        $data = collection([1,2,3]);

        $this->assertEquals(3,$data->end());
    }

    public function test_key()
    {
        $data = collection([0 => 5]);
        $data->rewind();
        $this->assertEquals(0,$data->key());
        $this->assertEquals(5,$data->current());
    }

    public function test_start()
    {
        $data = collection([1,2,3]);

        $this->assertEquals(1,$data->start());
    }

    public function test_length()
    {
        $data = collection();
        $this->assertEquals(0,$data->length());

        $data->push('123','124',50);
        $this->assertEquals(3,$data->length());

    }

    public function test_set()
    {
        $data = collection();

        $data->set('alex')->set('marc')->set('marion');

        $this->assertEquals(['alex','marc','marion'],$data->getCollection());

        $data =  collection();

        $data->set(10,'note')->set(15,'age')->set(25 ,'euros');

        $this->assertEquals(['note' => 10,'age' => 15,'euros' => 25] ,$data->getCollection());

    }

    public function test_is()
    {
        $data =  collection();

        $this->assertTrue($data->numeric('1'));
        $this->assertTrue($data->numeric(12));
        $this->assertTrue($data->string("azadz"));
        $this->assertTrue($data->string("lerfzz"));
        $this->assertTrue($data->empty());
    }

    public function test_get()
    {
        $data =  collection();

        $data->set(10,'note')->set(15,'age')->set(25 ,'euros');

        $this->assertEquals(10,$data->get('note'));
        $this->assertEquals(15,$data->get('age'));
        $this->assertEquals(25,$data->get('euros'));
    }

    public function test_remove()
    {
        $data =  collection();

        $data->set(10,'note')->set(15,'age')->set(25 ,'euros');

        $data->remove('note');
        $this->assertNotContains('note',$data->getCollection());
    }

    public function test_join()
    {
        $data =  collection();
        $data->push('i am a boy','you a little girl');
        $this->assertEquals('i am a boy, you a little girl',$data->join(', '));

        $data->clear()->push('i am a boy','you a little girl');
        $this->assertEquals('i am a girl, you a little girl',$data->join(', ',true,'boy','girl'));
    }

    public function test_get_values()
    {
        $data = collection();
        $data->set('i','a')->set('am','b')->set('a','c')->set('boy','d');
        $this->assertEquals(['i','am','a','boy'],$data->getValues());
        $this->assertEquals(['a','b','c','d'],$data->getKeys());
    }

    public function test_for()
    {
        $data = collection(array('i','have','a','dog','and','i','eat','a','big','sandwich'));
        $data->rewind();
        while ($data->valid())
        {
            $this->assertNotEmpty($data->current());
            $data->next();
        }

    }
    public function test_array_prev()
    {
        $data = collection(['note' =>10 ,'age' => 18,'phone' => 564]);
        $this->assertEquals(10,$data->valueBeforeKey('age'));
        $this->assertEquals(18,$data->valueBeforeKey('phone'));

        $data = collection([10 , 18, 564]);
        $this->assertEquals(10,$data->valueBeforeKey(18));
        $this->assertEquals(18,$data->valueBeforeKey(564));
        $data = collection([10]);
        $this->assertEquals(10,$data->valueBeforeKey(10));

    }

    public function test_reverse()
    {
        $data =  collection(['boy','a','am','i']);

        $this->assertEquals(['i','am','a','boy'],$data->reverse());
    }

    public function test_before_and_after()
    {
        $data = collection(['do','re','mi','fa','sol','la','si','do']);
        $data->rewind();

        $this->assertEquals('re',$data->after());

        $this->assertEquals('mi',$data->after());

        $this->assertEquals('fa',$data->after());

        $this->assertEquals('mi',$data->before());

        $this->assertEquals('re',$data->before());


        $this->assertEquals('do',$data->before());
        $data->next();
        $data->next();
        $data->next();
        $data->next();
        $this->assertEquals('sol',$data->current());

    }

    /**
     * @throws \Exception
     */
    public function test_print()
    {
        $data = collection($this->get_mysql()->model()->all());

        $this->assertNotEmpty($data->print(true,$this->get_mysql()->model()->columns()));
        $this->assertNotEmpty($data->print(true));
        $this->assertNotEmpty($data->print(false,$this->get_mysql()->model()->columns(),true));
        $this->assertNotEmpty($data->print(false,[],true));

        $data = collection( $this->get_pgsql()->model()->all());
        $this->assertNotEmpty($data->print(true,$this->get_pgsql()->model()->columns()));
        $this->assertNotEmpty($data->print(true));
        $this->assertNotEmpty($data->print(false,$this->get_pgsql()->model()->columns(),true));
        $this->assertNotEmpty($data->print(false,[],true));

        $data = \collection($this->get_sqlite()->model()->all());

        $this->assertNotEmpty($data->print(true,$this->get_sqlite()->model()->columns()));
        $this->assertNotEmpty($data->print(true));
        $this->assertNotEmpty($data->print(false,$this->get_sqlite()->model()->columns(),true));
        $this->assertNotEmpty($data->print(false,[],true));

    }

    public function test()
    {

        $data = \collection();
        $data->offsetSet('name','willy');
        $this->assertEquals('name',$data->offsetGet('willy'));
        $this->assertEquals(true,$data->offsetExists('willy'));
        $data->offsetUnset('willy');
        $this->assertEquals(false,$data->offsetExists('willy'));

        $this->assertEquals('',$data->offsetGet('willy'));

    }
}