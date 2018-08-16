<?php
/**
 * fumseck added HelpersTest.php to imperium
 * The 11/09/17 at 06:08
 *
 * imperium is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or any later version.
 *
 * imperium is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package : imperium
 * @author  : fumseck
 **/


namespace tests;


use Carbon\Carbon;
use Cz\Git\GitRepository;
use Faker\Generator;
use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\Databases\Exception\IdentifierException;
use Imperium\Directory\Dir;
use Imperium\File\File;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Form\Form;
use Intervention\Image\ImageManager;
use PDO;
use PHPUnit\Framework\TestCase;
use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Os;

class HelpersTest extends TestCase
{
    /**
     * @var \Imperium\Databases\Eloquent\Query\Query
     */
    private $sql;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Base
     */
    private $mariadb;

    private $base = 'imperiums';

    private $table = 'country';
    /**
     * @var Base
     */
    private $pgsql;

    public function setUp()
    {
        $this->pdo = connect(Connexion::SQLITE, $this->base);
        $this->sql = sql('country');
        $this->mariadb =  base(Connexion::MYSQL,'','root','','');
        $this->pgsql =  base(Connexion::POSTGRESQL,'','postgres','','');
    }

    public function testIconBar()
    {
        $bar =  icon()
            ->add(fa('fa-bars','fa-2x'),'menu','#myNavmenu','',true)
            ->add(fa('fa-table','fa-2x'),'tables','#myNavmenu' ,'',true)
            ->add(fa('fa-database','fa-2x'),'database','#myNavmenu' ,'',true)
            ->add(fa('fa-users','fa-2x'),'users','#myNavmenu','',true)
            ->add(fa('fa-user','fa-2x'),'user','#myNavmenu' ,'',true)
            ->add(fa('fa-tachometer-alt','fa-2x'),'dashboard', '/','')
            ->add(fa('fa-chart-pie','fa-2x'),'graph', '/','')
            ->add(fa('fa-terminal','fa-2x'),'sql', '/','')
            ->add(fa('fa-home','fa-2x'),'home', '/','')
            ->add(fa('fa-power-off','fa-2x'),'logout', '/','')
            ->end();

        $this->assertStringStartsWith('<ul class="list-inline">',$bar);

        $this->assertContains('myNavmenu',$bar);
        $this->assertContains('fa-2x',$bar);

        $this->assertContains('link',$bar);
        $this->assertContains('icon',$bar);

        $this->assertContains('menu',$bar);
        $this->assertContains('tables',$bar);
        $this->assertContains('database',$bar);
        $this->assertContains('users',$bar);
        $this->assertContains('user',$bar);
        $this->assertContains('dashboard',$bar);
        $this->assertContains('graph',$bar);
        $this->assertContains('sql',$bar);
        $this->assertContains('home',$bar);
        $this->assertContains('logout',$bar);


        $this->assertContains('fa-bars',$bar);
        $this->assertContains('fa-table',$bar);
        $this->assertContains('fa-database',$bar);
        $this->assertContains('fa-users',$bar);
        $this->assertContains('fa-user',$bar);
        $this->assertContains('fa-tachometer-alt',$bar);
        $this->assertContains('fa-chart-pie',$bar);
        $this->assertContains('fa-terminal',$bar);
        $this->assertContains('fa-home',$bar);
        $this->assertContains('fa-power-off',$bar);

        $this->assertStringEndsWith('</ul>',$bar);
    }

