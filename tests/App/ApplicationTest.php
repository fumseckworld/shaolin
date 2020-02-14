<?php


namespace Testing\App;


use Eywa\Cache\CacheInterface;
use Eywa\Collection\Collect;
use Eywa\Database\Connexion\Connect;
use Eywa\Database\Connexion\Connexion;
use Eywa\Database\Query\Sql;
use Eywa\Detection\Detect;
use Eywa\File\File;
use Eywa\Html\Form\Form;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;
use Eywa\Message\Email\Write;
use Eywa\Security\Authentication\Auth;
use Eywa\Security\Crypt\Crypter;
use Eywa\Security\Validator\Validator;
use Eywa\Session\SessionInterface;
use Eywa\Testing\Unit;
use Redis;

class ApplicationTest extends Unit
{

    public function test_ioc()
    {
        $this->assertInstanceOf(Connexion::class,app()->ioc(Connect::class));
        $this->assertInstanceOf(Write::class,app()->write('subject','message','micieli@laposte.net','micieli@laposte.net'));
        $this->assertInstanceOf(Response::class,app()->response('i am a view')->send());
        $this->assertInstanceOf(Response::class,app()->redirect('root',[],'',true)->send());
        $this->assertInstanceOf(Collect::class,app()->collect());
        $this->assertInstanceOf(Validator::class,app()->validator(['id' => 36]));
        $this->assertInstanceOf(Sql::class,app()->sql('users'));
        $this->assertInstanceOf(SessionInterface::class,app()->session());
        $this->assertInstanceOf(Auth::class,app()->auth());
        $this->assertInstanceOf(File::class,app()->file('README.md'));
        $this->assertInstanceOf(Form::class,app()->form('root',GET));
        $this->assertInstanceOf(Request::class,app()->request());
        $this->assertInstanceOf(Detect::class,app()->detect());
        $this->assertInstanceOf(Response::class,app()->view('welcome','welcome','welcome'));
        $this->assertInstanceOf(Response::class,app()->back());
        $this->assertInstanceOf(Response::class,app()->to('root'));
        $this->assertInstanceOf(Response::class,app()->run());
        $this->assertInstanceOf(Response::class,app()->json(['os'=> 'linux']));
        $this->assertInstanceOf(Connexion::class,app()->connexion());
        $this->assertInstanceOf(Connexion::class,app()->connexion()->development());
        $this->assertInstanceOf(Crypter::class,app()->crypter());
        $this->assertInstanceOf( Redis::class,app()->redis());
        $this->assertInstanceOf( Redis::class,app()->redis());
        $this->assertInstanceOf( CacheInterface::class,app()->cache());
        $this->assertInstanceOf( CacheInterface::class,app()->cache(FILE_CACHE));
        $this->assertInstanceOf( CacheInterface::class,app()->cache(REDIS_CACHE));
        $this->assertInstanceOf( CacheInterface::class,app()->cache(MEMCACHE_CACHE));
        $this->assertInstanceOf( CacheInterface::class,app()->cache(APCU_CACHE));
        $this->assertEquals(MYSQL,app()->env('DB_DRIVER'));
        $this->assertNotEquals('linux',app()->crypt('linux'));
        $A = app()->crypt('linux');

        $this->assertEquals('linux',app()->decrypt($A));
        $this->assertNull(app()->get('a',null));
        $this->assertNull(app()->post('a',null));
        $this->assertNull(app()->files('a',null));
        $this->assertNull(app()->cookie('a',null));
        $this->assertNull(app()->server('a',null));

        $this->assertEquals([],app()->config('connection','options'));
        $this->assertEquals([],app()->config('connection','options'));
    }


}