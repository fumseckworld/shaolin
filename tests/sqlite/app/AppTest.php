<?php
namespace Testing\sqlite\app {

    use Exception;
    use Imperium\Bases\Base;
    use Imperium\Collection\Collection;
    use Imperium\Connexion\Connect;
    use Imperium\App;
    use Imperium\Json\Json;
    use Imperium\Model\Model;
    use Imperium\Query\Query;
    use Imperium\Tables\Table;
    use Imperium\Users\Users;
    use Intervention\Image\ImageManager;
    use Sinergi\BrowserDetector\Os;
    use Sinergi\BrowserDetector\Device;
    use Sinergi\BrowserDetector\Browser;
    use Testing\DatabaseTest;
    use Twig_Environment;
    use Whoops\Run;

    /**
     *
     */
    class AppTest extends DatabaseTest
    {


        /**
         * @throws Exception
         */
        public function test_apps()
        {

            $this->assertInstanceOf(App::class,app());
        }

        /**
         *
         */
        public function test_env()
        {
            $this->sqlite()->env()->load();
            $this->assertEquals('mysql',env('driver'));

        }

        /**
         *
         */
        public function test_not_exist()
        {
            $this->assertTrue(not_exist(faker()->name));
            $this->assertFalse(not_exist('def'));
        }

        /**
         *
         */
        public function test_ago()
        {
            $this->assertNotEmpty(ago('fr',now()));
            $this->assertNotEmpty(ago('en',now()));
            $this->assertNotEmpty(ago('de',now()));
        }

        /**
         *
         */
        public function test_image()
        {
            $this->assertInstanceOf(ImageManager::class,image());
            $this->assertInstanceOf(ImageManager::class,image("imagick"));
        }

        /**
         *
         */
        public function test_loaded()
        {
            $this->assertTrue(sqlite_loaded());
            $this->assertTrue(pgsql_loaded());
            $this->assertTrue(sqlite_loaded());
        }

        /**
         * @throws Exception
         */
        public function test_quote()
        {
            $word = "l'agent à été l'as du voyage d'affaire`";

            $this->assertNotEquals($word,quote($this->sqlite()->connect(),$word));
            $this->assertNotEquals($word,quote($this->postgresql()->connect(),$word));
            $this->assertNotEquals($word,quote($this->sqlite()->connect(),$word));
        }


        /**
         * @throws Exception
         */
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

        /**
         * @throws Exception
         */
        public function test_execute()
        {
            $this->assertTrue(execute($this->sqlite()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));

        }

        /**
         * @throws Exception
         */
        public function test_req()
        {
            $this->assertNotEmpty(req($this->sqlite()->connect(),"SELECT * FROM model","SELECT * FROM base","SELECT * FROM helpers"));
        }

        /**
         *
         */
        public function test_assign()
        {
            $var = 'i am a';

            assign(false,$var," man");

            $this->assertEquals($var,$var);

            assign(true,$var," man");

            $this->assertEquals(" man",$var);
        }

        /**
         * @throws Exception
         */
        public function test_query()
        {
            $this->assertInstanceOf(Query::class,\query($this->sqlite()->table(),$this->sqlite()->connect()));

            $this->assertInstanceOf(Model::class,\model($this->sqlite()->connect(),$this->sqlite()->table()));


            $this->assertInstanceOf(Table::class,table($this->sqlite()->connect()));



        }

        /**
         *
         */
        public function test_twig()
        {
            $this->assertInstanceOf(Twig_Environment::class,twig('views',[]));
        }

        /**
         * @throws Exception
         */
        public function test_awesome()
        {
            $this->assertNotEmpty(awesome());
            $this->assertNotEmpty(foundation());
            $this->assertNotEmpty(bootswatch('lumen'));
            $this->assertNotEmpty(bootswatch('bootstrap'));
        }

        /**
         *
         */
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

        /**
         *
         */
        public function test_lines()
        {
            $this->assertNotEmpty(lines('README.md'));
        }

