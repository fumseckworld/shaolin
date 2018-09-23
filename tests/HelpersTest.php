<?php

namespace  tests;


 
use Exception;

use Faker\Generator;
use Imperium\Bases\Base;
use Imperium\Connexion\Connect;
use Imperium\Imperium;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Tables\Table;
use PDO;
use Testing\DatabaseTest;
use Whoops\Run;

class HelpersTest extends DatabaseTest
{

    /**
     * @throws Exception
     */
    public function test_instance()
    {
        $this->assertInstanceOf(Imperium::class,$this->mysql());
        $this->assertInstanceOf(Imperium::class,$this->postgresql());
        $this->assertInstanceOf(Imperium::class,$this->sqlite());

        $mysql = instance(Connect::MYSQL,self::MYSQL_USER,$this->base,'',PDO::FETCH_OBJ,'dump',$this->table);
        $postgresql = instance(Connect::POSTGRESQL,self::POSTGRESQL_USER,$this->base,'',PDO::FETCH_OBJ,'dump',$this->table);
        $sqlite = instance(Connect::SQLITE,'',$this->base,'',PDO::FETCH_OBJ,'dump',$this->table);

        $this->assertInstanceOf(Imperium::class,$mysql);
        $this->assertInstanceOf(Imperium::class,$postgresql);
        $this->assertInstanceOf(Imperium::class,$sqlite);

        $this->assertEquals($this->base,$mysql->connect()->get_database());
        $this->assertEquals($this->base,$postgresql->connect()->get_database());
        $this->assertEquals($this->base,$sqlite->connect()->get_database());

        $this->assertEquals(PDO::FETCH_OBJ,$mysql->connect()->get_fetch_mode());
        $this->assertEquals(PDO::FETCH_OBJ,$postgresql->connect()->get_fetch_mode());
        $this->assertEquals(PDO::FETCH_OBJ,$sqlite->connect()->get_fetch_mode());

        $this->assertEquals(Connect::MYSQL,$mysql->connect()->get_driver());
        $this->assertEquals(Connect::POSTGRESQL,$postgresql->connect()->get_driver());
        $this->assertEquals(Connect::SQLITE,$sqlite->connect()->get_driver());

        $this->assertEquals(self::MYSQL_USER,$mysql->connect()->get_username());
        $this->assertEquals(self::POSTGRESQL_USER,$postgresql->connect()->get_username());


        $this->assertEmpty($mysql->connect()->get_password());
        $this->assertEmpty($postgresql->connect()->get_password());


    }
    /**
     * @throws \Exception
     */
    public function test_query_view()
    {

        $not_found = 'records was not found';
        $table_empty = 'the current table is empty';

        $code = query_view("index.php",$this->mysql()->model(),$this->mysql()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty);


        $this->assertContains('action="index.php"', $code);
        $this->assertNotContains($not_found, $code);
        $this->assertContains('expected"', $code);
        $this->assertContains('superior ', $code);
        $this->assertContains('superior or equal', $code);
        $this->assertContains('inferior', $code);
        $this->assertContains('inferior or equal', $code);
        $this->assertContains('different', $code);
        $this->assertContains('equal', $code);
        $this->assertContains('like', $code);
        $this->assertContains('remove', $code);
        $this->assertContains('select', $code);
        $this->assertContains('execute', $code);
        $this->assertContains('btn btn-primary', $code);

        $code = query_view("index.php",$this->postgresql()->model(),$this->postgresql()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty);


        $this->assertContains('action="index.php"', $code);

        $this->assertNotContains($not_found, $code);
        $this->assertContains('expected"', $code);
        $this->assertContains('superior ', $code);
        $this->assertContains('superior or equal', $code);
        $this->assertContains('inferior', $code);
        $this->assertContains('inferior or equal', $code);
        $this->assertContains('different', $code);
        $this->assertContains('equal', $code);
        $this->assertContains('like', $code);
        $this->assertContains('remove', $code);
        $this->assertContains('select', $code);
        $this->assertContains('execute', $code);
        $this->assertContains('btn btn-primary', $code);

        $code = query_view("index.php",$this->sqlite()->model(),$this->sqlite()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty);


        $this->assertContains('action="index.php"', $code);

        $this->assertNotContains($not_found, $code);
        $this->assertContains('expected"', $code);
        $this->assertContains('superior ', $code);
        $this->assertContains('superior or equal', $code);
        $this->assertContains('inferior', $code);
        $this->assertContains('inferior or equal', $code);
        $this->assertContains('different', $code);
        $this->assertContains('equal', $code);
        $this->assertContains('like', $code);
        $this->assertContains('remove', $code);
        $this->assertContains('select', $code);
        $this->assertContains('execute', $code);
        $this->assertContains('btn btn-primary', $code);


    }

