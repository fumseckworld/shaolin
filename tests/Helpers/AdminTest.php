<?php

namespace Testing\Helpers;

use Imperium\Testing\Unit;
use Imperium\App;
use Imperium\Exception\Kedavra;
use Symfony\Component\HttpFoundation\Request;
use Imperium\Connexion\Connect;
use PDO;
use Whoops\Run;
use Sinergi\BrowserDetector\Os;
use Faker\Generator;
use Imperium\Security\Hashing\Hash;

class AdminTest extends Unit
{

    public function test_app()
    {
        $this->assertInstanceOf(App::class,app());
    }
    
    public function test_redirect()
    {
    	
        $this->assertTrue(redirect('root','a',true)->isRedirect('/'));
    }
    
    public function test_history()
	{
		$this->assertNotEmpty(history());
		$this->assertStringContainsString('btn',history());
		$this->assertStringContainsString('back',history());
	}

    public function test_db()
    {
        $this->assertNotEmpty(db('driver'));
        $this->assertNotEmpty(db('base'));
        $this->expectException(Kedavra::class);
        db('a');
        db('mode');
    }

    public function test_config()
    {
        $this->assertNotEmpty(config('git','readme'));
    }

    public function test_def()
    {
        $a= '';
        $b = $a;
        $c = $a;
        $this->assertFalse(def($a,$b,$c));
        $this->assertTrue(not_def($a,$b,$c));

        $a= 'lou';
        $b = $a;
        $c = $a;
        $this->assertTrue(def($a,$b,$c));
        $this->assertFalse(not_def($a,$b,$c));
    }

    public function test_request()
    {
        $this->assertInstanceOf(Request::class,request());
    }

    public function test_https()
    {
        $this->assertFalse(https());
    }

    public function test_equal()
    {
        $this->assertTrue(equal('a','a'));
        $this->assertFalse(equal('a','b'));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('Values are equals');

        equal('a','a',true,'Values are equals');
    }

	
    public function test_instance()
	{
		$this->assertInstanceOf(PDO::class,instance()->pdo());
	}
	
	public function test_assign()
	{
		$var ='';
		assign(false,$var,'alexandra');
		$this->assertEquals('',$var);
		assign(true,$var,'alexandra');
		$this->assertEquals('alexandra',$var);
		assign(true,$var,'');
		$this->assertEquals('',$var);
	}
    public function test_string_parse()
	{
		$this->assertNotEmpty(string_parse('i am a super boy'));
		$this->assertEquals(['i','am','a','super','boy'],string_parse('i am a super boy'));
	}
	
    public function test_different()
    {
        $this->assertFalse(different('a','a'));
        $this->assertTrue(different('a','b'));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('Values are differents');
        
        different('a','n',true,'Values are differents');
    }

    public function test_is_not_false()
    {
        $this->assertFalse(is_not_false(false));
        $this->assertTrue(is_not_false(true));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('is true');
        is_not_false(true,true,"is true");
    }

    public function test_is_not_true()
    {
        $this->assertFalse(is_not_true(true));
        $this->assertTrue(is_not_true(false));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('is false');

        is_not_true(false,true,"is false");
    }


    public function test_is_false()
    {
        $this->assertFalse(is_false(true));
        $this->assertTrue(is_false(false));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('is false');
        
        is_false(false,true,"is false");
    }

    public function test_is_true()
    {
        $this->assertTrue(is_true(true));
        $this->assertFalse(is_true(false));
        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('is true');
        
        is_true(true,true,"is true");
    }


    public function test_server()
    {
        $this->assertNotEmpty(server('SCRIPT_FILENAME'));
    }


    public function test_post()
    {
        $this->assertEmpty(post('x'));
    }
    
    public function test_get()
    {
        $this->assertEmpty(get('x'));
    }
       
    public function test_connect()
    {
        $this->assertInstanceOf(Connect::class,connect('mysql','','root','root',LOCALHOST,'dump'));
        $this->assertInstanceOf(PDO::class,connect('mysql','','root','root',LOCALHOST,'dump')->pdo());
    }

        
    public function test_superior()
    {
        $this->assertTrue(superior(10,5));
        $this->assertTrue(superior([1,2,1,4,5,8,7,8,9,41],5));

        $this->assertFalse(superior(10,50));
        $this->assertFalse(superior([1,2,1,4,5,8,7,8,9,41],50));
    }

         
    public function test_superior_or_equal()
    {
        $this->assertTrue(superior_or_equal(10,5));
        $this->assertTrue(superior_or_equal([1,2,1,4,5,8,7,8,9,41],5));

        $this->assertFalse(superior_or_equal(10,50));
        $this->assertFalse(superior_or_equal([1,2,1,4,5,8,7,8,9,41],50));
    }
    public function test_to()
    {
        $this->assertEquals(302,to('/login')->getStatusCode());
        $this->assertEquals(302,to('/login','Connexion')->getStatusCode());
        $this->assertEquals(302,to('/login','Connexion',false)->getStatusCode());
    }

          
    public function test_inferior()
    {
        $this->assertTrue(inferior(1,5));
        $this->assertTrue(inferior([1,2,1,4,5,8,7,8,9,41],50));

        $this->assertFalse(inferior(150,50));
        $this->assertFalse(inferior([1,2,1,4,5,8,7,8,9,41],2));

        $this->expectException(Kedavra::class);
        $this->expectExceptionMessage('is inferior');
        inferior(2,10,true,"is inferior");
    }
    
         
    public function test_inferior_or_equal()

    {
        $this->assertTrue(inferior_or_equal(1,5));
        $this->assertTrue(inferior_or_equal([1,2,1,4,5,8,7,8,9,41],50));

        $this->assertFalse(inferior_or_equal(50,5));
        $this->assertFalse(inferior_or_equal([41,'a',2],1));
    }


    public function test_password_valid()
    {
        $x = (new Hash('valid'))->generate();
        $this->assertTrue(check('valid',$x));
    }
    public function test_woops()
    {    
        $this->assertInstanceOf(Run::class,whoops());
    }



    public function test_faker()
    {    
        $this->assertInstanceOf(Generator::class,faker());
    }

    public function test_os()
    {
        $this->assertEquals(Os::UNKNOWN,os(true));
        $this->assertInstanceOf(Os::class,os());
    }

    public function test_command_and_controller()
    {
        $this->assertNotEmpty(commands());
        $this->assertNotEmpty(controllers());
    }
    public function test_csrf()
    {
        $first = csrf_field();
        $second = csrf_field();
        $this->assertNotEquals($first,$second);
        $this->assertNotEmpty(csrf_field());

    }

    public function test_message()
    {
        $this->assertNotEmpty(message('index.mjml'));
    }
}