<?php

namespace Testing\Collect;

use Imperium\Collection\Collect;
use Opis\Closure\SerializableClosure;
use PHPUnit\Framework\TestCase;
use stdClass;

class CollectTest extends TestCase
{
    public function testHelper()
    {
        $obj = new stdClass();
        $obj->id = 4;
        $obj->username = 'willy';

        $this->assertInstanceOf(Collect::class, collect());
        $this->assertInstanceOf(Collect::class, collect(['id' => 1]));
        $this->assertInstanceOf(Collect::class, collect($obj));
        $this->assertEquals(['id' => 4, 'username' => 'willy'], collect($obj)->all());
    }

    public function testCall()
    {
        $x = function (string $os) {
            return "$os is better";
        };
        $this->assertEquals('linux is better', collect()->addCallback('os', $x, ['linux'])->call('os'));
        $this->assertNull(\collect()->call('a'));
        $this->assertEquals('windows is better', collect()->addCallback('os', $x, ['windows'])->call('os'));
    }


    public function testObject()
    {
        $obj = new stdClass();
        $a = 2;
        $this->assertInstanceOf(stdClass::class, collect()->addObject('os', $obj)->getObject('os'));
        $this->assertEquals(2, collect()->put('disk', $a)->get('disk'));
    }

    public function testCollect()
    {
        $obj = new stdClass();
        $a = function () {
            return 0;
        };
        $b = function () {
            return 255;
        };
        $this->assertInstanceOf(Collect::class, \collect()->getCollect(uniqid()));
        $x = collect()->addCallback('success', $a)->addObject('class', $obj);
        $z = collect()->addCallback('char', $b)->addObject('obj', $obj)->addCollect('old', $x);
        $this->assertEquals(0, collect()->addCollect('data', $x)->getCollect('data')->call('success'));
        $this->assertEquals(255, collect()
            ->addCollect('old', $x)
            ->addCollect('new', $z)->getCollect('new')->call('char'));
        $this->assertInstanceOf(stdClass::class, collect()->addCollect('data', $x)
            ->getCollect('data')->getObject('class'));
    }

    public function testToJson()
    {
        $this->assertNotEmpty(collect(['a' => 3])->json());
    }
    public function testFor()
    {
        $this->assertEquals(
            ["a,", "b,"],
            collect(['a', 'b'])->for(fn ($x) => "$x,")->all()
        );
    }
    public function testEach()
    {
        $this->assertEquals(
            ['b' => 'bab', 'a' => 'aba'],
            collect(['a' => 'b', 'b' => 'a'])->each(fn ($a, $b) => "$a$b$a")->all()
        );
    }

    public function testOnly()
    {
        $data = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertEquals(['a' => 1], collect($data)->only('a')->all());
        $this->assertEquals(['a' => 1, 'c' => 3], collect($data)->only('a', 'c')->all());
    }
    public function testCount()
    {
        $data = ['a', 'b', 'b', 'c', 'c', 'c'];
        $this->assertEquals(
            ['a' => 1, 'b' => 2, 'c' => 3],
            collect($data)->count()->all()
        );
    }
    public function testPosition()
    {
        $data = ['a', 'b', 'b', 'c', 'c', 'c'];
        $x = [['a', 'b', 'c'], ['d', 'e', 'f'], ['g', 'h', 'i']];
        $this->assertEquals('b', collect($data)->position(2)->current());
        $this->assertEquals('b', collect($data)->position(2)->before()->current());
        $this->assertEquals('b', collect($data)->position(4)->before(3)->current());
        $this->assertEquals('c', collect($data)->position(1)->after(4)->current());
        $this->assertEquals(['d', 'e', 'f'], collect($x)->position(1)->current());
        $this->assertEquals(['a', 'b', 'c'], collect($x)->position(1)->before()->current());
        $this->assertEquals(['g', 'h', 'i'], collect($x)->after(2)->current());
    }

    public function testValues()
    {
        $this->assertEquals(['a', 'b'], collect(['m' => 'a', 'z' => 'b'])->values()->all());
        $this->assertEquals(['m', 'z'], collect(['m' => 'a', 'z' => 'b'])->keys()->all());
    }
    public function testJoin()
    {
        $this->assertEquals('a,b,c', collect(['a', 'b', 'c'])->join());
    }

