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
use Imperium\Databases\Eloquent\Bases\Base;
use Imperium\Databases\Eloquent\Connexion\Connexion;
use Imperium\Databases\Eloquent\Eloquent;
use Imperium\Databases\Eloquent\Users\Users;
use Imperium\Databases\Exception\IdentifierException;
use Imperium\Html\Bar\Icon;
use Imperium\Html\Canvas\Canvas;
use Imperium\Html\Form\Form;
use Imperium\Html\Records\Records;
use Intervention\Image\ImageManager;
use PDO;
use PHPUnit\Framework\TestCase;

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
        $this->assertEquals([], collation('sqlite', connect('sqlite', 'testing')));
    }

    public function testSql()
    {
        $this->assertEquals(5, count($this->sql->setPdo($this->pdo)->limit(5, 2)->where('id', '>=', 1)->getRecords()));

    }
    public function testRecords()
    {

        foreach ([Connexion::MYSQL, Connexion::POSTGRESQL] as $db)
        {
            switch ($db)
            {
                case Connexion::MYSQL:

                    $table = 'country';
                    $instance = table($db, $this->base, 'root', '', 'dump');
                    $pdo = connect($db, $this->base, 'root', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next');
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, "imperium", $pdo, 1, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains("imperium", $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);


                    $instance = table($db, $this->base, 'root', '', 'dump');
                    $pdo = connect($db,  $this->base, 'root', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next');
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, "imperium", $pdo, 1, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains( 'imperium' , $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);


                    $instance = table($db,  $this->base, 'root', '', 'dump');
                    $pdo = connect($db,  $this->base, 'root', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next','','','',Form::FOUNDATION);
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, 'imperium', $pdo, 2, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains('imperium', $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);


                    $instance = table($db,  $this->base, 'root', '', 'dump');
                    $pdo = connect($db,  $this->base, 'root', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next','','','',Form::FOUNDATION);
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, 'imperium', $pdo, 2, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains( 'imperium', $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);
                break;

                case Connexion::POSTGRESQL:
                    $table = 'country';
                    $instance = table($db,  $this->base, 'postgres', '', 'dump');
                    $pdo = connect($db,  $this->base, 'postgres', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next');
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, 'imperium', $pdo, 1, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains( 'imperium', $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);


                    $instance = table($db,  $this->base, 'postgres', '', 'dump');
                    $pdo = connect($db,  $this->base, 'postgres', '');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next','','','',2);
                    $records = records($db, 'table', $instance, $table, 'edit', 'delete', 'desc', 'edit', 'remove', 'btn btn-primary', 'btn btn-danger', fa('fa-edit'), fa('fa-trash-o'), 2, 1, 'imperium', $pdo, 2, 'search', 'are you sure ?', 'previous', 'next', 'update');

                    $this->assertStringStartsWith('<div class="row"', $records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit', $records);
                    $this->assertContains('delete', $records);
                    $this->assertContains('remove', $records);
                    $this->assertContains('btn btn-primary', $records);
                    $this->assertContains('btn btn-danger', $records);
                    $this->assertContains( 'imperium', $records);
                    $this->assertContains('search', $records);
                    $this->assertContains('are you sure ?', $records);
                    $this->assertContains('next', $records);
                    $this->assertContains('previous', $records);
                    $this->assertContains('update', $records);
                    $this->assertContains($pagination, $records);
                break;
            }
        }
    }
    public function testFile()
    {
        $this->assertEquals('',files('a'));
    }
    public function testRecordClass()
    {

        foreach ([Connexion::MYSQL,Connexion::POSTGRESQL] as $db)
        {
            switch ($db)
            {
                case Connexion::MYSQL:

                    $table = 'country';
                    $instance = table($db,$this->base,'root','','dump');
                    $pdo = connect($db,$this->base,'root','');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next');
                    $records = Records::show($db,'table',$instance,$table,'edit','delete','desc','edit','remove','btn btn-primary','btn btn-danger',fa('fa-edit'),fa('fa-trash-o'),2,1,'imperium',$pdo,1,'search','are you sure ?','previous','next','update');
                    $this->assertStringStartsWith('<div class="row"',$records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit',$records);
                    $this->assertContains('delete',$records);
                    $this->assertContains('remove',$records);
                    $this->assertContains('btn btn-primary',$records);
                    $this->assertContains('btn btn-danger',$records);
                    $this->assertContains('imperium',$records);
                    $this->assertContains('search',$records);
                    $this->assertContains('are you sure ?',$records);
                    $this->assertContains('next',$records);
                    $this->assertContains('previous',$records);
                    $this->assertContains('update',$records);
                    $this->assertContains($pagination,$records);
                break;

                case Connexion::POSTGRESQL:

                    $table = 'country';
                    $instance = table($db,$this->base,'postgres','','dump');
                    $pdo = connect($db,$this->base,'postgres','');
                    $pagination = pagination(2,"imperium",1,$instance->count($table),'previous','next');
                    $records = Records::show($db,'table',$instance,$table,'edit','delete','desc','edit','remove','btn btn-primary','btn btn-danger',fa('fa-edit'),fa('fa-trash-o'),2,1,'imperium',$pdo,1,'search','are you sure ?','previous','next','update');
                    $this->assertStringStartsWith('<div class="row"',$records);
                    $this->assertStringEndsWith('</p>', $records);
                    $this->assertContains('edit',$records);
                    $this->assertContains('delete',$records);
                    $this->assertContains('remove',$records);
                    $this->assertContains('btn btn-primary',$records);
                    $this->assertContains('btn btn-danger',$records);
                    $this->assertContains('imperium',$records);
                    $this->assertContains('search',$records);
                    $this->assertContains('are you sure ?',$records);
                    $this->assertContains('next',$records);
                    $this->assertContains('previous',$records);
                    $this->assertContains('update',$records);
                    $this->assertContains($pagination,$records);
                break;

            }

        }

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
        $this->assertEquals('',cookie('a'));
        $this->assertEquals('',session('a'));
        $this->assertEquals('',server('a'));
        $this->assertEquals('',get('a'));
        $this->assertEquals('',post('a'));
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

        $this->assertContains('works', table(Connexion::MYSQL, $this->base, 'root', '', '')->show());
        $this->assertContains('patients', table(Connexion::MYSQL, $this->base, 'root', '', '')->show());
        $this->assertContains('doctors', table(Connexion::MYSQL, $this->base, 'root', '', '')->show());


        $this->assertContains('public.works', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.patients', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.doctors', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());
        $this->assertContains('public.doctors', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->show());

        $this->assertEquals(true, table(Connexion::MYSQL, $this->base, 'root', '', '')->has());
        $this->assertEquals(true, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->has());
        $this->assertEquals(true, table(Connexion::SQLITE, $this->base, '', '', '')->has());

        $this->assertEquals(100, table(Connexion::MYSQL, $this->base, 'root', '', '')->count($this->table));
        $this->assertEquals(100, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->count($this->table));
        $this->assertEquals(99, table(Connexion::SQLITE, $this->base, '', '', '')->count($this->table));

        $this->assertContains('id', table(Connexion::MYSQL, $this->base, 'root', '', '')->setName($this->table)->getColumns());
        $this->assertContains( 'name', table(Connexion::MYSQL, $this->base, 'root', '', '')->setName($this->table)->getColumns());

        $this->assertContains('id', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumns());
        $this->assertContains('name', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumns());

        $this->assertContains('id', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumns());
        $this->assertContains( 'name', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumns());


        $this->assertContains( 'INTEGER', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'VARCHAR(255)', table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->getColumnsTypes());

        $this->assertContains( 'int(11)', table(Connexion::MYSQL, $this->base, 'root', '', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'varchar(255)', table(Connexion::MYSQL, $this->base, 'root', '', '')->setName($this->table)->getColumnsTypes());


        $this->assertContains( 'integer', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumnsTypes());
        $this->assertContains( 'character varying', table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->getColumnsTypes());


        $this->assertEquals(true, table(Connexion::SQLITE, $this->base, '', '', '') ->exist($this->table));
        $this->assertEquals(true, table(Connexion::MYSQL, $this->base, 'root', '', '') ->exist($this->table));
        $this->assertEquals(true, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '') ->exist("public.$this->table"));

        $this->assertEquals(false, table(Connexion::SQLITE, $this->base, '', '', '')->exist('alexandra'));
        $this->assertEquals(false, table(Connexion::MYSQL, $this->base, 'root', '', '')->exist('alexandra'));
        $this->assertEquals(false, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->exist('alexandra'));

        $this->assertEquals(false, table(Connexion::SQLITE, $this->base, '', '', '')->setName($this->table)->isEmpty());
        $this->assertEquals(false, table(Connexion::MYSQL, $this->base, 'root', '', '')->setName($this->table)->isEmpty());
        $this->assertEquals(false, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->setName($this->table)->isEmpty());

        $this->assertEquals(Connexion::SQLITE, table(Connexion::SQLITE, $this->base, '', '', '')->getDriver());
        $this->assertEquals(Connexion::POSTGRESQL, table(Connexion::POSTGRESQL, $this->base, 'postgres', '', '')->getDriver());
        $this->assertEquals(Connexion::MYSQL, table(Connexion::MYSQL, $this->base, 'root', '', '')->getDriver());

    }

    public function testUserDell()
    {
        $this->assertEquals(false, userDel(Connexion::SQLITE,$this->pdo, 'user'));

    }

    public function testUserAdd()
    {
        $this->assertEquals(false, userAdd(Connexion::SQLITE, 'username', 'pass', '', $this->pdo));
        $this->assertEquals(true, userAdd(Connexion::MYSQL, 'username', 'pass', '', root(Connexion::MYSQL)));
        $this->assertEquals(true, userAdd(Connexion::MYSQL, 'usernames', 'pass', '', root(Connexion::MYSQL)));
        $this->assertEquals(true, userDel(Connexion::MYSQL, root(Connexion::MYSQL),'usernames', 'username'));
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

    public function testFaGroup()
    {
        $this->assertContains('Gnu/Linux', faGroup([fa('fa-linux')], ['#linux'], ['Gnu/Linux'], ['btn btn-primary']));
        $this->assertContains('fa-linux', faGroup([fa('fa-linux')], ['#linux'], ['Gnu/Linux'], ['btn btn-primary']));
        $this->assertContains('btn btn-primary', faGroup([fa('fa-linux')], ['#linux'], ['Gnu/Linux'], ['btn btn-primary']));
        $this->assertContains('#linux', faGroup([fa('fa-linux')], ['#linux'], ['Gnu/Linux'], ['btn btn-primary']));
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
        $this->assertEquals(true, pass(Connexion::MYSQL, 'root', '', ''));
        $this->assertEquals(true, pass(Connexion::POSTGRESQL, 'postgres', '', ''));
    }

    public function testGenerateQrCode()
    {
        $secret = generateKey();
        $this->assertEquals("https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=otpauth%3A%2F%2Ftotp%2Fcompany%3Afumseck%3Fsecret%3D$secret%26issuer%3Dcompany", generateQrCode('company', 'fumseck', $secret));
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

    public function testCharset()
    {
        $this->assertEquals([], charset('sqlite', connect('sqlite', 'testing')));
    }

    public function testUser()
    {
        $this->assertInstanceOf(Users::class, user('mysql', 'a', 'a'));
        $this->assertInstanceOf(Users::class, user('pgsql', 'a', 'a'));
        $this->assertContains('root', user(Connexion::MYSQL, 'root', '')->show());
        $this->assertContains('postgres', user(Connexion::POSTGRESQL, 'postgres', '')->show());
        $this->assertEquals([], user(Connexion::SQLITE, 'adz', 'pass')->show());
    }

    /**
     * @throws IdentifierException
     */
    public function testShow()
    {
        $this->expectException(IdentifierException::class);

        show(Connexion::MYSQL, 'a', 'user', 'password', Eloquent::MODE_ALL_TABLES);
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
}