    public function testPrev()
    {
        $array = ['a','b'];

        $this->assertEquals('a',array_prev($array,'b'));
        $array = ['a','b','c'];
        $this->assertEquals('b',array_prev($array,'c'));
    }
    /**
     * @throws IdentifierException
     */
    public function testBase()
    {
        $this->assertInstanceOf(Base::class, base('mysql', 'a', 'a', 'a', 'dump'));
        $this->assertInstanceOf(Base::class, base('pgsql', 'a', 'a', 'a', 'dump'));
        $this->assertInstanceOf(Base::class, base('sqlite', 'a', 'a', 'a', 'dump'));
        $this->expectException(IdentifierException::class);
        base(Connexion::MYSQL, 'base', 'user', 'pass', 'dump')->show();
        base(Connexion::POSTGRESQL, 'base', 'user', 'pass', 'dump')->show();
        base(Connexion::SQLITE, 'base', 'user', 'pass', 'dump')->show();
        base(Connexion::SQLITE, 'base', 'user', 'pass', 'dump')->create();
        base(Connexion::MYSQL, 'base', 'user', 'pass', 'dump')->create();
        base(Connexion::POSTGRESQL, 'base', 'user', 'pass', 'dump')->create();
        base(Connexion::ORACLE, 'base', 'user', 'pass', 'dump')->create();
        $this->assertEquals(false, base(Connexion::MYSQL, 'ae', 'a', 'pass', 'dump')->setName('a')->exist());
        $this->assertEquals(false, base(Connexion::POSTGRESQL, 'a', 'a', 'pass', 'dump')->setName('a')->exist());
        $this->assertEquals(false, base(Connexion::SQLITE, 'a', 'a', 'pass', 'dump')->setName('a')->exist());
        $this->assertEquals(false, base(Connexion::ORACLE, 'a', 'a', 'pass', 'dump')->setName('a')->exist());
    }

    public function testCollation()
    {


        $mysql = collation(Connexion::MYSQL,root(Connexion::MYSQL,'root','root'));
        $pgsql = collation(Connexion::POSTGRESQL,root(Connexion::POSTGRESQL,'postgres',''));


        $this->assertNotEmpty($mysql);
        $this->assertNotEmpty($pgsql);
        $this->assertContains('utf8_general_ci',$mysql);
        $this->assertContains('utf8_unicode_ci',$mysql);
        $this->assertContains('C',$pgsql);
        $this->assertContains('POSIX',$pgsql);
        $this->assertEquals([], collation('sqlite', connect('sqlite', 'testing')));
    }

    public function testSql()
    {
        $this->assertEquals(5, count($this->sql->setPdo($this->pdo)->limit(5, 2)->where('id', '>=', 1)->getRecords()));

    }

    public function testFile()
    {
        $this->assertEquals('',files('a'));
    }

    public function testArray()
    {
        $a = array();
        push($a,1);
        $this->assertContains(1,$a);
        $this->assertEquals(true,has(1,$a));
        $this->assertEquals([1],values($a));
        pop($a);
        $this->assertEquals(true,empty($a));
        $a = array('linux','is','the','must');
        $this->assertEquals(['linux','is','the','must'],values($a));
        pop($a);
        $this->assertEquals(['linux','is','the'],values($a));
        pop($a);pop($a);
        $this->assertEquals(['linux'],values($a));
        push($a,'is the better os');
        $this->assertEquals(2,count($a));
        $b = array( "color" , "red");
        $a = array('a');
        $this->assertEquals( ["color" , "red" ,'a'],merge($b,$a));
    }

    public function testPhp()
    {
        $expected = 4;
        $key = 'age';
        $_SESSION[$key] = $expected;
        $_COOKIE[$key] = $expected;
        $_GET[$key] = $expected;
        $_POST[$key] = $expected;
        $_SERVER[$key] = $expected;
        $_FILES[$key] = $expected;

        $this->assertEquals('',cookie('a'));
        $this->assertEquals('',session('a'));
        $this->assertEquals('',server('a'));
        $this->assertEquals('',get('a'));
        $this->assertEquals('',post('a'));
        $this->assertEquals('',files('a'));

        $this->assertEquals($expected,cookie($key));
        $this->assertEquals( $expected,session($key));
        $this->assertEquals($expected,server($key));
        $this->assertEquals($expected,get($key));
        $this->assertEquals($expected,post($key));
        $this->assertEquals($expected,files($key));
    }
    public function testExecuteAndReq()
    {
        $this->assertEquals(true, execute($this->pdo, "delete  from country WHERE id = 10"));
        $this->assertNotEmpty(req($this->pdo, "select  * from country WHERE id = 1", PDO::FETCH_ASSOC));
    }

    public function testGit()
    {
        $this->assertInstanceOf(GitRepository::class, git('.'));
    }

