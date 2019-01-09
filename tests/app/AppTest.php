<?php
namespace tests\base;

use Exception;
use Imperium\Bases\Base;
use Imperium\Collection\Collection;
use Imperium\Connexion\Connect;
use Imperium\Imperium;
use Imperium\Json\Json;
use Imperium\Model\Model;
use Imperium\Query\Query;
use Imperium\Router\Router;
use Imperium\Tables\Table;
use Imperium\Users\Users;
use Intervention\Image\ImageManager;
use Sinergi\BrowserDetector\Os;
use Sinergi\BrowserDetector\Device;
use Sinergi\BrowserDetector\Browser;
use Testing\DatabaseTest;
use Whoops\Run;

/**
 *
 */
class AppTest extends DatabaseTest
{


    public function test_url_methods()
    {

        $route = new Router('','','');

        $route->add('/',function (){},'home','GET');
        $route->add('/bedrooms',function (){},'bedrooms','GET');
        $route->add('/salon',function (){},'salon','GET');

        $this->assertEquals('/salon',url('salon'));
        $this->assertEquals('/',url('home'));
        $this->assertEquals('/bedrooms',url('bedrooms'));

        $this->assertIsCallable(method('salon'));
        $this->assertIsCallable(method('home'));
        $this->assertIsCallable(method('bedrooms'));
        $this->expectException(Exception::class);
        url('a');
        url('b');
        method('a');
        method('b');
    }

    public function test_apps()
    {
        $a = apps(Connect::MYSQL,'root','zen','root',Connect::LOCALHOST,'dump','imperium','views',[],[],[]);
        $this->assertInstanceOf(Imperium::class,$a);
    }

    public function test_not_exist()
    {
        $this->assertTrue(not_exist(faker()->name));
        $this->assertFalse(not_exist('def'));
    }
    public function test_ago()
    {
        $this->assertNotEmpty(ago('fr',now()));
        $this->assertNotEmpty(ago('en',now()));
        $this->assertNotEmpty(ago('de',now()));
    }

    public function test_image()
    {
        $this->assertInstanceOf(ImageManager::class,image());
        $this->assertInstanceOf(ImageManager::class,image("imagick"));
    }
    public function test_loaded()
    {
        $this->assertTrue(mysql_loaded());
        $this->assertTrue(pgsql_loaded());
        $this->assertTrue(sqlite_loaded());
    }
    public function test_quote()
    {
        $word = "l'agent à été l'as du voyage d'affaire`";

        $this->assertNotEquals($word,quote($this->mysql()->connect(),$word));
        $this->assertNotEquals($word,quote($this->postgresql()->connect(),$word));
        $this->assertNotEquals($word,quote($this->sqlite()->connect(),$word));
    }


    public function test_register()
    {
        $form = secure_register_form('/', '127.0.0.1', '127.0.0.1', 'username', 'username will be use','username can be empty', 'email', 'email will be use', 'email can be empty', 'password', 'password will be use', 'password not be empty', 'confirm the password','create account', 'register', true,['fr' => 'French','en' => 'English' ],
                                        'select', 'lang will be use','select a lang', 'select a time zone', 'time zone will be use','time zone','az','btn-primary', fa('fas','fa-key'), fa('fas','fa-user'), fa('fas','fas-envelope'),fa('fas','fa-user-plus'), fa('fas', 'fa-globe'), '');

        $this->assertContains('/',$form);
        $this->assertContains('username will be use',$form);
        $this->assertContains('time zone will be use',$form);
        $this->assertContains('az',$form);
        $this->assertContains('placeholder="username"',$form);
        $this->assertContains('placeholder="email"',$form);
        $this->assertContains('placeholder="email"',$form);
        $this->assertContains('placeholder="password"',$form);
        $this->assertContains('placeholder="confirm the password"',$form);
        $this->assertContains('<option value="fr">French</option>',$form);
        $this->assertContains('<option value="en">English</option>',$form);
        $this->assertContains('<option value="">select</option>',$form);
        $this->assertContains('<option value="">select a time zone</option>',$form);
        $this->assertContains('<button type="submit" class="btn btn-primary" id="register" name="register">',$form);


        $form = secure_register_form('/', '127.0.0.a1', '127.0.0.1', 'username', 'username will be use','username can be empty', 'email', 'email will be use', 'email can be empty', 'password', 'password will be use', 'password not be empty', 'confirm the password','create account', 'register', true,['fr' => 'french','en' => 'English' ],
'select', 'lang will be use','select a lang', 'select a time zone', 'time zone will be use','time zone','az','btn btn-primary', fa('fas','fa-key'), fa('fas','fa-user'), fa('fas','fas-envelope'),fa('fas','fa-user-plus'), fa('fas', 'fa-globe'), '');

        $this->assertEquals('',$form);
    }

