<?php

namespace  tests;



use Exception;
use Faker\Generator;
use Imperium\Connexion\Connect;
use Imperium\Databases\Eloquent\Query\Query;
use PDO;
use Testing\DatabaseTest;

class HelpersTest extends DatabaseTest
{


    /**
     * @throws \Exception
     */
    public function test_query_view()
    {

        $not_found = 'records was not found';
        $table_empty = 'the current table is empty';

        $code = query_view(2,"index.php",$this->get_mysql()->model(),$this->get_mysql()->table(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute',$this->get_mysql()->class(),'record was removed successfully',$not_found,$table_empty);


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
        $this->assertContains($this->get_mysql()->class(), $code);

        $code = query_view(2,"index.php",$this->get_pgsql()->model(),$this->get_pgsql()->table(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute',$this->get_pgsql()->class(),'record was removed successfully',$not_found,$table_empty);


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
        $this->assertContains($this->get_mysql()->class(), $code);

        $code = query_view(2,"index.php",$this->get_sqlite()->model(),$this->get_sqlite()->table(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute',$this->get_sqlite()->class(),'record was removed successfully',$not_found,$table_empty);


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
        $this->assertContains($this->get_sqlite()->class(), $code);


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
     * @throws \Exception
     */
    public function test_execute_query()
    {
        $this->assertFalse(collection(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_mysql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','!=',1,$this->table,$this->get_mysql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','<=',1,$this->table,$this->get_mysql()->class(),'update',''))->isEmpty());
        $this->assertTrue(collection(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','<',1,$this->table,$this->get_mysql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','>',1,$this->table,$this->get_mysql()->class(),'update',''))->isEmpty());

        $this->assertFalse(collection(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_pgsql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','!=',1,$this->table,$this->get_pgsql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','<=',1,$this->table,$this->get_pgsql()->class(),'update',''))->isEmpty());
        $this->assertTrue(collection(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','<',1,$this->table,$this->get_pgsql()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','>',1,$this->table,$this->get_pgsql()->class(),'update',''))->isEmpty());

        $this->assertFalse(collection(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_sqlite()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','!=',1,$this->table,$this->get_sqlite()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','<=',1,$this->table,$this->get_sqlite()->class(),'update',''))->isEmpty());
        $this->assertTrue(collection(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','<',1,$this->table,$this->get_sqlite()->class(),'update',''))->isEmpty());
        $this->assertFalse(collection(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','>',1,$this->table,$this->get_sqlite()->class(),'update',''))->isEmpty());


        $this->assertTrue(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::DELETE,'id','=',1,$this->table,$this->get_mysql()->class(),'a',''));
        $this->assertEmpty(execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_mysql()->class(),'a',''));


        $this->assertTrue(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::DELETE,'id','=',1,$this->table,$this->get_pgsql()->class(),'a',''));
        $this->assertEmpty(execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_pgsql()->class(),'a',''));


        $this->assertTrue(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::DELETE,'id','=',1,$this->table,$this->get_sqlite()->class(),'a',''));
        $this->assertEmpty(execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_sqlite()->class(),'a',''));

    }