    public function testTable()
    {

        $this->assertContains('works', table(Connexion::SQLITE, $this->base, '', '', '')->show());
        $this->assertContains('patients', table(Connexion::SQLITE, $this->base, '', '', '')->show());
        $this->assertContains('doctors', table(Connexion::SQLITE, $this->base, '', '', '')->show());

        $this->assertContains('works', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->show());
        $this->assertContains('patients', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->show());
        $this->assertContains('doctors', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->show());


        $this->assertContains('public.works', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.patients', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.doctors', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.doctors', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());

        $this->assertEquals(true, table(Connexion::MYSQL, $this->base, 'root', 'root', '')->has());
        $this->assertEquals(true, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->has());
        $this->assertEquals(true, table(Connexion::SQLITE, $this->base, '', '', '')->has());

        $this->assertEquals(100, table(Connexion::MYSQL, $this->base, 'root', 'root', '')->count($this->table));
        $this->assertEquals(100, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->count($this->table));
        $this->assertEquals(99, table(Connexion::SQLITE, $this->base, '', '', '')->count($this->table));

        $this->assertContains('id', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->setName($this->table)->getColumns());
        $this->assertContains( 'name', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->setName($this->table)->getColumns());

        $this->assertContains('id', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumns());
        $this->assertContains('name', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumns());

        $this->assertContains('id', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumns());
        $this->assertContains( 'name', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumns());


        $this->assertContains( 'INTEGER', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'VARCHAR(255)', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumnsTypes());

        $this->assertContains( 'int(11)', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'varchar(255)', table(Connexion::MYSQL, $this->base, 'root', 'root', '')->setName($this->table)->getColumnsTypes());


        $this->assertContains( 'integer', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'character varying', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumnsTypes());


        $this->assertEquals(true, table(Connexion::SQLITE, $this->base, '', '', '') ->exist($this->table));
        $this->assertEquals(true, table(Connexion::MYSQL, $this->base, 'root', 'root', '') ->exist($this->table));
        $this->assertEquals(true, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '') ->exist("public.$this->table"));

        $this->assertEquals(false, table(Connexion::SQLITE, $this->base, '', '', '')->exist('alexandra'));
        $this->assertEquals(false, table(Connexion::MYSQL, $this->base, 'root', 'root', '')->exist('alexandra'));
        $this->assertEquals(false, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->exist('alexandra'));

        $this->assertEquals(false, table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->isEmpty());
        $this->assertEquals(false, table(Connexion::MYSQL, $this->base, 'root', 'root', '')->setName($this->table)->isEmpty());
        $this->assertEquals(false, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->isEmpty());

        $this->assertEquals(Connexion::SQLITE, table(Connexion::SQLITE, $this->base, '', '', '')->getDriver());
        $this->assertEquals(Connexion::POSTGRESQL, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->getDriver());
        $this->assertEquals(Connexion::MYSQL, table(Connexion::MYSQL, $this->base, 'root', 'root', '')->getDriver());

    }

    public function testUserDell()
    {
        $this->assertEquals(false, userDel(Connexion::SQLITE,$this->pdo, 'user'));

    }

    public function testUserAdd()
    {
        $this->assertEquals(false, userAdd(Connexion::SQLITE, 'username', 'pass', '', $this->pdo));
        $this->assertEquals(true, userAdd(Connexion::MYSQL, 'username', 'pass', '', root(Connexion::MYSQL,'root','root')));
        $this->assertEquals(true, userAdd(Connexion::MYSQL, 'usernames', 'pass', '', root(Connexion::MYSQL,'root','root')));
        $this->assertEquals(true, userDel(Connexion::MYSQL, root(Connexion::MYSQL,'root','root'),'usernames', 'username'));
    }

    public function testFa()
    {
        $this->assertContains('fa-linux', fa('fa-linux'));
        $this->assertContains('fa-linux fa-spin', fa('fa-linux', 'fa-spin'));
    }

    public function testCssLoader()
    {
        $this->assertEquals('<link href="url" rel="stylesheet">', cssLoader('url'));
        $this->assertEquals('<link href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css" rel="stylesheet">', cssLoader('https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css'));
    }

    public function testJsLoader()
    {
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js" type="text/javascript"></script>', jsLoader('https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js'));
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js" type="text/babel"></script>', jsLoader('https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js', 'text/babel'));
    }