        /**
         *
         */
        public function test_slug()
        {
            $this->assertEquals('linux-is-better',\slug('LINUX IS BETTER'));
            $this->assertEquals('the-planet-is-dead',\slug('The planet IS DEAD'));
            $this->assertEquals('the-planet-is-dead',\slug('The*planet*IS*DEAD','*'));
            $this->assertEquals('the-planet-is-dead',\slug('The--planet--IS--DEAD','--'));
        }

        /**
         *
         */
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

        /**
         * @throws Exception
         */
        public function test_equal()
        {
            $this->assertTrue(equal('a','a'));
            $this->assertTrue(equal(10,10))  ;
            $this->assertFalse(equal(5,3));
            $this->assertFalse(equal('om','psg'));
        }

        /**
         * @throws Exception
         */
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


        /**
         * @throws Exception
         */
        public function test_is_not_false()
        {
            $this->assertTrue(is_not_false(true));
            $this->assertTrue(is_not_false('a'));
            $this->assertTrue(is_not_false(5));
            $this->assertFalse(is_not_false(false));
        }

        /**
         * @throws Exception
         */
        public function test_is_not_false_exe()
        {
            $msg = "matrix";

            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);

            is_not_false('a',true,$msg);
            is_not_false(5,true,$msg);
            is_not_false(true,true,$msg);

        }


        /**
         * @throws Exception
         */
        public function test_is_not_true()
        {
            $this->assertTrue(is_not_true(false));
            $this->assertTrue(is_not_true('a'));
            $this->assertTrue(is_not_true(5));
            $this->assertFalse(is_not_true(true));
        }


        /**
         * @throws Exception
         */
        public function test_is_not_true_exe()
        {
            $msg = "matrix";

            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);

            is_not_true(false,true,$msg);
            is_not_true(5,true,$msg);
            is_not_true('adza',true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_is_false()
        {
            $this->assertTrue(is_false(false));
            $this->assertFalse(is_false(true));
        }

        /**
         * @throws Exception
         */
        public function test_is_false_exe()
        {
            $msg = "matrix";

            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);

            is_false(false,true,$msg);

        }


        /**
         * @throws Exception
         */
        public function test_is_true()
        {
            $this->assertTrue(is_true(true));
            $this->assertFalse(is_true(false));
        }

        /**
         * @throws Exception
         */
        public function test_is_true_exe()
        {
            $msg = "matrix";

            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);

