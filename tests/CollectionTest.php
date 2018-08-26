<?php



namespace tests;

use Imperium\Collection\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{


    public function testInstance()
    {
        $this->assertInstanceOf(Collection::class,collection());
        $this->assertInstanceOf(Collection::class,collection(['a','b']));
    }

    public function testPush()
    {
        $data = ['a','b'];
        $collection = new Collection($data);
        $collection->push('c','d','e','f');
        $this->assertEquals(['a','b','c','d','e','f'],$collection->getCollection()) ;
    }
    public function testCreation()
    {
        $data = collection();

        for ($i=0;$i<10;$i++)
        {
            $data->push($i);

        }
        $this->assertEquals(10,$data->length());
        $this->assertEquals([0,1,2,3,4,5,6,7,8,9],$data->getCollection());
    }

    public function testCurrent()
    {
        $data = ['a','b','c','d','e','f'];
        $collection = new Collection($data);
        $collection->rewind();
        while ($collection->valid())
        {
            $this->assertNotEmpty($collection->current());

            $collection->next();
            $this->assertGreaterThan(0,$collection->key());
        }
    }
    public  function testPop()
    {
        $data = ['a','b','c','d','e','f'];
        $collection = new Collection($data);
        $collection->pop()->pop()->pop();
        $this->assertEquals(['a','b','c'],$collection->getCollection()) ;
    }
   public  function testGetCollection()
    {
        $data = ['a','b','c','d','e','f'];
        $collection = new Collection($data);

        $this->assertNotEmpty($collection->getCollection());
        $this->assertEquals($data,$collection->getCollection()) ;
    }

    public function testMerge()
    {
        $data = ['a','b','c','d','e','f'];
        $second = ['g','h','i'];
        $three = ['j','k','l'];
        $collection = new Collection($data);
        $collection->merge($second,$three);

        $this->assertEquals( ['a','b','c','d','e','f','g','h','i','j','k','l'],$collection->getCollection());
    }

    public function testEndAndStart()
    {
        $data = ['a','b','c','d','e','f'];

        $collection = new Collection($data);

        $this->assertEquals('f',$collection->end());
        $this->assertEquals('a',$collection->start());
    }

    public function testGetTotal()
    {
        $data = ['a','b','c','d','e','f'];

        $collection = new Collection($data);

        $this->assertEquals(6,$collection->length());
        $collection->merge($data);
        $this->assertEquals(12,$collection->length());
        $collection->pop();

        $this->assertEquals(11,$collection->length());

    }

    public function testSet()
    {
        $data = ['a','b','c','d','e','f'];

        $collection = new Collection($data);
        $collection->set('g')->set('h');
        $this->assertEquals( ['a','b','c','d','e','f','g','h'],$collection->getCollection());

        $collection->offsetSet('i','a');
        $collection->offsetSet('j','b');
        $collection->offsetSet('k','c');
        $collection->offsetSet('l','d');
        $this->assertEquals( ['a','b','c','d','e','f','g','h','a' => 'i','b' => 'j','c'=> 'k','d'=> 'l'],$collection->getCollection());

    }

    public function testGetValues()
    {
        $data = [0=> 'a', 1=>'b', 2=>'c', 3=>'d',4 =>'e',5 => 'f'];

        $collection = new Collection($data);
        $this->assertEquals(5,$collection->lastKey());
        $this->assertEquals('f',$collection->lastValue());
        $this->assertEquals(0,$collection->firstKey());
        $this->assertEquals('a',$collection->firstValue());
    }


    public function testReverse()
    {
        $data = [1,2,3];
        $collection = new Collection($data);
        $this->assertEquals([3,2,1],$collection->reverse());
    }

    public function testHasAndExist()
    {
        $data = [1 => 4 ,2 => 6,3 => 12];
        $collection = new Collection($data);
        $this->assertTrue($collection->exist(4));
        $this->assertTrue($collection->exist(6));
        $this->assertFalse($collection->exist(60));
        $this->assertFalse($collection->exist(70));
        $this->assertTrue($collection->has(1));
        $this->assertTrue($collection->offsetExists(1));
        $this->assertTrue($collection->has(2));
        $this->assertTrue($collection->offsetExists(2));
        $this->assertTrue($collection->offsetExists(3));
        $this->assertFalse($collection->has(325));
        $this->assertFalse($collection->has(3245));
        $this->assertFalse($collection->has(3245));

    }


    public function testExist()
    {

        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertTrue($collection->exist('lundi'));
        $this->assertTrue($collection->exist('mardi'));
        $this->assertTrue($collection->exist('mercredi'));
        $this->assertFalse($collection->exist('a'));
        $this->assertFalse($collection->exist('ad'));
        $this->assertFalse($collection->exist('aad'));

    }
    public function testHas()
    {
        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);

        $this->assertEquals(false,$collection->has('lundi'));
        $this->assertEquals(false,$collection->has('mardi'));
        $this->assertEquals(false,$collection->has('mercredi'));

        $data = ['lundi' => 4,'mardi' => 5,'mercredi' => 6];

        $collection =  new Collection($data);
        $this->assertEquals(true,$collection->has('lundi'));
        $this->assertEquals(true,$collection->has('mardi'));
        $this->assertEquals(true,$collection->has('mercredi'));
    }

    public function testGet()
    {
        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertEquals(null,$collection->get('a'));
        $this->assertEquals(null,$collection->get('b'));
        $this->assertEquals('lundi',$collection->get(0));

        $this->assertEquals(null,$collection->offsetGet('a'));
        $this->assertEquals(null,$collection->offsetGet('b'));
        $this->assertEquals('lundi',$collection->offsetGet(0));
    }
    public function testUnset()
    {
        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertEquals(false,$collection->unset(0)->exist('lundi'));

        $collection->offsetUnset(1);
        $this->assertEquals(false,$collection->exist('mardi'));

    }


    public function testClear()
    {
        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertEquals([],$collection->clear()->getCollection());
    }
    public function testSearch()
    {
        $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertEquals(0,$collection->search('lundi'));
        $this->assertEquals(0,$collection->search('lun'));
        $this->assertEquals(1,$collection->search('mardi'));

    }

    public function testJoin()
    {  $data = ['lundi','mardi','mercredi'];

        $collection =  new Collection($data);
        $this->assertEquals('lundi, mardi, mercredi',$collection->join(', '));
    }
    public function testBeforeAndAfter()
    {
        $data = ['lundi','mardi','mercredi'];

       $collection =  new Collection($data);
       $this->assertEquals('mardi',$collection->after());
       $this->assertEquals('mercredi',$collection->after());
       $this->assertEquals('mardi',$collection->before());
       $this->assertEquals('lundi',$collection->before());
    }
    public function testInit()
    {

        $collection = new Collection();
        $this->assertEquals(0,$collection->init());

    }
    public function testBeforeAKey()
    {
        $data = [1 => 4 ,2 => 6,3 => 12];
        $collection = new Collection($data);

        $this->assertEquals(6,$collection->valueBeforeKey(3));
        $this->assertEquals(4,$collection->valueBeforeKey(2));

    }
}