    public function test_equal()
    {
        $this->assertTrue(equal(1,1));
        $this->assertTrue(equal(5,5));
        $this->assertTrue(equal("ale","ale"));
        $this->assertTrue(equal("",""));

        $this->assertFalse(equal(1,12));
        $this->assertFalse(equal(15,12));
        $this->assertFalse(equal("",12));
        $this->assertFalse(equal("","adz"));
        $this->assertFalse(equal("a","adz"));

    }

    public function test_append()
    {
        $code = '';
        append($code,'i ','am ','very ','happy');
        $this->assertEquals('i am very happy',$code);
    }

    public function test_different()
    {
        $this->assertTrue(different('','adz'));
        $this->assertTrue(different('','a'));
        $this->assertTrue(different('a','aadz'));

        $this->assertFalse(different('adz','adz'));
        $this->assertFalse(different('a','a'));
        $this->assertFalse(different('aadz','aadz'));
    }


    /**
     * @throws Exception
     */
    public function test_show()
    {
        $this->assertNotEmpty(show($this->mysql(),Imperium::MODE_ALL_TABLES));
        $this->assertNotEmpty(show($this->mysql(),Imperium::MODE_ALL_USERS));
        $this->assertNotEmpty(show($this->mysql(),Imperium::MODE_ALL_DATABASES));

        $this->assertNotEmpty(show($this->postgresql(),Imperium::MODE_ALL_TABLES));
        $this->assertNotEmpty(show($this->postgresql(),Imperium::MODE_ALL_USERS));
        $this->assertNotEmpty(show($this->postgresql(),Imperium::MODE_ALL_DATABASES));

        $this->assertNotEmpty(show($this->sqlite(),Imperium::MODE_ALL_TABLES));

    }

    /**
     *
     */
    public function test_whoops()
    {
        $this->assertInstanceOf(Run::class,whoops());
    }

    /**
     * @throws Exception
     */
    public function test_request()
    {
        $this->assertNotEmpty(req($this->mysql()->connect(),'SHOW DATABASES'));
        $this->assertNotEmpty(req($this->postgresql()->connect(),'SELECT * FROM patients'));
        $this->assertNotEmpty(req($this->sqlite()->connect(),'SELECT * FROM patients'));
    }

    /**
     * @throws Exception
     */
    public function test_model()
    {
        $this->assertInstanceOf(Model::class,\model($this->mysql()->connect(),$this->mysql()->tables(),$this->table));
        $this->assertInstanceOf(Model::class,\model($this->postgresql()->connect(),$this->postgresql()->tables(),$this->table));
        $this->assertInstanceOf(Model::class,\model($this->sqlite()->connect(),$this->sqlite()->tables(),$this->table));
    }

    /**
     * @throws Exception
     */
    public function test_table()
    {
        $this->assertInstanceOf(Table::class,\table($this->mysql()->connect()));
        $this->assertInstanceOf(Table::class,\table($this->postgresql()->connect()));
        $this->assertInstanceOf(Table::class,\table($this->sqlite()->connect()));
    }

