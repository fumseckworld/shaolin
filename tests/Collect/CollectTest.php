<?php

namespace Testing\Collect;

use Imperium\Collection\Collect;
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
    }
    public function testMaxAndMin()
    {
        $data = [1, 2, 2, 3, 3, 3, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5];
        $this->assertEquals(5, collect($data)->max());
        $this->assertEquals(1, collect($data)->min());
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
