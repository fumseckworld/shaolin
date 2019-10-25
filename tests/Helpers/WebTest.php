<?php
namespace Testing\Helpers;

use Imperium\Testing\Unit;
use Imperium\App;
use Imperium\Exception\Kedavra;
use Symfony\Component\HttpFoundation\Request;
use Imperium\Connexion\Connect;
use PDO;
use Whoops\Run;
use Imperium\Collection\Collect;
use Carbon\Carbon;

class WebTest extends Unit
{
    public function test_collect()
    {
        $this->assertInstanceOf(Collect::class,collect());
        $this->assertInstanceOf(Collect::class,collect([1,2,3]));
    }

    public function test_now()
    {
        $this->assertInstanceOf(Carbon::class,now());
        $this->assertInstanceOf(Carbon::class,now('Europe/Paris'));
    }

    public function test_numb()
    {
        $this->assertEquals('1 T',numb(1000000000000));
        $this->assertEquals('1 B',numb(1000000000));
        $this->assertEquals('110 M',numb(110000000));
        $this->assertEquals('100 M',numb(100000000));
        $this->assertEquals('10 M',numb(10000000));
        $this->assertEquals('1 M',numb(1000000));
        $this->assertEquals('100 K',numb(100000));
        $this->assertEquals('10 K',numb(10000));
        $this->assertEquals('1 K',numb(1000));
        $this->assertEquals('100',numb(100));
        $this->assertEquals('10',numb(10));
        $this->assertEquals('1',numb(1));

    }

    public function test_sum()
    {
        $this->assertEquals(5,sum('trois'));
        $this->assertEquals(3,sum([0,1,2]));
        $this->expectException(Kedavra::class);
        sum(true);
    }

    public function test_clear_terminal()
    {
        $this->assertTrue(clear_terminal());
    }
    public function test_has()
    {
        $this->assertTrue(has('lucky',['lucky','jumper','joe']));
        $this->assertTrue(not_in([1,2,3,4,5,6,7,8],9));
        $this->assertFalse(has('avrel',['lucky','jumper','joe']));
        $this->assertFalse(not_in([1,2,3,4,5,6,7,8,9],9));
    }


    public function test_root()
    {
        $this->assertEquals('/',root());
    }

    public function test_route()
    {
        $this->assertEquals('/',route('web','root'));
        $this->assertEquals('/game/imperium',route('web','game',['imperium']));
    }

    public function test_is_mobile()
    {
        $this->assertFalse(is_mobile());
    }

    public function test_is_pair()
    {
        $this->assertTrue(is_pair(0));  
        $this->assertFalse(is_pair(1)); 
        $this->assertTrue(is_pair(2));  
        $this->assertFalse(is_pair(3)); 
        $this->assertTrue(is_pair(4));  
        $this->assertFalse(is_pair(5)); 
        $this->assertTrue(is_pair(6));  
        $this->assertFalse(is_pair(7)); 
        $this->assertTrue(is_pair(8));  
        $this->assertFalse(is_pair(9));
        $this->assertTrue(is_pair(10)); 
    }
    
    public function test_assets()
    {
        $this->assertEquals('<img src="/img/fumseck.jpg" alt="fumseck">',img('fumseck.jpg','fumseck'));
        $this->assertEquals('<link href="/css/app.css"  rel="stylesheet" type="text/css">',css('app'));
        $this->assertEquals('<script src="/js/app.js" ></script>',js('app.js'));
        $this->assertEquals('<script src="/js/app.js" type="babel"></script>',js('app.js','babel'));
    }
}