    /**
     * @throws Exception
     */
    public function test_execute()
    {
        $this->assertTrue(execute($this->mysql()->connect(),'SHOW DATABASES'));
        $this->assertTrue(execute($this->postgresql()->connect(),'SELECT * FROM patients'));
        $this->assertTrue(execute($this->sqlite()->connect(),'SELECT * FROM patients'));
    }
    /**
     * @throws \Exception
     */
    public function test_execute_query()
    {

        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','!=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','<=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertTrue(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','<',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','>',1,$this->table,'btn btn-primary','update',''))->empty());

        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertTrue(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update',''))->empty());

        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertTrue(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update',''))->empty());

        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertTrue(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update',''))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update',''))->empty());


        $this->assertTrue(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::DELETE,'id','=',1,$this->table,'btn btn-primary','a',''));
        $this->assertEmpty(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a',''));


        $this->assertTrue(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::DELETE,'id','=',1,$this->table,'btn btn-primary','a',''));
        $this->assertEmpty(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a',''));


        $this->assertTrue(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::DELETE,'id','=',1,$this->table,'btn btn-primary','a',''));
        $this->assertEmpty(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a',''));

    }

    /**
     * @throws Exception 
     */
    public function test_length()
    {
        $data = 'je';
        $array = ['a','a'];
        $this->assertEquals(2,length($data));
        $this->assertEquals(2,length($array));
        $this->expectException(Exception::class);
        length(1);
        length(true);
        length(false);
        length(null);
    }
    /**
     * @throws \Exception
     */
    public function test_print_result()
    {
        $query = execute_query(2,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','azda','');


        $this->assertCount(1,$query);

        $query = execute_query(2,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','','');
        $this->assertCount(1,$query);

        $query = execute_query(2,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','','');
        $this->assertCount(1,$query);

    }

    public function test_merge()
    {
        $data = array();

        merge($data,[1,2,3],[4,5,6]);

        $this->assertEquals([1,2,3,4,5,6],$data);
    }

    public function test_stack()
    {
        $data = [];
        for ($i=0;$i<10;$i++)
        {
            stack($data,$i);
        }

      $this->assertEquals([9,8,7,6,5,4,3,2,1,0],$data);
    }

    public function test_def_and_not_def()
    {
        $a = 'define';
        $c ='';
        $x = null;
        $this->assertTrue(def($a));
        $this->assertTrue(not_def($x,$c));
    }

    public function test_zones()
    {
        $zone =zones('choose');
        $this->assertNotEmpty($zone);
        $this->assertContains('choose',$zone);
    }

    /**
     * @throws \Exception
     */
    public function test_tables_select()
    {

        $choose = 'select a table';
        $select = tables_select($this->mysql()->tables(),'imperium',$this->table,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->mysql()->tables(),'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->postgresql()->tables(),'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->postgresql()->tables(),'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);


        $select = tables_select($this->sqlite()->tables(),'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);


        $select = tables_select($this->sqlite()->tables(),'imperium',$this->table,$choose,true);

        $this->assertContains($choose,$select);
        $this->assertContains('location',$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);
    }

    /**
     * @throws \Exception
     */
    public function test_users_select()
    {

        $choose = 'select an user';
        $select = users_select($this->mysql()->users(),[],'imperium',$this->table,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->mysql()->users(),[],'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->postgresql()->users(),[],'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->postgresql()->users(),[],'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

    }

    /**
     * @throws \Exception
     */
    public function test_base_select()
    {

        $choose = 'select a database';
        $select = bases_select($this->mysql()->bases(),[],'imperium',$this->base,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->mysql()->bases(),[],'imperium',$this->base,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->postgresql()->bases(),[],'imperium',$this->base,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->postgresql()->bases(),[],'imperium',$this->base,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotEmpty($select);
    }

    public function test_global()
    {
        $this->assertEquals('',session('a'));
        $this->assertEquals('',cookie('a'));
        $this->assertEquals('',get('a'));
        $this->assertEquals('',post('a'));
        $this->assertEquals('',server('a'));
        $this->assertEquals('',files('a'));

        $_POST['a'] = 'a';
        $_GET['a'] = 'a';
        $_COOKIE['a'] = 'a';
        $_SESSION['a'] = 'a';
        $_SERVER['a'] = 'a';
        $_FILES['a'] = 'a';

        $this->assertEquals('a',session('a'));
        $this->assertEquals('a',cookie('a'));
        $this->assertEquals('a',get('a'));
        $this->assertEquals('a',post('a'));
        $this->assertEquals('a',server('a'));
        $this->assertEquals('a',files('a'));

    }

    /**
     * @throws Exception
     */
    public function test_html()
    {
        $content = faker()->text(20);
        $class = $this->class;

        for ($i=1;$i!=6;$i++)
            $this->assertEquals("<h$i>$content</h$i>",html("h$i",$content));

        $this->assertEquals('<p class="'.$class.'">'.$content.'</p>',html('p',$content,$class));
        $this->assertEquals('<div class="'.$class.'">'.$content.'</div>',html('div',$content,$class));
        $this->assertEquals('<ol class="'.$class.'">'.$content.'</ol>',html('ol',$content,$class));
        $this->assertEquals('<ul class="'.$class.'">'.$content.'</ul>',html('ul',$content,$class));
        $this->assertEquals('<li class="'.$class.'">'.$content.'</li>',html('li',$content,$class));
        $this->assertEquals('<link href="'.$content.'" rel="stylesheet">',html('link',$content));
        $this->assertEquals( '<meta '.$content.'>',html('meta',$content));
        $this->assertEquals( '<img src="'.$content.'" class="'.$class.'">',html('img',$content,$class));

        $this->assertNotContains($class,html('p',$content));
        $this->assertNotContains($class,html('div',$content));
        $this->assertNotContains($class,html('ol',$content));
        $this->assertNotContains($class,html('ul',$content));
        $this->assertNotContains($class,html('li',$content));
        $this->assertNotContains($class,html('img',$content));

        $id = faker()->text(5);
        $this->assertEquals('<p class="'.$class.'" id="'.$id.'">'.$content.'</p>',html('p',$content,$class,$id));
        $this->assertEquals('<div class="'.$class.'" id="'.$id.'">'.$content.'</div>',html('div',$content,$class,$id));
        $this->assertEquals('<ol class="'.$class.'" id="'.$id.'">'.$content.'</ol>',html('ol',$content,$class,$id));
        $this->assertEquals('<ul class="'.$class.'" id="'.$id.'">'.$content.'</ul>',html('ul',$content,$class,$id));
        $this->assertEquals('<li class="'.$class.'" id="'.$id.'">'.$content.'</li>',html('li',$content,$class,$id));


        $this->expectException(Exception::class);
        html('a',$content,$class);
        html($content,$class,"");
        html('',$class,$content);
    }

    /**
     * @throws Exception
     */
    public function test_login()
    {
        $login = login('index.php','login','username','password','login',$this->class,'login-id');
        $this->assertContains('placeholder="password"',$login);
        $this->assertContains('placeholder="username"',$login);
        $this->assertContains('id="login"',$login);
        $this->assertContains($this->class,$login);
        $this->assertContains('id="login-id"',$login);
        $this->assertContains('login"',$login);
    }

    /**
     * @throws Exception
     */
    public function test_root()
    {
        $this->assertInstanceOf(PDO::class,root(Connect::MYSQL,self::MYSQL_USER,self::MYSQL_PASS)->instance());
        $this->assertInstanceOf(PDO::class,root(Connect::POSTGRESQL,self::POSTGRESQL_USER,self::POSTGRESQL_PASS)->instance());

        $this->expectException(Exception::class);

        root(Connect::MYSQL,self::POSTGRESQL_USER,self::MYSQL_PASS)->instance();
        root(Connect::POSTGRESQL,self::MYSQL_USER,self::MYSQL_PASS)->instance();

    }


    /**
     * @throws Exception
     */
    public function test_pass()
    {
        $this->assertTrue(pass($this->mysql()->connect(),self::MYSQL_USER,self::MYSQL_PASS));

        $this ->assertTrue(pass($this->postgresql()->connect(),self::POSTGRESQL_USER,self::POSTGRESQL_PASS));

    }

    public function test_loaded()
    {
        $this->assertTrue(mysql_loaded());
        $this->assertTrue(postgresql_loaded());
        $this->assertTrue(sqlite_loaded());
    }

    public function test_exist()
    {
        $this->assertTrue(exist('html'));
        $this->assertFalse(exist('_e'));
        $this->assertFalse(exist('app'));
        $this->assertTrue(exist('_html'));
    }

    public function test_future()
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
    public function test_faker()
    {
        $this->assertInstanceOf(Generator::class,faker('fr'));
        $this->assertInstanceOf(Generator::class,faker('en'));
        $this->assertInstanceOf(Generator::class,faker('es'));
    }

    /**
     * @throws Exception
     */
    public function test_user_del()
    {
        $user = 'alexandra';

        $this->assertTrue(user_add($user,$user,'',$this->mysql()->connect()));
        $this->assertTrue(user_add($user,$user,'',$this->postgresql()->connect()));
        $this->assertFalse(user_add($user,$user,'',$this->sqlite()->connect()));

        $this->assertTrue(remove_users($this->mysql()->connect(),$user));
        $this->assertTrue(remove_users($this->postgresql()->connect(),$user));
        $this->assertFalse(remove_users($this->sqlite()->connect(),$user));
    }


    public function test_loaders()
    {
        $expected = '<script src="imperium.js"></script><script src="main.js"></script><script src="db.js"></script>';
        $loader = js_loader("imperium.js","main.js",'db.js');
        $this->assertEquals($expected,$loader);

        $expected = '<link href="app.css" rel="stylesheet"><link href="main.css" rel="stylesheet">';

        $loader = css_loader("app.css","main.css");
        $this->assertEquals("$expected",$loader);
    }

    /**
     * @throws Exception
     */
    public function test_sql()
    {
        $this->assertInstanceOf(Query::class,sql($this->table,$this->mysql()->query()));
        $this->assertInstanceOf(Query::class,sql($this->table,$this->postgresql()->query()));
        $this->assertInstanceOf(Query::class,sql($this->table,$this->sqlite()->query()));
        $this->assertInstanceOf(Query::class,query($this->mysql()->tables(),$this->mysql()->connect()));
        $this->assertInstanceOf(Query::class, query($this->postgresql()->tables(),$this->postgresql()->connect()));
        $this->assertInstanceOf(Query::class,query($this->sqlite()->tables(),$this->sqlite()->connect()));
    }

    /**
     * @throws Exception
     */
    public function test_collation()
    {
        $this->assertFalse(collection(collation($this->mysql()->connect()))->empty());
        $this->assertFalse(collection(collation($this->postgresql()->connect()))->empty());

    }

    public function test_id()
    {
        $this->assertNotEmpty(id());
        $this->assertNotEmpty(id('alexandra'));
        $this->assertNotEmpty(id(faker()->email));
        $this->assertNotEmpty(id(faker()->text()));
        $this->assertNotEmpty(id());
        $this->assertNotEmpty(id(time()));
        $this->assertNotEmpty(id(future('day',1)));
    }
    public function test_submit()
    {

        $this->assertFalse(submit(id()));
        $this->assertFalse(submit(id(),false));
        $_GET['a'] = 20;
        $_POST['a'] = 20;
        $this->assertTrue(submit('a'));
        $this->assertTrue(submit('a',false));
    }
    /**
     * @throws Exception
     */
    public function test_charset()
    {
        $this->assertFalse(collection(charset($this->mysql()->connect()))->empty());
        $this->assertFalse(collection(charset($this->postgresql()->connect()))->empty());

    }

    public function test_imperium_instance()
    {
        $this->assertInstanceOf(Imperium::class,$this->mysql());
        $this->assertInstanceOf(Imperium::class,$this->postgresql());
        $this->assertInstanceOf(Imperium::class,$this->sqlite());
    }

    public function test_import()
    {
        $this->assertNotEmpty(bootstrap_js());
        $this->assertNotEmpty(bootswatch());
        $this->assertNotEmpty(foundation());
        $this->assertNotEmpty(fontAwesome());
    }

    /**
     * @throws \Cz\Git\GitException
     */
    public function test_current_branch()
    {
        $this->assertEquals('master',current_branch('.'));
    }

    public function test_array_prev()
    {
        $array = array(1 => 2,3=> 4,5=> 6,7=>8,9=> 10);

        $this->assertEquals(8,array_prev($array,9));
        $this->assertEquals(6,array_prev($array,7));
        $this->assertEquals(4,array_prev($array,5));
        $this->assertEquals(2,array_prev($array,3));
        $this->assertEquals(null,array_prev($array,1));
    }

    public function test_lines()
    {
        $this->assertNotEmpty(lines('composer.json'));

        $this->assertNotEmpty(file_keys('composer.json',','));

        $this->assertNotEmpty(file_values('composer.json',','));

        $this->assertNotEmpty(lines('README.md'));

        $this->assertNotEmpty(file_keys('README.md','#'));

        $this->assertNotEmpty(file_values('README.md','#'));
    }

    /**
     * @throws Exception
     */
    public function test_dumper()
    {
        $this->assertTrue(dumper($this->mysql()->connect()));
        $this->assertTrue(dumper($this->postgresql()->connect()));
        $this->assertTrue(dumper($this->sqlite()->connect()));

        $this->assertTrue(dumper($this->mysql()->connect(),false,$this->table));
        $this->assertTrue(dumper($this->postgresql()->connect(),false,$this->table));
        $this->assertTrue(dumper($this->sqlite()->connect(),false,$this->table));

    }

    public function test_not_in()
    {
        $data = array('red','apple','orange','purple','green');

        $this->assertTrue(not_in($data,'alex'));
        $this->assertTrue(not_in($data,'blue'));
        $this->assertTrue(not_in($data,'cyan'));

        $this->assertFalse(not_in($data,'red'));
        $this->assertFalse(not_in($data,'apple'));
        $this->assertFalse(not_in($data,'orange'));
        $this->assertFalse(not_in($data,'purple'));
        $this->assertFalse(not_in($data,'green'));
    }

    /**
     * @throws Exception
     */
    public function test_bases()
    {
        $this->assertInstanceOf(Base::class,\base($this->mysql()->connect()));
        $this->assertInstanceOf(Base::class,\base($this->postgresql()->connect()));
        $this->assertInstanceOf(Base::class,\base($this->sqlite()->connect()));
    }
}