    public function testMerge()
    {
        $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], collect([0, 1, 2, 3, 4])
            ->merge([5, 6, 7])->merge([8, 9])->all());
    }
    public function testSet()
    {
        $this->assertEquals(['a', 'b'], collect()->set('a')->set('b')->all());
    }

    public function testClear()
    {
        $this->assertEmpty(collect(['a', 'b', 'c'])->clear()->all());
    }

    public function testPop()
    {
        $this->assertEmpty(collect([0, 1, 2, 3])->pop(4)->all());
        $this->assertNotEmpty(collect([0, 1, 2, 3])->pop(3)->all());
    }
    public function testOk()
    {
        $this->assertTrue(collect()->ok());
        $this->assertFalse(collect([true, false, true])->ok());
    }
    public function testHas()
    {
        $this->assertFalse(\collect()->has('a'));
        $this->assertTrue(\collect()->hasNot('a'));
        $this->assertTrue(\collect(['a' => 0])->has('a'));
        $this->assertFalse(\collect(['a' => 0])->hasNot('a'));
    }

    public function testLoop()
    {
        $data = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];
        $x = collect($data);
        $x->rewind();
        while ($x->valid()) {
            $this->assertNotEmpty($x->current());
            $this->assertIsInt($x->key());
            $x->next();
        }
    }
    public function testSlice()
    {
        $data = ['a', 'b', 'b', 'c', 'c', 'c'];
        $this->assertEquals(['b', 'c', 'c', 'c'], collect($data)->slice(2)->all());
        $this->assertEquals(['b', 'c'], collect($data)->slice(2, 2)->all());
        $this->assertNotEquals(['b', 'c'], collect($data)->slice(2, 2, true)->all());
    }


    public function testChunk()
    {
        $data = ['a', 'b', 'b', 'c', 'c', 'c'];
        $this->assertEquals(
            [['b'], ['c', 'c']],
            collect($data)->slice(2)->chunk(2)->all()
        );
    }


    public function testDiff()
    {
        $data = ['a', 'b', 'b', 'c', 'c', 'c'];
        $this->assertNotEquals(
            $data,
            collect($data)->diff(['c' => "a"])->all()
        );
    }


    public function testLast()
    {
        $data = [0, 1, 2, 3, 4, 5];
        $this->assertEquals(
            5,
            collect($data)->last()
        );
    }

    public function testFirstAndLastComplete()
    {

        $data = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'A', 'B', 'C', 'D', 'E', 'F'];
        $this->assertEquals(['A', 'B', 'C', 'D', 'E', 'F'], collect($data)->chunk(10)->last());
        $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8, 9,], collect($data)->chunk(10)->first());
    }
    public function testFirst()
    {
        $data = [0, 1, 2, 3, 4, 5];
        $this->assertEquals(0, collect($data)->first());
        $this->assertEquals(0, collect($data)->firstKey());
        $this->assertEquals(5, collect($data)->last());
        $this->assertEquals(5, collect($data)->lastKey());
    }
    public function testReverse()
    {
        $this->assertEquals(['a', 'b', 'c'], collect(['c', 'b', 'a'])->reverse()->all());
    }

    public function testGet()
    {
        $this->assertEquals('metal', collect(['classic', 'rap', 'metal'])->get(2));
        $this->assertNull(collect(['classic', 'rap', 'metal'])->get(20));
    }

    public function testForget()
    {
        $a = \collect()->add('a')->add('b')->add('c');
        $this->assertTrue($a->has(0));
        $this->assertFalse($a->hasNot(0));
        $this->assertFalse($a->forget(0)->has(0));
        $this->assertTrue($a->forget(0)->hasNot(0));
    }
    public function testMaxAndMin()
    {
        $data = [1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5];
        $this->assertEquals(5, collect($data)->max());
        $this->assertEquals(1, collect($data)->min());
    }


    public function testTake()
    {
        $data = [1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5];
        $this->assertEquals([1, 2, 2], collect($data)->take(3)->all());
    }

    public function testStack()
    {
        $data = [0, 1, 2, 3, 4, 5];
        $this->assertEquals($data, collect()->stack(5, 4, 3, 2, 1, 0)->all());
    }

    public function testEmpty()
    {
        $this->assertTrue(collect()->empty());
        $this->assertFalse(collect(['a' => 'fire'])->empty());
    }

    public function testBeforeKey()
    {
        $data = ['one' => 1, 'two' => 2];
        $this->assertEmpty(collect()->beforeKey('a'));
        $this->assertEquals(1, collect($data)->beforeKey('two'));
    }
}