            is_true(true,true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_different()
        {
            $this->assertTrue(different(true,false));
            $this->assertTrue(different(5,false));
            $this->assertTrue(different(5,52));
            $this->assertFalse(different(false,false));
        }

        /**
         * @throws Exception
         */
        public function test_different_exe()
        {
            $msg = "matrix";

            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);

            different(false,true,true,$msg);
            different(5,true,true,$msg);
            different(5,8,true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_base_to_json()
        {
            $this->expectException(Exception::class);
            bases_to_json($this->sqlite()->bases(),'app.json','bases');
        }

        /**
         * @throws Exception
         */
        public function test_user_to_json()
        {
            $this->expectException(Exception::class);
            users_to_json($this->sqlite()->users(),'app.json','bases');
        }

        /**
         * @throws Exception
         */
        public function test_table_to_json()
        {
            $this->assertTrue(tables_to_json($this->sqlite()->table(),'app.json','bases'));
        }

        /**
         * @throws Exception
         */
        public function test_sql_to_json()
        {
            $this->assertTrue(sql_to_json($this->sqlite()->connect(),'app.json',[$this->sqlite()->query()->mode(Query::SELECT)->from('base')->where('id',Query::INFERIOR,5)->sql()],["base_records"]));
        }

        /**
         * @throws Exception
         */
        public function test_length()
        {
            $this->assertEquals(5,length('trois'));
            $this->assertEquals(4,length([1,2,3,4]));
        }

        /**
         * @throws Exception
         */
        public function test_connect_instance()
        {
            $this->assertInstanceOf(Connect::class,\connect(Connect::SQLITE,'zen.sqlite3','','',Connect::LOCALHOST,'dump'));
        }

        /**
         *
         */
        public function test_json_instance()
        {
            $this->assertInstanceOf(Json::class,json('app.json'));
        }

        /**
         *
         */
        public function test_collection_instance()
        {
            $this->assertInstanceOf(Collection::class,collection());
        }

        /**
         *
         */
        public function test_def()
        {
            $a ='';
            $this->assertFalse(def($a));
            $this->assertTrue(not_def($a));
            $a = 'aaz';
            $this->assertTrue(def($a));
            $this->assertFalse(not_def($a));
        }

        /**
         *
         */
        public function test_zones()
        {
            $this->assertContains('Europe/Paris',zones(''));
            $this->assertContains('a',zones('a'));
        }

        /**
         * @throws Exception
         */
        public function test_table_select()
        {
            $select = tables_select('base',$this->sqlite()->table(),[],'?=','');
            $this->assertContains('base',$select);
            $this->assertContains('?=',$select);
            $this->assertContains('/',$select);

            $select = tables_select('base',$this->postgresql()->table(),[],'?=','');
            $this->assertContains('base',$select);
            $this->assertContains('?=',$select);
            $this->assertContains('/',$select);

            $select = tables_select('base',$this->sqlite()->table(),[],'?=','');
            $this->assertContains('base',$select);
            $this->assertContains('?=',$select);
            $this->assertContains('/',$select);

        }

        /**
         *
         */
        public function test_true_or_false()
        {
            $this->assertNotEmpty(true_or_false(Connect::POSTGRESQL));

        }

        /**
         * @throws Exception
         */
        public function test_users_select()
        {
            $this->expectException(Exception::class);
            users_select($this->sqlite()->users(),[],'?=','','choose',false);


        }

        /**
         * @throws Exception
         */
        public function test_base_select()
        {

            $this->expectException(Exception::class);
            bases_select($this->sqlite()->bases(),[],'?=','','choose',false);

        }

        /**
         * @throws Exception
         */
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

        /**
         *
         */
        public function test_id()
        {
            $this->assertNotEmpty(id());
            $this->assertNotEmpty(id('a'));
        }


        /**
         *
         */
        public function test_submit()
        {

            $this->assertFalse(submit('a'));
            $_POST['a'] = 'legend';
            $this->assertTrue(submit('a'));

            $this->assertFalse(submit('a',false));
            $_GET['a'] = 'legend';
            $this->assertTrue(submit('a',false));
        }

        /**
         *
         */
        public function test_push()
        {
            $a = [];
            push($a,1,2,3);
            $this->assertEquals([1,2,3],$a);
        }

        /**
         *
         */
        public function test_stack()
        {
            $a = [];
            stack($a,1,2,3);
            $this->assertEquals([3,2,1],$a);
        }

        /**
         *
         */
        public function test_has()
        {
            $this->assertTrue(has(1,[2,1,3]));
            $this->assertTrue(has(50,[2,1,3,50]));
            $this->assertFalse(has(4,[2,1,3]));
            $this->assertFalse(has(5,[2,1,3,50]));
        }

        /**
         *
         */
        public function test_values()
        {
            $this->assertEquals(['i','am','a','god'],values([0=> 'i',1 => 'am',3 => 'a' , 4=> 'god']));
        }

        /**
         *
         */
        public function test_keys()
        {
            $this->assertEquals([0,1,3,4],keys([0=> 'i',1 => 'am',3 => 'a' , 4=> 'god']));
        }

        /**
         *
         */
        public function test_merge()
        {
            $a = [1,2,3];
            merge($a,[4,5],[6,7,8],[9,10]);
            $this->assertEquals([1,2,3,4,5,6,7,8,9,10],$a);
        }

        /**
         * @throws Exception
         */
        public function test_collation()
        {
            $this->assertEmpty(collation($this->sqlite()->connect()));

        }

        /**
         * @throws Exception
         */
        public function test_charset()
        {
            $this->assertEmpty(charset($this->sqlite()->connect()));
        }

        /**
         * @throws Exception
         */
        public function test_base()
        {
            $this->assertInstanceOf(Base::class,base($this->sqlite()->connect(),$this->sqlite()->table()));
            $this->assertInstanceOf(Base::class,base($this->postgresql()->connect(),$this->postgresql()->table()));

        }

        /**
         * @throws Exception
         */
        public function test_user()
        {
            $this->assertInstanceOf(Users::class,user($this->sqlite()->connect()));
            $this->assertInstanceOf(Users::class,user($this->postgresql()->connect()));
        }

        /**
         * @throws Exception
         */
        public function test_pass()
        {
            $this->expectException(Exception::class);
            pass($this->sqlite()->connect(),'root','root');
        }

        /**
         *
         */
        public function test_os()
        {
            $this->assertInstanceOf(Os::class,os());
            $this->assertEquals(Os::UNKNOWN,os(true));
        }

        /**
         *
         */
        public function test_device()
        {
            $this->assertInstanceOf(Device::class,\device());
            $this->assertEquals(Device::UNKNOWN,device(true));
        }

        /**
         *
         */
        public function test_browser()
        {
            $this->assertInstanceOf(Browser::class,browser());
            $this->assertEquals(Browser::UNKNOWN,browser(true));
        }

        /**
         *
         */
        public function test_is_browser()
        {
            $this->assertFalse(is_browser('Firefox'));
        }

        /**
         *
         */
        public function test_is_mobile()
        {
            $this->assertFalse(is_mobile());
        }

        /**
         * @throws Exception
         */
        public function test_superior()
        {
            $this->assertTrue(\superior(1,0));
            $this->assertFalse(\superior(0,5));
        }

        /**
         * @throws Exception
         */
        public function test_superior_exe()
        {
            $msg = 'matrix';
            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);
            superior(5,2,true,$msg);
            superior(8,4,true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_superior_or_equal()
        {
            $this->assertTrue(superior_or_equal(1,0));
            $this->assertTrue(superior_or_equal(1,1));
            $this->assertFalse(superior_or_equal(0,5));
            $this->assertFalse(superior_or_equal(4,5));
        }

        /**
         * @throws Exception
         */
        public function test_superior_or_equal_exe()
        {
            $msg = 'matrix';
            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);
            superior_or_equal(5,2,true,$msg);
            superior_or_equal(5,5,true,$msg);
            superior_or_equal(8,4,true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_inferior()
        {
            $this->assertTrue(inferior(0,4));
            $this->assertTrue(inferior(1,5));
            $this->assertFalse(inferior(60,5));
            $this->assertFalse(inferior(50,5));
        }

        /**
         * @throws Exception
         */
        public function test_inferior_exe()
        {
            $msg = 'matrix';
            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);
            inferior(22,50,true,$msg);
            inferior(2,50,true,$msg);
        }


        /**
         * @throws Exception
         */
        public function test_inferior_or_equal()
        {
            $this->assertTrue(inferior_or_equal(1,1));
            $this->assertTrue(inferior_or_equal(1,5));
            $this->assertFalse(inferior_or_equal(5,0));
            $this->assertFalse(inferior_or_equal(10,5));
        }

        /**
         * @throws Exception
         */
        public function test_inferior_or_equal_exe()
        {
            $msg = 'matrix';
            $this->expectException(Exception::class);
            $this->expectExceptionMessage($msg);
            inferior_or_equal(5,5,true,$msg);
            inferior_or_equal(5,50,true,$msg);
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
        public function test_value_before_a_key()
        {
            $a = [0 => "a",2 => 'b',3 => 'c',508 =>'d',4 => 'e' ];
            $this->assertEquals('a',before_key($a,2));
            $this->assertEquals('c',before_key($a,508));
            $this->assertEquals('d',before_key($a,4));
        }
    }
}