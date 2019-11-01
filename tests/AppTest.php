<?php
	
	namespace Testing;
	
	use Imperium\Cache\Cache;
	use Imperium\Collection\Collect;
	use Imperium\Curl\Curl;
	use Imperium\File\File;
	use Imperium\Html\Form\Form;
	use Imperium\Query\Query;
	use Imperium\Security\Auth\Oauth;
	use Imperium\Shopping\Shop;
	use Imperium\Tables\Table;
	use Imperium\Writing\Write;
	use PHPUnit\Framework\TestCase;
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class AppTest extends TestCase
	{
		public function test()
		{
			$this->assertTrue(app()->exist('users'));
			$this->assertFalse(app()->exist('u'));
			$this->assertInstanceOf(Collect::class,app()->collect());
			$this->assertInstanceOf(File::class,app()->file('README.md'));
			$this->assertInstanceOf(Table::class,app()->table());
			$this->assertInstanceOf(Form::class,app()->form('post','admin','create','users'));
			$this->assertInstanceOf(Query::class,app()->sql('users'));
			$this->assertInstanceOf(Request::class,app()->request());
			$this->assertInstanceOf(Oauth::class,app()->auth());
			$this->assertInstanceOf(Write::class,app()->write('test','i am a test','micieli@laposte.net','micieli@laposte.net'));
			$this->assertInstanceOf(RedirectResponse::class,app()->redirect('web','root'));
			$this->assertInstanceOf(RedirectResponse::class,app()->back());
			$this->assertInstanceOf(RedirectResponse::class,app()->to('/'));
			$this->assertInstanceOf(Cache::class,app()->cache());
			$this->assertInstanceOf(Curl::class,app()->curl());
			$this->assertInstanceOf(Shop::class,app()->shop());
			$this->assertNotEmpty(app()->tables());
			$this->assertTrue(app()->save());

		
		}
	}