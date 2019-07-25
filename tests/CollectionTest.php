<?php


namespace Testing;


use Imperium\Testing\Unit;

class CollectionTest extends Unit
{

    public function test_empty()
    {
        $this->assertTrue($this->collect()->empty());
    }

    public function test_to_json()
    {
        $this->assertEquals('{}',$this->collect()->json());
    }

    public function test_get()
    {
        $this->assertEquals(1,$this->collect([1])->first());
        $this->assertNotEmpty($this->collect([1])->keys());
        $this->assertNotEmpty($this->collect([1])->values());
        $this->assertEquals(1,$this->collect([1])->last());
        $this->assertEquals(2,$this->collect([1,2])->last());
        $this->assertEquals(1,$this->collect([1])->get(0));
        $this->assertEquals('',$this->collect([1])->get(1));
    }

    public function test_set()
    {
        $this->assertEquals(1,$this->collect()->set(1,2)->first());
        $this->assertEquals(2,$this->collect()->set(1,2)->last());
    }

    public function test_remove()
    {
        $data = $this->collect()->set(1,2,3,4)->put('linux','is a super os')->put('windows','is null');

        $this->assertTrue($this->collect($data->all())->del(1,2,3,4,'windows','is a super os')->empty());
    }

    public function test_ok()
    {
        $this->assertTrue($this->collect()->ok());
        $this->assertTrue($this->collect(['true','a','b','25'])->ok());
    }

    public function test_clear()
    {
        $this->assertTrue($this->collect()->set('a','b','c')->clear()->empty());
    }

    public function test_count()
    {
        $this->assertTrue($this->collect()->set('a','b','c')->count()->not_exist(2));
        $this->assertTrue($this->collect()->set('a','a','b','c')->count()->exist(2));
    }

    public function test_refresh()
    {
        $this->assertEquals('Linux',$this->collect()->set('Windows')->refresh('Windows','Linux')->first());
        $this->assertEquals('ArchLinux',$this->collect()->set('Linux Mint','Ubuntu','Debian','Red hat','Fedora')->refresh('Red hat','ArchLinux')->get(3));
    }
    public function test_length()
    {
        $this->assertEquals(10,$this->collect()->set(1,2,2,3,5,1,5,4,5,6)->sum());
    }

    public function test_stack()
    {
        $this->assertEquals([6,5,4,3,2,1],$this->collect()->stack(1,2,3,4,5,6)->all());
    }
    public function test_push()
    {
        
        $this->assertEquals([1,2,3,4,5,6],$this->collect()->push(1,2,3,4,5,6)->all());
    }

    public function test_uniq()
    {
        $this->assertEquals([1,2,3,4,5,6],$this->collect()->uniq(1,2,3,4,5,6,1,2,3,4,5,6,6,5,4,3,2,1)->all());
    }

    public function test_diff()
    {
        $this->assertNotEmpty($this->collect(['willy'])->diff([1,2,3,4,5,6])->all());
    }

    public function test_slice()
    {
        $this->assertEquals([4,5,6,7,8,9],$this->collect([1,2,3,4,5,6,7,8,9])->slice(3)->all());
        $this->assertEquals([4,5],$this->collect([1,2,3,4,5,6,7,8,9])->slice(3,2)->all());
    }

    public function test_merge()
    {
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10],$this->collect()->merge([1,2,3,4,5],[6,7],[8],[9,10])->all());
    }
    public function test_chunk()
    {
        $this->assertEquals([0 =>[1,2,3],1=> [4,5,6],2=> [7,8,9]],$this->collect([1,2,3,4,5,6,7,8,9])->chunk(3)->all());
        $this->assertEquals([0 =>[1,2],1=> [3,4],2=> [5,6],3=>[7,8],4=> [9]],$this->collect([1,2,3,4,5,6,7,8,9])->chunk(2)->all());
    }
    public function test_only()
    {
        $this->assertEquals([['username' => 'willy'],['username' => 'bob']],$this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->only('username')->all());
        $this->assertEquals('0,1',$this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->join_keys());
        $this->assertFalse($this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->contains('alexandra'));
        $this->assertTrue($this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->not_contains('alexandra'));
        $this->assertTrue($this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->only('username')->contains('willy','bob'));
        $this->assertFalse($this->collect()->push(['id'=> 1,'username' => 'willy'],['id'=> 2,'username' => 'bob'])->only('username')->contains('MARC','marion'));
        $this->assertEquals(['username' => 'willy'],$this->collect(['id'=> 1,'username' => 'willy'])->only('username')->all());

    }

    public function test_value_before_key()
    {
        $x = $this->collect([1,2,3,4,5,6,7,8,9,10]);
        $this->assertEquals(2,$x->value_before_key(2));
        $this->assertEquals('',$x->value_before_key(5002));

    }
    public function test_each()
    {
        $this->assertEquals('linux-is-a-super-operating-system',$this->collect(['LINUX','iS','A','sUPEr','OperatinG','SyStem'])->each([$this,'for_all'])->join('-'));
    }

    public function test_pop_end_shift()
    {
        $this->assertEquals([1,2,3,4,5],$this->collect()->set(1,2,3,4,5,6)->pop()->all());
        $this->assertEquals([2,3,4,5,6],$this->collect()->set(1,2,3,4,5,6)->shift()->all());
    }

    public function test_reverse()
    {
        $this->assertEquals([6,5,4,3,2,1,0],$this->collect([0,1,2,3,4,5,6])->reverse()->all());
    }

    public function for_all($param): string
    {
        return strtolower($param);
    }
}