    public function testHelper()
    {
        $this->assertContains('v5.0.8',fontAwesome());
        $this->assertContains('3.1.3',jasnyCss());
        $this->assertContains('3.1.3',jasnyJs());
        $this->assertContains('script',jasnyJs());

        $this->assertContains('6.4.3',foundation());


        $this->assertContains('<link rel="stylesheet"',fontAwesome());
        $this->assertContains('<link rel="stylesheet"',jasnyCss());
        $this->assertContains('<link rel="stylesheet"',bootswatch('lumen'));
        $this->assertContains('<link rel="stylesheet"',bootswatch('bootstrap'));
        $this->assertContains('<link rel="stylesheet"',foundation());

        $this->assertInstanceOf(Icon::class,icon());
        $this->assertInstanceOf(Canvas::class,canvas('a'));

        $this->assertContains('a',canvas('a')->end());

        $this->assertContains('icon',Icon::start()->add(fa('fa-linux'),'linux','/linux','')->end());
        $this->assertContains('link',Icon::start()->add(fa('fa-linux'),'linux','/linux','')->end());

        $this->assertContains('fa-linux',Icon::start()->add(fa('fa-linux'),'linux','/linux','')->end());
        $this->assertContains('list-inline',icon()->end());
        $this->assertContains('link',icon()->add(fa('fa-linux'),'2','/','')->end());
        $this->assertContains('icon',icon()->add(fa('fa-linux'),'2','/','')->end());
        $this->assertContains('2',icon()->add(fa('fa-linux'),'2','/','')->end());
        $this->assertContains('/',icon()->add(fa('fa-linux'),'2','/','')->end());


        $this->assertContains('fa-linux fa-2x',icon()->add(fa('fa-linux','fa-2x'),'linux','/linux','')->end());
        $this->assertContains('linux',icon()->add(fa('fa-linux','fa-2x'),'linux','/linux','')->end());
        $this->assertContains('/linux',icon()->add(fa('fa-linux','fa-2x'),'linux','/linux','')->end());


        $this->assertContains('list-inline',canvas('a')->end());
        $this->assertContains('offCanvasLink',canvas('a')->end());
        $this->assertContains('navmenu-fixed-right',canvas('a')->end());
        $this->assertContains('a',canvas('a')->end());
        $this->assertContains('alexandra',canvas('a')->add('alexandra','/','al')->end());
        $this->assertContains('/',canvas('a')->add('alexandra','/','al')->end());
        $this->assertContains('/',canvas('al')->add('alexandra','/','al')->end());
    }
    public function testIconic()
    {
        $this->assertEquals('<svg viewBox="0 0 8 8"><use xlink:href="icon"></use></svg>', iconic('svg', 'icon'));
        $this->assertEquals('<span class="oi" data-glyph="icon"></span>', iconic('icon', 'icon'));
        $this->assertEquals('<span class="oi icon"></span>', iconic('bootstrap', 'icon'));
        $this->assertEquals('<span class="icon"></span>', iconic('foundation', 'icon'));
        $this->assertEquals('<img src="icon">', iconic('img', 'icon'));
        $this->assertEquals('', iconic('imadzg', 'icon'));
    }

    public function testGlyph()
    {
        $this->assertEquals('<svg-icon><src href="icon"/></svg-icon>', glyph('icon'));
        $this->assertEquals('<img src="icon"/>', glyph('icon', 'img'));
        $this->assertEquals('<img src="icon"/>', glyph('icon', 'a'));
    }

    public function testZones()
    {
      $this->assertNotContains(25,zones('a'));
      $this->assertNotContains(1,zones('a'));
      $this->assertContains('a',zones('a'));
      $this->assertContains('Europe/Paris',zones('a'));
      $this->assertContains('Europe/Madrid',zones('a'));
      $this->assertContains('Africa/Abidjan',zones('a'));
      $this->assertContains('Africa/Dakar',zones('a'));
      $this->assertTrue(is_array(zones('m')));

    }

