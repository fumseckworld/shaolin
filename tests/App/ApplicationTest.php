<?php


namespace Testing\App;


use App\Validators\Users\UsersValidator;
use Eywa\Cache\CacheInterface;
use Eywa\Collection\Collect;
use Eywa\Database\Connexion\Connect;
use Eywa\Database\Connexion\Connexion;
use Eywa\Database\Query\Sql;
use Eywa\Detection\Detect;
use Eywa\Exception\Kedavra;
use Eywa\File\File;
use Eywa\Html\Form\Form;
use Eywa\Http\Request\FormRequest;
use Eywa\Http\Request\Request;
use Eywa\Http\Response\Response;
use Eywa\Message\Email\Write;
use Eywa\Security\Authentication\Auth;
use Eywa\Security\Crypt\Crypter;
use Eywa\Session\SessionInterface;
use Eywa\Testing\Unit;
use Redis;

class ApplicationTest extends Unit
{

    /**
     * @throws Kedavra
     */
    public function test_ioc()
    {
        $this->assertInstanceOf(Connexion::class,app()->ioc(Connect::class));
        $this->assertInstanceOf(Connexion::class,development());
        $this->assertInstanceOf(Connexion::class,production());
        $this->assertInstanceOf(Write::class,app()->write('subject','message','micieli@laposte.net','micieli@laposte.net'));
        $this->assertInstanceOf(Response::class,app()->response('i am a view')->send());
        $this->assertInstanceOf(Response::class,app()->redirect('root',[],'',true)->send());
        $this->assertInstanceOf(Collect::class,app()->collect());
        $this->assertTrue(app()->validator(new UsersValidator())->to('/error'));
        $this->assertInstanceOf(Sql::class,app()->sql('users'));
        $this->assertInstanceOf(SessionInterface::class,app()->session());
        $this->assertInstanceOf(Auth::class,app()->auth());
        $this->assertInstanceOf(File::class,app()->file('README.md'));
        $this->assertInstanceOf(Form::class,app()->form(new FormRequest('/')));
        $this->assertInstanceOf(Request::class,app()->request());
        $this->assertInstanceOf(Detect::class,app()->detect());
        $this->assertInstanceOf(Response::class,app()->back());
        $this->assertInstanceOf(Response::class,app()->to('root'));
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
        $this->assertNull(app()->cookie('a',null));
        $this->assertNull(app()->server('a',null));

    }


}