    public function test_execute()
    {
        $this->assertTrue(execute($this->mysql()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
        $this->assertTrue(execute($this->postgresql()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
        $this->assertTrue(execute($this->sqlite()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));

    }
    public function test_req()
    {
        $this->assertNotEmpty(req($this->mysql()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
        $this->assertNotEmpty(req($this->postgresql()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
        $this->assertNotEmpty(req($this->sqlite()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
    }
    public function test_assign()
    {
        $var = 'i am a';

        assign(false,$var," man");

        $this->assertEquals($var,$var);

        assign(true,$var," man");

        $this->assertEquals(" man",$var);
    }

    public function test_query()
    {
        $this->assertInstanceOf(Query::class,\query($this->mysql()->tables(),$this->mysql()->connect()));
        $this->assertInstanceOf(Query::class,\query($this->postgresql()->tables(),$this->postgresql()->connect()));
        $this->assertInstanceOf(Query::class,\query($this->sqlite()->tables(),$this->sqlite()->connect()));
        $this->assertInstanceOf(Query::class,\query($this->sqlite()->tables(),$this->sqlite()->connect()));

        $this->assertInstanceOf(Model::class,\model($this->mysql()->connect(),$this->mysql()->tables(),'model'));
        $this->assertInstanceOf(Model::class,\model($this->postgresql()->connect(),$this->postgresql()->tables(),'model'));
        $this->assertInstanceOf(Model::class,\model($this->sqlite()->connect(),$this->sqlite()->tables(),'model'));


        $this->assertInstanceOf(Table::class,table($this->mysql()->connect(),'model'));
        $this->assertInstanceOf(Table::class,table($this->postgresql()->connect(),'model'));
        $this->assertInstanceOf(Table::class,table($this->sqlite()->connect(),'model'));



    }



    public function test_today_and_future()
    {
        $this->assertNotEmpty(today());
        $this->assertNotEmpty(future('second',1));
        $this->assertNotEmpty(future('seconds',1));
        $this->assertNotEmpty(future('minute',1));
        $this->assertNotEmpty(future('minutes',1));
        $this->assertNotEmpty(future('hour',1));
        $this->assertNotEmpty(future('hours',1));
        $this->assertNotEmpty(future('day',1));
        $this->assertNotEmpty(future('days',1));
        $this->assertNotEmpty(future('week',1));
        $this->assertNotEmpty(future('weeks',1));
        $this->assertNotEmpty(future('month',1));
        $this->assertNotEmpty(future('months',1));
        $this->assertNotEmpty(future('year',1));
        $this->assertNotEmpty(future('years',1));
        $this->assertNotEmpty(future('centuries',1));
        $this->assertNotEmpty(future('century',1));
        $this->assertNotEmpty(future('a',1));
    }

    public function test_lines()
    {
        $this->assertNotEmpty(lines('README.md'));
    }

    public function test_slug()
    {
        $this->assertEquals('linux-is-better',\slug('LINUX IS BETTER'));
        $this->assertEquals('the-planet-is-dead',\slug('The planet IS DEAD'));
        $this->assertEquals('the-planet-is-dead',\slug('The*planet*IS*DEAD','*'));
        $this->assertEquals('the-planet-is-dead',\slug('The--planet--IS--DEAD','--'));
    }

    public function test_pair()
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

    public function test_equal()
    {
        $this->assertTrue(equal('a','a'));
        $this->assertTrue(equal(10,10))  ;
        $this->assertFalse(equal(5,3));
        $this->assertFalse(equal('om','psg'));
    }

    public function test_equal_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        equal('a','a',true,$msg);
        equal(true,true,true,$msg);
        equal(false,false,true,$msg);
        equal(1,1,true,$msg);
    }


    public function test_is_not_false()
    {
        $this->assertTrue(is_not_false(true));
        $this->assertTrue(is_not_false('a'));
        $this->assertTrue(is_not_false(5));
        $this->assertFalse(is_not_false(false));
    }

    public function test_is_not_false_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        is_not_false('a',true,$msg);
        is_not_false(5,true,$msg);
        is_not_false(true,true,$msg);

    }


    public function test_is_not_true()
    {
        $this->assertTrue(is_not_true(false));
        $this->assertTrue(is_not_true('a'));
        $this->assertTrue(is_not_true(5));
        $this->assertFalse(is_not_true(true));
    }

    public function test_query_result()
    {
        $sql = '';
        $result = query_result($this->mysql()->model(), execute_query($this->mysql(), Query::SELECT, 'id', Query::SUPERIOR, 4, 'imperium', 'btn btn-primary', 'update', 'id', 'desc', $sql),"Success", 'Result empty', 'Table empty', $sql);
        $this->assertNotEmpty($result);
        $this->assertNotEmpty($sql);
    }
    public function test_is_not_true_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        is_not_true(false,true,$msg);
        is_not_true(5,true,$msg);
        is_not_true('adza',true,$msg);
    }



    public function test_is_false()
    {
        $this->assertTrue(is_false(false));
        $this->assertFalse(is_false(true));
    }

    public function test_is_false_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        is_false(false,true,$msg);

    }


    public function test_is_true()
    {
        $this->assertTrue(is_true(true));
        $this->assertFalse(is_true(false));
    }

    public function test_is_true_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        is_true(true,true,$msg);
    }


    public function test_different()
    {
        $this->assertTrue(different(true,false));
        $this->assertTrue(different(5,false));
        $this->assertTrue(different(5,52));
        $this->assertFalse(different(false,false));
    }

    public function test_different_exe()
    {
        $msg = "matrix";

        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);

        different(false,true,true,$msg);
        different(5,true,true,$msg);
        different(5,8,true,$msg);
    }


    public function test_base_to_json()
    {
        $this->assertTrue(bases_to_json($this->mysql()->bases(),'app.json','bases'));
        $this->assertTrue(bases_to_json($this->postgresql()->bases(),'app.json','bases'));
    }

    public function test_user_to_json()
    {
        $this->assertTrue(users_to_json($this->mysql()->users(),'app.json','bases'));
        $this->assertTrue(users_to_json($this->postgresql()->users(),'app.json','bases'));
    }

    public function test_table_to_json()
    {
        $this->assertTrue(tables_to_json($this->mysql()->tables(),'app.json','bases'));
        $this->assertTrue(tables_to_json($this->postgresql()->tables(),'app.json','bases'));
        $this->assertTrue(tables_to_json($this->sqlite()->tables(),'app.json','bases'));
    }

    public function test_sql_to_jssn()
    {
        $this->assertTrue(sql_to_json($this->mysql()->connect(),'app.json',["show databases","show tables","select host, User from mysql.user",$this->mysql()->query()->mode(Query::SELECT)->from('base')->where('id',Query::INFERIOR,5)->sql()],['bases','tables',"user","base_records"]));
        $this->assertTrue(sql_to_json($this->postgresql()->connect(),'app.json',[$this->postgresql()->query()->mode(Query::SELECT)->from('base')->where('id',Query::INFERIOR,5)->sql()],["base_records"]));
        $this->assertTrue(sql_to_json($this->sqlite()->connect(),'app.json',[$this->sqlite()->query()->mode(Query::SELECT)->from('base')->where('id',Query::INFERIOR,5)->sql()],["base_records"]));
    }

    public function test_length()
    {
        $this->assertEquals(5,length('trois'));
        $this->assertEquals(4,length([1,2,3,4]));
    }

    public function test_connect_instance()
    {
        $this->assertInstanceOf(Connect::class,\connect(Connect::MYSQL,'zen','root','root',Connect::LOCALHOST,'dump'));
        $this->assertInstanceOf(Connect::class,\connect(Connect::POSTGRESQL,'zen','postgres','postgres',Connect::LOCALHOST,'dump'));
        $this->assertInstanceOf(Connect::class,\connect(Connect::SQLITE,'zen','','',Connect::LOCALHOST,'dump'));
    }
    public function test_json_instance()
    {
        $this->assertInstanceOf(Json::class,json('app.json'));
    }
    public function test_collection_instance()
    {
        $this->assertInstanceOf(Collection::class,collection());
    }

    public function test_def()
    {
        $a ='';
        $this->assertFalse(def($a));
        $this->assertTrue(not_def($a));
        $a = 'aaz';
        $this->assertTrue(def($a));
        $this->assertFalse(not_def($a));
    }
    public function test_zones()
    {
        $this->assertContains('Europe/Paris',zones(''));
        $this->assertContains('a',zones('a'));
    }

    public function test_table_select()
    {
        $select = tables_select('base',$this->mysql()->tables(),[],'?=','csrf','');
        $this->assertContains('base',$select);
        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('csrf',$select);

        $select = tables_select('base',$this->postgresql()->tables(),[],'?=','csrf','');
        $this->assertContains('base',$select);
        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('csrf',$select);

        $select = tables_select('base',$this->sqlite()->tables(),[],'?=','csrf','');
        $this->assertContains('base',$select);
        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('csrf',$select);

    }

    public function test_true_or_false()
    {
        $this->assertNotEmpty(true_or_false());
    }
    public function test_users_select()
    {
        $select = users_select($this->mysql()->users(),[],'?=','','choose',false);

        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('root',$select);

        $select = users_select($this->mysql()->users(),[],'?=','root','choose',true);
        $this->assertContains('location',$select);

        $select = users_select($this->postgresql()->users(),[],'?=','root','choose',false);

        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('postgres',$select);

        $select = users_select($this->postgresql()->users(),[],'?=','root','choose',true);
        $this->assertContains('location',$select);

    }

    /**
     * @throws Exception
     */
    public function test_execute_query()
    {
        $sql = '';
        $this->assertTrue(execute_query($this->mysql(),Query::DELETE,'id',Query::EQUAL,5,'','','','','',$sql));
        $this->assertEquals("DELETE FROM imperium WHERE id = 5",$sql);
        $this->assertCount(0,execute_query($this->mysql(),Query::UPDATE,'id',Query::EQUAL,5,'','','','id','asc',$sql));
        $this->assertCount(1,execute_query($this->mysql(),Query::UPDATE,'id',Query::EQUAL,1,'','','','id','asc',$sql));
        $this->assertCount(2,execute_query($this->mysql(),Query::UPDATE,'id',Query::INFERIOR_OR_EQUAL,2,'','','','id','asc',$sql));

        $this->assertTrue(execute_query($this->postgresql(),Query::DELETE,'id',Query::EQUAL,5,'','','','','',$sql));
        $this->assertEquals("DELETE FROM imperium WHERE id = 5",$sql);
        $this->assertCount(0,execute_query($this->postgresql(),Query::UPDATE,'id',Query::EQUAL,5,'','','','id','asc',$sql));
        $this->assertCount(1,execute_query($this->postgresql(),Query::UPDATE,'id',Query::EQUAL,1,'','','','id','asc',$sql));
        $this->assertCount(2,execute_query($this->postgresql(),Query::UPDATE,'id',Query::INFERIOR_OR_EQUAL,2,'','','','id','asc',$sql));

        $this->assertTrue(execute_query($this->sqlite(),Query::DELETE,'id',Query::EQUAL,5,'','','','','',$sql));
        $this->assertEquals("DELETE FROM imperium WHERE id = 5",$sql);
        $this->assertCount(0,execute_query($this->sqlite(),Query::UPDATE,'id',Query::EQUAL,5,'','','','id','asc',$sql));
        $this->assertCount(1,execute_query($this->sqlite(),Query::UPDATE,'id',Query::EQUAL,1,'','','','id','asc',$sql));
        $this->assertCount(2,execute_query($this->sqlite(),Query::UPDATE,'id',Query::INFERIOR_OR_EQUAL,2,'','','','id','asc',$sql));
    }
    public function test_base_select()
    {
        $select = bases_select($this->mysql()->bases(),[],'?=','','choose',false);

        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('zen',$select);

        $select = bases_select($this->mysql()->bases(),[],'?=','','choose',true);
        $this->assertContains('location',$select);

        $select = bases_select($this->postgresql()->bases(),[],'?=','','choose',false);

        $this->assertContains('?=',$select);
        $this->assertContains('/',$select);
        $this->assertContains('postgres',$select);

        $select = bases_select($this->postgresql()->bases(),[],'?=','','choose',true);
        $this->assertContains('location',$select);

    }

    public function test_html()
    {
        $html = ['title','article','aside','footer','header','h1','h2','h3','h4','h5','h6','nav','section','div','p','li','ol','ul','pre'];
        foreach($html as $elem)
        {
            $this->assertEquals("<$elem>a</$elem>",html($elem,'a'));
            $this->assertEquals("<$elem class=\"a\">a</$elem>",html($elem,'a','a'));
            $this->assertEquals("<$elem class=\"a\" id=\"a\">a</$elem>",html($elem,'a','a','a'));

        }

        $this->assertEquals('<link href="a" rel="stylesheet">',html('link','a'));
        $this->assertEquals('<pre><code>a</code></pre>',html('code','a'));
        $this->assertEquals('<meta csrf="fr-latin9">',html('meta','csrf="fr-latin9"'));
        $this->assertEquals('<img src="a">',html('img','a'));
        $this->assertEquals('<img src="a" class="a">',html('img','a','a'));

    }
    public function test_id()
    {
        $this->assertNotEmpty(id());
        $this->assertNotEmpty(id('a'));
    }


    public function test_submit()
    {
        $a = post('a');
        $this->assertFalse(submit('a'));
        $_POST['a'] = 'legend';
        $this->assertTrue(submit('a'));

        $a = get('a');
        $this->assertFalse(submit('a',false));
        $_GET['a'] = 'legend';
        $this->assertTrue(submit('a',false));
    }

    public function test_push()
    {
        $a = [];
        push($a,1,2,3);
        $this->assertEquals([1,2,3],$a);
    }

    public function test_stack()
    {
        $a = [];
        stack($a,1,2,3);
        $this->assertEquals([3,2,1],$a);
    }
    public function test_has()
    {
        $this->assertTrue(has(1,[2,1,3]));
        $this->assertTrue(has(50,[2,1,3,50]));
        $this->assertFalse(has(4,[2,1,3]));
        $this->assertFalse(has(5,[2,1,3,50]));
    }
    public function test_values()
    {
        $this->assertEquals(['i','am','a','god'],values([0=> 'i',1 => 'am',3 => 'a' , 4=> 'god']));
    }
    public function test_keys()
    {
        $this->assertEquals([0,1,3,4],keys([0=> 'i',1 => 'am',3 => 'a' , 4=> 'god']));
    }
    public function test_merge()
    {
        $a = [1,2,3];
        merge($a,[4,5],[6,7,8],[9,10]);
        $this->assertEquals([1,2,3,4,5,6,7,8,9,10],$a);
    }

    public function test_collation()
    {
        $this->assertNotEmpty(collation($this->mysql()->connect()));
        $this->assertNotEmpty(collation($this->postgresql()->connect()));
    }
    public function test_charset()
    {
        $this->assertNotEmpty(charset($this->mysql()->connect()));
        $this->assertNotEmpty(charset($this->postgresql()->connect()));
    }

    public function test_base()
    {
        $this->assertInstanceOf(Base::class,base($this->mysql()->connect(),$this->mysql()->tables()));
        $this->assertInstanceOf(Base::class,base($this->postgresql()->connect(),$this->postgresql()->tables()));

    }

    public function test_user()
    {
        $this->assertInstanceOf(Users::class,user($this->mysql()->connect()));
        $this->assertInstanceOf(Users::class,user($this->postgresql()->connect()));
    }

    public function test_pass()
    {
        $this->assertTrue(pass($this->mysql()->connect(),'root','root'));
        $this->assertTrue(pass($this->postgresql()->connect(),'postgres','postgres'));
    }

    public function test_os()
    {
        $this->assertInstanceOf(Os::class,os());
        $this->assertEquals(Os::UNKNOWN,os(true));
    }
    public function test_device()
    {
        $this->assertInstanceOf(Device::class,\device());
        $this->assertEquals(Device::UNKNOWN,device(true));
    }

    public function test_browser()
    {
        $this->assertInstanceOf(Browser::class,browser());
        $this->assertEquals(Browser::UNKNOWN,browser(true));
    }

    public function test_is_browser()
    {
        $this->assertFalse(is_browser('Firefox'));
    }

    public function test_is_mobile()
    {
        $this->assertFalse(is_mobile());
    }
    public function test_superior()
    {
        $this->assertTrue(\superior(1,0));
        $this->assertFalse(\superior(0,5));
    }

    public function test_superior_exe()
    {
        $msg = 'matrix';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);
        superior(5,2,true,$msg);
        superior(8,4,true,$msg);
    }


    public function test_superior_or_equal()
    {
        $this->assertTrue(superior_or_equal(1,0));
        $this->assertTrue(superior_or_equal(1,1));
        $this->assertFalse(superior_or_equal(0,5));
        $this->assertFalse(superior_or_equal(4,5));
    }

    public function test_superior_or_equal_exe()
    {
        $msg = 'matrix';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);
        superior_or_equal(5,2,true,$msg);
        superior_or_equal(5,5,true,$msg);
        superior_or_equal(8,4,true,$msg);
    }


    public function test_inferior()
    {
        $this->assertTrue(inferior(0,4));
        $this->assertTrue(inferior(1,5));
        $this->assertFalse(inferior(60,5));
        $this->assertFalse(inferior(50,5));
    }

    public function test_inferior_exe()
    {
        $msg = 'matrix';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);
        inferior(22,50,true,$msg);
        inferior(2,50,true,$msg);
    }


    public function test_inferior_or_equal()
    {
        $this->assertTrue(inferior_or_equal(1,1));
        $this->assertTrue(inferior_or_equal(1,5));
        $this->assertFalse(inferior_or_equal(5,0));
        $this->assertFalse(inferior_or_equal(10,5));
    }

    public function test_inferior_or_equal_exe()
    {
        $msg = 'matrix';
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($msg);
        inferior_or_equal(5,5,true,$msg);
        inferior_or_equal(5,50,true,$msg);
    }

    public function test_whoops()
    {
        $this->assertInstanceOf(Run::class,whoops());
    }
    public function test_value_before_a_key()
    {
        $a = [0 => "a",2 => 'b',3 => 'c',508 =>'d',4 => 'e' ];
        $this->assertEquals('a',before_key($a,2));
        $this->assertEquals('c',before_key($a,508));
        $this->assertEquals('d',before_key($a,4));
    }
}