    public function testRegister()
    {
        $register = registerForm(1,'/','Your username','your email','your password','confirm','create account','create');

        $this->assertContains('<i class="fas fa-user"></i>',$register);
        $this->assertContains('<i class="fas fa-key"></i>',$register);
        $this->assertContains('<i class="fas fa-envelope"></i>',$register);
        $this->assertContains('<i class="fas fa-user-plus"></i>',$register);
        $this->assertContains('btn btn-outline-primary',$register);
        $this->assertContains('Your username',$register);
        $this->assertContains('your email',$register);
        $this->assertContains('your password',$register);
        $this->assertContains('confirm',$register);
        $this->assertContains('create',$register);
        $this->assertContains('create',$register);
        $this->assertContains('create account',$register);
        $this->assertNotContains('Europe/Paris',$register);
        $this->assertNotContains('Europe/London',$register);

        $register = registerForm(1,'/','Your username','your email','your password','confirm','create account','create',true,['/' =>'choose','fr' => 'french' ,'en' => 'english'],'choose a time zone');

        $this->assertContains('fr',$register);
        $this->assertContains('en',$register);
        $this->assertContains('Europe/Paris',$register);
        $this->assertContains('Europe/London',$register);
        $this->assertContains('choose a time zone',$register);
        $this->assertContains('<i class="fas fa-user"></i>',$register);
        $this->assertContains('<i class="fas fa-key"></i>',$register);
        $this->assertContains('<i class="fas fa-envelope"></i>',$register);
        $this->assertContains('<i class="fas fa-user-plus"></i>',$register);
        $this->assertContains('btn btn-outline-primary',$register);
        $this->assertContains('Your username',$register);
        $this->assertContains('your email',$register);
        $this->assertContains('your password',$register);
        $this->assertContains('confirm',$register);
        $this->assertContains('create',$register);
        $this->assertContains('create',$register);
        $this->assertContains('create account',$register);


    }

    public function testImage()
    {
        $this->assertInstanceOf(ImageManager::class, image('gd'));
    }

    public function testDate()
    {
        $this->assertInstanceOf(Carbon::class, today());
        $this->assertInstanceOf(Carbon::class, now());
        $this->assertEquals(Carbon::now()->addHour(1)->toDateString(), future('hour', 1));
        $this->assertEquals(Carbon::now()->addHour(2)->toDateString(), future('hours', 2));
        $this->assertEquals(Carbon::now()->addMinute(2)->toDateString(), future('minute', 2));
        $this->assertEquals(Carbon::now()->addDay(3)->toDateString(), future('day', 3));
        $this->assertEquals(Carbon::now()->addWeek(2)->toDateString(), future('week', 2));
        $this->assertEquals(Carbon::now()->addMonths(2)->toDateString(), future('month', 2));
        $this->assertEquals(Carbon::now()->addYear(2)->toDateString(), future('year', 2));
        $this->assertEquals(Carbon::now()->addCenturies(2)->toDateString(), future('century', 2));
        $this->assertEquals(Carbon::now()->addHour(2)->toDateString(), future('', 2));
        $this->assertEquals('1 second ago', ago('en', Carbon::now()));
        $this->assertEquals('hace 1 segundo', ago('es', Carbon::now()));
        $this->assertEquals('1 secondo fa', ago('it', Carbon::now()));
        $this->assertEquals('il y a 1 seconde', ago('fr', Carbon::now()));
    }

    public function testPass()
    {
        $this->assertEquals(true, pass(Connexion::MYSQL, 'root', 'root', 'root'));
        $this->assertEquals(true, pass(Connexion::POSTGRESQL, 'postgres', '', ''));
    }



    public function testIsMobile()
    {
        $this->assertEquals(false, isMobile());
        $this->assertEquals(true, !isMobile());
    }

    public function testGetOs()
    {
        $this->assertEquals('unknown', getOs());
        $this->assertEquals('unknown', os(true));
    }

    public function testGetBrowser()
    {
        $this->assertEquals('unknown', getBrowser());
        $this->assertEquals('unknown', browser(true));
    }

    public function testGetDevice()
    {
        $this->assertEquals('unknown', getDevice());
        $this->assertEquals('unknown', device(true));
    }

    public function testIsBrowser()
    {
        foreach (['chromium', 'firefox', 'vivaldi', 'opera', 'google chrome', 'edge'] as $browser) {
            $this->assertEquals(false, isBrowser($browser));
        }

    }
   public function testCurrentBranch()
    {
        $this->assertEquals('master',getCurrentBranch('.'));

    }

    public function testGetOsObject()
    {
        $this->assertInstanceOf(Os::class,os());
    }

    public function testGetDeviceObject()
    {
        $this->assertInstanceOf(Device::class,device());
    }

