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
			$this->assertEquals('{}', $this->collect()->json());
		}
		public function test_keys()
		{
			$this->assertEquals('id',$this->collect(['id'=> 4,'name' => 'willy'])->first_key());
			$this->assertEquals('name',$this->collect(['id'=> 4,'name' => 'willy'])->last_key());
		}
		public function test_get()
		{
			$this->assertEquals(1, $this->collect([ 1 ])->first());
			$this->assertNotEmpty($this->collect([ 1 ])->keys());
			$this->assertNotEmpty($this->collect([ 1 ])->values());
			$this->assertEquals(1, $this->collect([ 1 ])->last());
			$this->assertEquals(2, $this->collect([ 1, 2 ])->last());
			$this->assertEquals(1, $this->collect([ 1 ])->get(0));
			$this->assertEquals('', $this->collect([ 1 ])->get(1));
		}
		
		public function test_set()
		{
			$this->assertEquals(1, $this->collect()->set(1, 2)->first());
			$this->assertEquals(2, $this->collect()->set(1, 2)->last());
		}
		
		public function test_remove()
		{
			$data = $this->collect()->set(1, 2, 3, 4)->put('linux', 'is a super os')->put('windows', 'is null');
			$this->assertTrue($this->collect($data->all())->del(1, 2, 3, 4, 'windows', 'is a super os')->empty());
		}
		
		public function test_ok()
		{
			$this->assertTrue($this->collect()->ok());
			$this->assertTrue($this->collect([ 'true', 'a', 'b', '25' ])->ok());
		}
		
		public function test_clear()
		{
			$this->assertTrue($this->collect()->set('a', 'b', 'c')->clear()->empty());
		}
		
		public function test_count()
		{
			$this->assertTrue($this->collect()->set('a', 'b', 'c')->count()->not_exist(2));
			$this->assertTrue($this->collect()->set('a', 'a', 'b', 'c')->count()->exist(2));
		}
		
		public function test_refresh()
		{
			$this->assertEquals('Linux', $this->collect()->set('Windows')->refresh('Windows', 'Linux')->first());
			$this->assertEquals('ArchLinux', $this->collect()->set('Linux Mint', 'Ubuntu', 'Debian', 'Red hat', 'Fedora')->refresh('Red hat', 'ArchLinux')->get(3));
		}
		
		public function test_length()
		{
			$this->assertEquals(10, $this->collect()->set(1, 2, 2, 3, 5, 1, 5, 4, 5, 6)->sum());
		}
		
		public function test_stack()
		{
			$this->assertEquals([ 6, 5, 4, 3, 2, 1 ], $this->collect()->stack(1, 2, 3, 4, 5, 6)->all());
		}
		
		public function test_push()
		{
			$this->assertEquals([ 1, 2, 3, 4, 5, 6 ], $this->collect()->push(1, 2, 3, 4, 5, 6)->all());
		}
		
		public function test_uniq()
		{
			$this->assertEquals([ 1, 2, 3, 4, 5, 6 ], $this->collect()->uniq(1, 2, 3, 4, 5, 6, 1, 2, 3, 4, 5, 6, 6, 5, 4, 3, 2, 1)->all());
		}
		
		public function test_diff()
		{
			$this->assertNotEmpty($this->collect([ 'willy' ])->diff([ 1, 2, 3, 4, 5, 6 ])->all());
		}
		
		public function test_slice()
		{
			$this->assertEquals([ 4, 5, 6, 7, 8, 9 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->slice(3)->all());
			$this->assertEquals([ 4, 5 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->slice(3, 2)->all());
		}
		
		public function test_merge()
		{
			$this->assertEquals([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ], $this->collect()->merge([ 1, 2, 3, 4, 5 ], [ 6, 7 ], [ 8 ], [ 9, 10 ])->all());
		}
		
		public function test_has_not()
		{
			$this->assertTrue($this->collect()->put('os', 'Linux')->has_not('operating_system'));
			$this->assertFalse($this->collect()->put('os', 'Linux')->has_not('os'));
		}
		
		public function test_chunk()
		{
			$this->assertEquals([ 0 => [ 1, 2, 3 ], 1 => [ 4, 5, 6 ], 2 => [ 7, 8, 9 ] ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->chunk(3)->all());
			$this->assertEquals([ 0 => [ 1, 2 ], 1 => [ 3, 4 ], 2 => [ 5, 6 ], 3 => [ 7, 8 ], 4 => [ 9 ] ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->chunk(2)->all());
		}
		
		public function test_only()
		{
			$this->assertEquals([ [ 'username' => 'willy' ], [ 'username' => 'bob' ] ], $this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->only('username')->all());
			$this->assertEquals('0,1', $this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->join_keys());
			$this->assertFalse($this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->contains('alexandra'));
			$this->assertTrue($this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->not_contains('alexandra'));
			$this->assertTrue($this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->only('username')->contains('willy', 'bob'));
			$this->assertFalse($this->collect()->push([ 'id' => 1, 'username' => 'willy' ], [ 'id' => 2, 'username' => 'bob' ])->only('username')->contains('MARC', 'marion'));
			$this->assertEquals([ 'username' => 'willy' ], $this->collect([ 'id' => 1, 'username' => 'willy' ])->only('username')->all());
		}
		
		public function test_value_before_key()
		{
			$x = $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ]);
			$this->assertEquals(2, $x->value_before_key(2));
			$this->assertEquals('', $x->value_before_key(5002));
		}
		
		public function test_each()
		{
			$this->assertEquals('linux-is-a-super-operating-system', $this->collect([ 'LINUX', 'iS', 'A', 'sUPEr', 'OperatinG', 'SyStem' ])->for([ $this, 'for_all' ])->join('-'));
			$this->assertEquals('linux-is-a-super-operating-system', $this->collect([ 'LINUX', 'iS', 'A', 'sUPEr', 'OperatinG', 'SyStem' ])->each([ $this, 'foreach_all' ])->join('-'));
		}
		
		public function test()
		{
			$data = $this->collect([ 'a', 'b', 'c', 'd', 'e', 'f' ]);
			$data->rewind();
			do
			{
				$this->assertNotEmpty($data->current());
				$data->next();
				$this->assertNotEmpty($data->before());
				$this->assertIsInt($data->key());
				$this->assertNotEmpty($data->after());
				$data->next();
			}while($data->valid());
		}
		
		public function test_init()
		{
			$this->assertEquals(['id' => 'NULL','a' => 'NULL','b' => 'NULL'],$this->collect(['id' => 4,'a'  => 'alex' ,'b'=> 'mail' ])->init('NULL')->all());
		}
		
		public function test_combine()
		{
			$a = array('green', 'red', 'yellow');
			$b = array('avocado', 'apple', 'banana');
			$this->assertEquals(['green' => 'avocado','red' => 'apple','yellow' => 'banana'],$this->collect()->combine($a,$b)->all());
		}
		public function test_intersect()
		{
			$this->assertNotEmpty($this->collect()->put('name', 'willy')->put('a', 'b')->intersect($this->collect()->put('name', 'willy')->all())->all());
			$this->assertEmpty($this->collect()->put('name', 'willy')->put('a', 'b')->intersect($this->collect()->put('x', 'z')->all())->all());
		}
	
		public function test_exec()
		{
			$this->assertTrue($this->collect(['a','b','c'])->exec([$this,'exec_call']));
		}
		
		
		public function test_replace()
		{
			$this->assertEquals([ 'Willy Micieli', 'a', 'b' ], $this->collect()->set('willy', 'a', 'b')->replace([ 0 => 'Willy Micieli' ])->all());
		}
		
		public function test_unique()
		{
			$this->assertEquals([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10 ])->unique()->all());
		}
		
		public function test_map()
		{
			$this->assertEquals([ 1, 8, 27, 64, 125 ], $this->collect([ 1, 2, 3, 4, 5 ])->map([ $this, 'cube' ])->all());
		}
		
		public function test_product()
		{
			$this->assertEquals(6, $this->collect([ 1, 2, 3 ])->product());
		}
		
		public function test_display()
		{
			$this->assertEquals([ 3, 4, 5 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->display(2, 3)->all());
			$this->assertEquals([ 2, 3, 4 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->display(1, 3)->all());
			$this->assertEquals([ 2, 3, 4, 5, 6, 7 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->display(1, 6)->all());
		}
		
		public function test_range()
		{
			$this->assertEquals([ 1, 2, 3 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9 ])->range(1, 3)->all());
		}
		
		public function test_filter()
		{
			$this->assertEquals([ 10 => 11, 11 => 12, 12 => 15, 13 => 17 ], $this->collect([ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 15, 17 ])->filter([ $this, 'notes' ])->all());
		}
		
		public function test_take()
		{
			$this->assertEquals([ 'a', 'b', 'd', 'c', 'e' ], $this->collect([ 'a', 'b', 'd', 'c', 'e', 'f', 'g', 'h', 'k', 'j' ])->take(5)->all());
		}
		
		public function test_forget()
		{
			$this->assertEquals([ 'name' => 'willy micieli' ], $this->collect([ 'id' => 1, 'name' => 'willy micieli' ])->forget('id')->all());
		}
		
		public function test_pop_end_shift()
		{
			$this->assertEquals([ 1, 2, 3, 4, 5 ], $this->collect()->set(1, 2, 3, 4, 5, 6)->pop()->all());
			$this->assertEquals([ 2, 3, 4, 5, 6 ], $this->collect()->set(1, 2, 3, 4, 5, 6)->shift()->all());
		}
		
		public function test_reverse()
		{
			$this->assertEquals([ 6, 5, 4, 3, 2, 1, 0 ], $this->collect([ 0, 1, 2, 3, 4, 5, 6 ])->reverse()->all());
		}
		
		public function foreach_all($k, $param) : string
		{
			return strtolower($param);
		}
		
		public function test_max_and_min()
		{
			$this->assertNotEmpty($this->collect([ [ 'id' => 1, 'username' => 'willy', 'password', bcrypt('bcrypt') ], [ 'id' => 2, 'username' => 'alxandra', 'password', bcrypt('a') ] ])->only('id')->min());
			$this->assertNotEmpty($this->collect([ [ 'id' => 1, 'username' => 'willy', 'password', bcrypt('bcrypt') ], [ 'id' => 2, 'username' => 'alxandra', 'password', bcrypt('a') ] ])->only('id')->max());
		}
		
		public function for_all($param) : string
		{
			return strtolower($param);
		}
		
		public function cube($n) : string
		{
			return ( $n * $n * $n );
		}
		
		public function notes($n) : string
		{
			return $n > 10;
		}
		public function exec_call($key,$value)
		{
			return "$key = $value";
		}
	}