    /**
     * @throws \Exception
     */
    public function test_print_result()
    {
        $success = 'record was found';
        $failure = 'record was not found';


        $query = execute_query(10,$this->get_mysql()->model(), $this->get_mysql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_mysql()->class(),'update','');

        $this->assertEquals('<div class="alert alert-danger">'.$failure.'</div>',query_result($this->get_mysql()->model(),Query::SELECT,$query,$this->get_mysql()->columns(),$success,$failure,$failure));


        $query = execute_query(1,$this->get_pgsql()->model(), $this->get_pgsql()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_pgsql()->class(),'update','');

        $this->assertEquals('<div class="alert alert-danger">'.$failure.'</div>',query_result($this->get_pgsql()->model(),Query::SELECT,$query,$this->get_pgsql()->columns(),$success,$failure,$failure));


        $query = execute_query(1,$this->get_sqlite()->model(), $this->get_sqlite()->table(),Query::SELECT,'id','=',1,$this->table,$this->get_sqlite()->class(),'update','');

        $this->assertEquals('<div class="alert alert-danger">'.$failure.'</div>',query_result($this->get_sqlite()->model(),Query::SELECT,$query,$this->get_sqlite()->columns(),$success,$failure,$failure));


        $mysql_data = execute_query(10,$this->get_mysql()->model(), $this->get_mysql()->table(),Query::UPDATE,'id','=',5,$this->table,$this->get_mysql()->class(),'update','');
        $pgsql_data = execute_query(10,$this->get_pgsql()->model(), $this->get_pgsql()->table(),Query::UPDATE,'id','=',5,$this->table,$this->get_pgsql()->class(),'update','');
        $sqlite_data = execute_query(10,$this->get_sqlite()->model(), $this->get_sqlite()->table(),Query::UPDATE,'id','=',5,$this->table,$this->get_sqlite()->class(),'update','');

        $this->assertCount(1,$mysql_data);
        $this->assertCount(1,$pgsql_data);
        $this->assertCount(1,$sqlite_data);

        $query = execute_query(1,$this->get_mysql()->model(),$this->get_mysql()->table(),Query::DELETE,'id','=',2,$this->table,$this->get_mysql()->class(),'update','');

        $this->assertEquals('<div class="alert alert-success">'.$success.'</div>',query_result($this->get_mysql()->model(),Query::DELETE,$query,$this->get_mysql()->columns(),$success,$failure,$failure));

        $query = execute_query(1,$this->get_pgsql()->model(),$this->get_pgsql()->table(),Query::DELETE,'id','=',2,$this->table,$this->get_pgsql()->class(),'update','');

        $this->assertEquals('<div class="alert alert-success">'.$success.'</div>',query_result($this->get_pgsql()->model(),Query::DELETE,$query,$this->get_pgsql()->columns(),$success,$failure,$failure));

        $query = execute_query(1,$this->get_sqlite()->model(),$this->get_sqlite()->table(),Query::DELETE,'id','=',2,$this->table,$this->get_sqlite()->class(),'update','');

        $this->assertEquals('<div class="alert alert-success">'.$success.'</div>',query_result($this->get_sqlite()->model(),Query::DELETE,$query,$this->get_sqlite()->columns(),$success,$failure,$failure));

        $this->assertNotEmpty(query_result($this->get_mysql()->model(),Query::UPDATE,$mysql_data,$this->get_mysql()->columns(),$success,$failure,$failure));
        $this->assertNotEmpty(query_result($this->get_pgsql()->model(),Query::UPDATE,$mysql_data,$this->get_pgsql()->columns(),$success,$failure,$failure));
        $this->assertNotEmpty(query_result($this->get_sqlite()->model(),Query::UPDATE,$mysql_data,$this->get_sqlite()->columns(),$success,$failure,$failure));
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
        $select = tables_select($this->get_mysql()->table(),'imperium',$this->table,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->get_mysql()->table(),'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->get_pgsql()->table(),'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->get_pgsql()->table(),'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);


        $select = tables_select($this->get_sqlite()->table(),'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);


        $select = tables_select($this->get_sqlite()->table(),'imperium',$this->table,$choose,true);

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
        $select = users_select($this->get_mysql()->user(),'imperium',$this->table,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->get_mysql()->user(),'imperium',$this->table,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->get_pgsql()->user(),'imperium',$this->table,$choose,false);

        $this->assertNotContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->table,$select);
        $this->assertNotEmpty($select);

        $select = users_select($this->get_pgsql()->user(),'imperium',$this->table,$choose,true);
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
        $select = bases_select($this->get_mysql()->base(),'imperium',$this->base,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->get_mysql()->base(),'imperium',$this->base,$choose,true);
        $this->assertContains('location',$select);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->get_pgsql()->base(),'imperium',$this->base,$choose,false);
        $this->assertContains($choose,$select);
        $this->assertNotContains($this->base,$select);
        $this->assertNotContains('location',$select);
        $this->assertNotEmpty($select);

        $select = bases_select($this->get_pgsql()->base(),'imperium',$this->base,$choose,true);
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
        $this->assertTrue(pass($this->get_mysql()->connect(),self::MYSQL_USER,self::MYSQL_PASS));

        $this ->assertTrue(pass($this->get_pgsql()->connect(),self::POSTGRESQL_USER,self::POSTGRESQL_PASS));

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

        $this->assertTrue(user_add($user,$user,'',$this->get_mysql()->connect()));
        $this->assertTrue(user_add($user,$user,'',$this->get_pgsql()->connect()));
        $this->assertFalse(user_add($user,$user,'',$this->get_sqlite()->connect()));

        $this->assertTrue(remove_users($this->get_mysql()->connect(),$user));
        $this->assertTrue(remove_users($this->get_pgsql()->connect(),$user));
        $this->assertFalse(remove_users($this->get_sqlite()->connect(),$user));
    }


    public function test_loaders()
    {
        $scriptFirst = '<script src="app.js"></script>';
        $scriptSecond = '<script src="main.js"></script>';
        $loader = js_loader("app.js","main.js");
        $this->assertEquals("$scriptFirst$scriptSecond",$loader);

        $scriptFirst = '<link href="app.css" rel="stylesheet">';
        $scriptSecond = '<link href="main.css" rel="stylesheet">';
        $loader = css_loader("app.css","main.css");
        $this->assertEquals("$scriptFirst$scriptSecond",$loader);
    }

    /**
     * @throws Exception
     */
    public function test_sql()
    {
        $this->assertInstanceOf(Query::class,sql($this->table,$this->get_mysql()->query()));
        $this->assertInstanceOf(Query::class,sql($this->table,$this->get_pgsql()->query()));
        $this->assertInstanceOf(Query::class,sql($this->table,$this->get_sqlite()->query()));
        $this->assertInstanceOf(Query::class,query($this->get_mysql()->table(),$this->get_mysql()->connect()));
        $this->assertInstanceOf(Query::class, query($this->get_pgsql()->table(),$this->get_pgsql()->connect()));
        $this->assertInstanceOf(Query::class,query($this->get_sqlite()->table(),$this->get_sqlite()->connect()));
    }

    /**
     * @throws Exception
     */
    public function test_collation()
    {
        $this->assertFalse(collection(collation($this->get_mysql()->connect()))->isEmpty());
        $this->assertFalse(collection(collation($this->get_pgsql()->connect()))->isEmpty());

    }

    /**
     * @throws Exception
     */
    public function test_charset()
    {
        $this->assertFalse(collection(charset($this->get_mysql()->connect()))->isEmpty());
        $this->assertFalse(collection(charset($this->get_pgsql()->connect()))->isEmpty());

    }

}