    public function testGetBrowserObject()
    {
        $this->assertInstanceOf(Browser::class,browser());
    }

    public function testShowWithNotPossible()
    {
       $this->assertEquals([],show('sqlite','imperiums','root','root'));
    }

    public function testArrayPrevWhichOneKey()
    {
       $this->assertEquals('a',array_prev(['a'],'a'));
    }

    /**
     * @throws IdentifierException
     */
    public function testCreateDatabases()
    {
        $mysqlPdo = root(Connexion::MYSQL,'root','root');
        $pgsqlPdo = root(Connexion::POSTGRESQL,'postgres','');
        $sqlitePdo = connect(Connexion::SQLITE,'imperiums','','');

        $database = 'superman';


        $this->assertEquals(true,create(Connexion::MYSQL,$database,'utf8','utf8_general_ci',$mysqlPdo));
        $this->assertEquals(true,base(Connexion::MYSQL,'','root','root','')->drop($database));


        $this->assertEquals(true,create(Connexion::POSTGRESQL,$database,'UTF8','C',$pgsqlPdo));
        $this->assertEquals(true,base(Connexion::POSTGRESQL,'','postgres','','')->drop($database));

        $this->assertEquals(true,create(Connexion::SQLITE,$database,'','',$sqlitePdo));
        $this->assertEquals(true,base(Connexion::SQLITE,'','postgres','','')->drop($database));

        $this->expectException(IdentifierException::class);

        $this->assertEquals(false,create('a',$database,'utf8','utf8_general_ci',$mysqlPdo));
        $this->assertEquals(false,base('a','','postgres','','')->drop($database));

    }



    public function testPassReturnFalse()
    {
        $this->assertEquals(false,pass('sqlite','root','root',''));
    }
    public function testCharset()
    {
        $mysql = charset(Connexion::MYSQL,root(Connexion::MYSQL,'root','root'));
        $pgsql = charset(Connexion::POSTGRESQL,root(Connexion::POSTGRESQL,'postgres',''));

        $this->assertNotEmpty($mysql);
        $this->assertNotEmpty($pgsql);

        $this->assertContains('utf8',  $mysql);
        $this->assertContains('UTF8',  $pgsql);


        $this->assertEquals([], charset('sqlite', connect('sqlite', 'testing')));
    }

    public function testUser()
    {
        $this->assertInstanceOf(Users::class, user('mysql', 'a', 'a'));
        $this->assertInstanceOf(Users::class, user('pgsql', 'a', 'a'));
        $this->assertContains('root', user(Connexion::MYSQL, 'root', 'root')->show());
        $this->assertContains('postgres', user(Connexion::POSTGRESQL, 'postgres', '')->show());
        $this->assertEquals([], user(Connexion::SQLITE, 'adz', 'pass')->show());
    }

    /**
     * @throws IdentifierException
     */
    public function testShow()
    {

        $database = 'imperiums';

        $this->assertEquals([],show(Connexion::MYSQL, $database, 'root', 'root', 88888888));
        $this->assertEquals([],show(Connexion::POSTGRESQL, $database, 'postgres', '', 88888888));

        $this->assertNotEmpty(show(Connexion::MYSQL, $database, 'root', 'root', Eloquent::MODE_ALL_TABLES));
        $this->assertContains('patients',show(Connexion::MYSQL, $database, 'root', 'root', Eloquent::MODE_ALL_TABLES));

        $this->assertNotEmpty(show(Connexion::MYSQL, $database, 'root', 'root', Eloquent::MODE_ALL_USERS));
        $this->assertContains('root',show(Connexion::MYSQL, $database, 'root', 'root', Eloquent::MODE_ALL_USERS));

        $this->assertNotEmpty(show(Connexion::MYSQL, '', 'root', 'root', Eloquent::MODE_ALL_DATABASES));
        $this->assertContains('mysql',show(Connexion::MYSQL, '', 'root', 'root', Eloquent::MODE_ALL_DATABASES));

        $this->assertNotEmpty(show(Connexion::POSTGRESQL, $database, 'postgres', '', Eloquent::MODE_ALL_TABLES));
        $this->assertContains('public.doctors',show(Connexion::POSTGRESQL, $database, 'postgres', '', Eloquent::MODE_ALL_TABLES));

        $this->assertNotEmpty(show(Connexion::POSTGRESQL, $database, 'postgres', '', Eloquent::MODE_ALL_USERS));
        $this->assertContains('postgres',show(Connexion::POSTGRESQL, '', 'postgres', '', Eloquent::MODE_ALL_USERS));

        $this->assertNotEmpty(show(Connexion::POSTGRESQL, '', 'postgres', '', Eloquent::MODE_ALL_DATABASES));
        $this->assertContains('postgres',show(Connexion::POSTGRESQL, '', 'postgres', '', Eloquent::MODE_ALL_DATABASES));

        $this->expectException(IdentifierException::class);

        show(Connexion::MYSQL, 'user', 'user', 'password', Eloquent::MODE_ALL_DATABASES);
        show(Connexion::MYSQL, 'user', 'user', 'password', Eloquent::MODE_ALL_USERS);

        show(Connexion::POSTGRESQL, 'a', 'user', 'password', Eloquent::MODE_ALL_TABLES);
        show(Connexion::POSTGRESQL, 'user', 'user', 'password', Eloquent::MODE_ALL_DATABASES);
        show(Connexion::POSTGRESQL, 'user', 'user', 'password', Eloquent::MODE_ALL_USERS);
    }

