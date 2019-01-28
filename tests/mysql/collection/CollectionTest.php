<?php


namespace tests\collection;


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

    public function test_convert_to_json()
    {
        $this->assertTrue(collection($this->mysql()->show_databases())->convert_to_json('app.json'));

        $this->assertTrue(collection($this->mysql()->show_databases())->convert_to_json('app.json','bases'));
    }

    public function test_remove_values()
    {
        $data = [1,2,3,4,5,6,7,8,9];
        $this->assertNotContains('8',collection($data)->remove_values(8)->collection());
        $this->assertNotContains('9',collection($data)->remove_values(9)->collection());
    }

    /**
     * @throws \Exception
     */
    public function test_change_value()
    {
        $data = [1,2,3,4,5,6,7,8,9];
        $this->assertNotContains('9',collection($data)->change_value(9,10));
    }

    public function test_push()
    {
        $data = collection();

        $data->push(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],$data->collection());
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],$data->collection());
    }
    public function test_stack()
    {
        $data = collection();

        $data->stack(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
        $this->assertEquals([20,19,18,17,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1],$data->collection());

    }

    public function test_init()
    {
        $data = collection();
        $this->assertEquals(0,$data->init());

    }


    public function test_end()
    {
        $data = collection([1,2,3]);

        $this->assertEquals(3,$data->last());
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

        $this->assertEquals(1,$data->begin());
    }

    public function test_length()
    {
        $data = collection();
        $this->assertEquals(0,$data->length());

        $data->push('123','124',50);
        $this->assertEquals(3,$data->length());

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

        $data->add(10,'note')->add(15,'age')->add(25 ,'euros');

        $this->assertEquals(10,$data->get('note'));
        $this->assertEquals(15,$data->get('age'));
        $this->assertEquals(25,$data->get('euros'));
    }

    public function test_remove()
    {
        $data =  collection();

        $data->add(10,'note')->add(15,'age')->add(25 ,'euros');

        $data->remove('note');
        $this->assertNotContains('note',$data->collection());
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
        $data->add('i','a')->add('am','b')->add('a','c')->add('boy','d');
        $this->assertEquals(['i','am','a','boy'],$data->values());
        $this->assertEquals(['a','b','c','d'],$data->keys());
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
        $this->assertEquals(10,$data->value_before_key('age'));
        $this->assertEquals(18,$data->value_before_key('phone'));

        $data = collection([10 , 18, 564]);
        $this->assertEquals(10,$data->value_before_key(18));
        $this->assertEquals(18,$data->value_before_key(564));
        $data = collection([10]);
        $this->assertEquals(10,$data->value_before_key(10));

    }

    public function test_reverse()
    {
        $data =  collection(['boy','a','am','i']);

        $this->assertEquals(['i','am','a','boy'],$data->reverse());
    }

    public function test_each()
    {
        $data =  collection(['boy','a','am','i']);

        $this->assertEquals(['BOY','A','AM','I'],$data->each('strtoupper')->collection());
        $this->assertEquals(['boy','a','am','i'],$data->each('strtoupper')->each('strtolower')->collection());
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
        $m = $this->mysql()->model()->from(current_table());
        $data = collection($m->all());

        $this->assertNotEmpty($data->print(true,$m->columns()));
        $this->assertNotEmpty($data->print(true));
        $this->assertNotEmpty($data->print(false,$m->columns(),true));
        $this->assertNotEmpty($data->print(false,[],true));
        $this->assertNotEmpty($data->print(false,[],false,'<header>','</header>','<h1>','text-uppercase','<hr>'));

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