    public function testFormInstance()
    {
        $this->assertInstanceOf(Form::class, form(Form::BOOTSTRAP));
        $this->assertInstanceOf(Form::class, form(Form::FOUNDATION));
        $this->assertInstanceOf(Form::class, form(3));
        $this->assertInstanceOf(Form::class, form(5));
    }

    /**
     * @throws IdentifierException
     */
    public function testDrop()
    {


        $user = 'dracula';

        $mysqlBases = base(Connexion::MYSQL,'','root','root','');
        $pgsqlBases = base(Connexion::POSTGRESQL,'','postgres','','');

        $mysqlTables = table(Connexion::MYSQL,'imperiums','root','root','');
        $pgsqlTables = table(Connexion::POSTGRESQL,'imperiums','postgres','','');

        $mysqlUsers = user(Connexion::MYSQL,'root','root');
        $pgsqlUsers = user(Connexion::POSTGRESQL,'postgres','');

        $this->assertEquals(true,userAdd(Connexion::MYSQL,$user,$user,'',root(Connexion::MYSQL,'root','root')));
        $this->assertEquals(true,userAdd(Connexion::POSTGRESQL,$user,$user,'',root(Connexion::POSTGRESQL,'postgres','')));


        $this->assertEquals(true,create(Connexion::MYSQL,$user,'utf8','utf8_general_ci',root(Connexion::MYSQL,'root','root')));
        $this->assertEquals(true,create(Connexion::POSTGRESQL,$user,'UTF8','C',root(Connexion::POSTGRESQL,'postgres','')));



        $this->assertEquals(true,$mysqlTables->setName($user)->addField('int','loi',true)->create());
        $this->assertEquals(true,$pgsqlTables->setName($user)->addField('serial','loi',true)->create());


        $this->assertEquals(true,drop($mysqlBases,$user));
        $this->assertEquals(true,drop($mysqlTables,$user));
        $this->assertEquals(true,drop($mysqlUsers,$user));

        $this->assertEquals(true,drop($pgsqlBases,$user));
        $this->assertEquals(true,drop($pgsqlTables,$user));
        $this->assertEquals(true,drop($pgsqlUsers,$user));

    }

    public function testClearDirectory()
    {
        $this->assertEquals(true,Dir::clear('dump'));
    }
    public function testDump()
    {
        $base = 'imperiums';
        $table = 'doctors';

        $this->assertNotEquals(false,dump(Connexion::MYSQL,'root','root',$base,'dump'));
        $this->assertNotEquals(false,dump(Connexion::POSTGRESQL,'postgres','',$base,'dump'));
        $this->assertNotEquals(false,dump(Connexion::SQLITE,'','',$base,'dump'));

        $this->assertNotEquals(false,dump(Connexion::MYSQL,'root','root',$base,'dump',Eloquent::MODE_DUMP_TABLE,$table));
        $this->assertNotEquals(false,dump(Connexion::POSTGRESQL,'postgres','',$base,'dump',Eloquent::MODE_DUMP_TABLE,$table));
        $this->assertNotEquals(false,dump(Connexion::SQLITE,'','',$base,'dump',Eloquent::MODE_DUMP_TABLE,$table));

        $this->assertEquals(false,dump(Connexion::POSTGRESQL,'root','root',$base,'dump')) ;
        $this->assertEquals(false,dump(Connexion::MYSQL,'postgres','',$base,'dump')) ;

        $this->assertEquals(false , dump(Connexion::POSTGRESQL,'root','root',$base,'dump',Eloquent::MODE_DUMP_TABLE,$table)) ;
        $this->assertEquals(false,dump(Connexion::MYSQL,'postgres','',$base,'dump',Eloquent::MODE_DUMP_TABLE,$table));
        
    }
    public function testFuture()
    {
        $this->assertEquals(now()->addSecond(60)->toDateString(),future('second',60));
        $this->assertEquals(now()->addSeconds(360)->toDateString(),future('seconds',360));
        $this->assertEquals(now()->addMinutes(50)->toDateString(),future('minutes',50));
        $this->assertEquals(now()->addMinute()->toDateString(),future('minute',1));
        $this->assertEquals(now()->addHour()->toDateString(),future('hour',1));
        $this->assertEquals(now()->addHours(5)->toDateString(),future('hours',5));
        $this->assertEquals(now()->addDay()->toDateString(),future('day',1));
        $this->assertEquals(now()->addDays(5)->toDateString(),future('days',5));
        $this->assertEquals(now()->addWeek()->toDateString(),future('week',1));
        $this->assertEquals(now()->addWeeks(10)->toDateString(),future('weeks',10));
        $this->assertEquals(now()->addMonth()->toDateString(),future('month',1));
        $this->assertEquals(now()->addMonths(11)->toDateString(),future('months',11));
        $this->assertEquals(now()->addYear()->toDateString(),future('year',1));
        $this->assertEquals(now()->addYears(5)->toDateString(),future('years',5));
        $this->assertEquals(now()->addCentury()->toDateString(),future('century',1));
        $this->assertEquals(now()->addCenturies(5)->toDateString(),future('centuries',5));

    }

    public function testIsLoaded()
    {
        $this->assertEquals(true,mysql_loaded());
        $this->assertEquals(true,pgsql_loaded());
        $this->assertEquals(true,sqlite_loaded());
    }
    public function testExist()
    {
        $this->assertEquals(true,exist('drop'));
        $this->assertEquals(true,exist('faker'));
        $this->assertEquals(true,exist('db'));
        $this->assertEquals(false,exist('lorem'));
    }
    public function testFaker()
    {
        $this->assertInstanceOf(Generator::class,faker('fr'));
        $this->assertInstanceOf(Generator::class,faker('en'));
        $this->assertInstanceOf(Generator::class,faker('es'));
    }

    public function testUserDel()
    {
        $user = 'alexandra';


        $this->assertEquals(true,userAdd(Connexion::MYSQL,$user,$user,'',root(Connexion::MYSQL,'root','root')));
        $this->assertEquals(true,userAdd(Connexion::POSTGRESQL,$user,$user,'',root(Connexion::POSTGRESQL,'postgres','')));

        $this->assertEquals(true,userDel(Connexion::MYSQL,root(Connexion::MYSQL,'root','root'),$user));
        $this->assertEquals(true,userDel(Connexion::POSTGRESQL,root(Connexion::POSTGRESQL,'postgres',''),$user));
    }

    public function testGetLines()
    {
        $this->assertNotEmpty(File::getLines('README.md'));
        $this->assertNotEmpty(getLines('README.md'));
    }

    public function testGetFileInfo()
    {
        $keys = ['database','username','password'];
        $values = ['forge','forger','xkjkl'];

        $fileKeys = File::getKeys('env','=');
        $fileValues = File::getValues('env','=');

        $this->assertNotEmpty($fileKeys);
        $this->assertEquals($keys,$fileKeys);

        $this->assertNotEmpty($fileValues);
        $this->assertEquals($values,$fileValues);


        $fileKeys = getKeys('env','=');
        $fileValues = getValues('env','=');

        $this->assertNotEmpty($fileKeys);
        $this->assertEquals($keys,$fileKeys);

        $this->assertNotEmpty($fileValues);
        $this->assertEquals($values,$fileValues);
    }
}

