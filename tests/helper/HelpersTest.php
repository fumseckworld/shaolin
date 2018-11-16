<?php

namespace  tests\helper;


 
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


    public function setUp()
    {
        $this->table = 'helper';
    }

    public function test_is_pair()
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

    public function test_assign()
    {
        $request = '';
        assign(false,$request,"value");
        $this->assertEquals('',$request);

        assign(true,$request,"value");
        $this->assertEquals('value',$request);

        assign(false,$request,"adzvalue");
        $this->assertEquals('value',$request);
    }
    /**
     * @throws Exception
     */
    public function test_register()
    {
        $current_ip = '127.0.0.1';
        $username_placeholder = 'username';
        $username_success= 'username will be use';
        $username_fail = 'username cannot be empty';
        $email_placeholder = 'email address';
        $email_success = "email will be use";
        $email_fail = "email cannot  be empty";

        $password_placeholder = 'password';
        $password_success = "password will be use";
        $password_fail = "password cannot  be empty";
        $languages = ['fr' => 'French','es' => 'Spanish'];

        $form = register('a','a',$current_ip,$username_placeholder,$username_success,$username_fail,$email_placeholder, $email_success,$email_fail,$password_placeholder,$password_success,$password_fail,$password_placeholder,'create account',id());

        $this->assertEmpty($form);

        $form = register('a',$current_ip,$current_ip,$username_placeholder,$username_success,$username_fail,$email_placeholder, $email_success,$email_fail,$password_placeholder,$password_success,$password_fail,$password_placeholder,'create account',id());


        $this->assertNotContains('French',$form);
        $this->assertNotContains('Spanish',$form);

        $this->assertContains($username_placeholder,$form);
        $this->assertContains($username_success,$form);
        $this->assertContains($username_fail,$form);
        $this->assertContains($email_placeholder,$form);
        $this->assertContains($email_success,$form);
        $this->assertContains($email_fail,$form);
        $this->assertContains($password_placeholder,$form);
        $this->assertContains($password_success,$form);
        $this->assertContains($password_fail,$form);

        $choose_language = "choose a language";
        $choose_zone = "choose a zone";
        $lang_valid = "language will be used";
        $lang_fail ='languages must not be empty';
        $valid_zone = 'time zone will be used';
        $error_zone ='error';
        $form = register('a',$current_ip,$current_ip,$username_placeholder,$username_success,$username_fail,$email_placeholder, $email_success,$email_fail,$password_placeholder,$password_success,$password_fail,$password_placeholder,'create account',id(),true,$languages,$choose_language,$lang_valid,$lang_fail,$choose_zone,$valid_zone,$error_zone);


        $this->assertContains('French',$form);
        $this->assertContains('Spanish',$form);

        $this->assertContains($username_placeholder,$form);
        $this->assertContains($username_success,$form);
        $this->assertContains($username_fail,$form);
        $this->assertContains($email_placeholder,$form);
        $this->assertContains($email_success,$form);
        $this->assertContains($email_fail,$form);
        $this->assertContains($password_placeholder,$form);
        $this->assertContains($password_success,$form);
        $this->assertContains($password_fail,$form);
        $this->assertContains($choose_zone,$form);
        $this->assertContains($choose_language,$form);
        $this->assertContains($lang_valid,$form);
        $this->assertContains($lang_fail,$form);
        $this->assertContains($valid_zone,$form);
        $this->assertContains($error_zone,$form);

    }
    /**
     * @throws Exception
     */
    public function test_instance()
    {
        $this->assertInstanceOf(Imperium::class,$this->mysql());
        $this->assertInstanceOf(Imperium::class,$this->postgresql());
        $this->assertInstanceOf(Imperium::class,$this->sqlite());

        $mysql = instance(Connect::MYSQL,self::MYSQL_USER,$this->base,'root',PDO::FETCH_OBJ,'dump',$this->table);
        $postgresql = instance(Connect::POSTGRESQL,self::POSTGRESQL_USER,$this->base,'postgres',PDO::FETCH_OBJ,'dump',$this->table);
        $sqlite = instance(Connect::SQLITE,'',"zen.sqlite3",'',PDO::FETCH_OBJ,'dump',$this->table);

        $this->assertInstanceOf(Imperium::class,$mysql);
        $this->assertInstanceOf(Imperium::class,$postgresql);
        $this->assertInstanceOf(Imperium::class,$sqlite);

        $this->assertEquals($this->base,$mysql->connect()->get_database());
        $this->assertEquals($this->base,$postgresql->connect()->get_database());
        $this->assertEquals("zen.sqlite3",$sqlite->connect()->get_database());

        $this->assertEquals(PDO::FETCH_OBJ,$mysql->connect()->get_fetch_mode());
        $this->assertEquals(PDO::FETCH_OBJ,$postgresql->connect()->get_fetch_mode());
        $this->assertEquals(PDO::FETCH_OBJ,$sqlite->connect()->get_fetch_mode());

        $this->assertEquals(Connect::MYSQL,$mysql->connect()->get_driver());
        $this->assertEquals(Connect::POSTGRESQL,$postgresql->connect()->get_driver());
        $this->assertEquals(Connect::SQLITE,$sqlite->connect()->get_driver());

        $this->assertEquals(self::MYSQL_USER,$mysql->connect()->get_username());
        $this->assertEquals(self::POSTGRESQL_USER,$postgresql->connect()->get_username());


        $this->assertNotEmpty($mysql->connect()->get_password());
        $this->assertNotEmpty($postgresql->connect()->get_password());


    }

    /**
     * @throws Exception
     */
    public function test_superior()
    {

        $this->assertTrue(superior(1,0));
        $this->assertTrue(superior(50,10));
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');
        superior(2,1,true,"matrix");
        superior(20,10,true,"matrix");
    }

    /**
     * @throws Exception
     */
    public function test_superior_or_equal()
    {

        $this->assertTrue(superior_or_equal(1,1));
        $this->assertTrue(superior_or_equal(['a'],1));

        $this->assertTrue(superior(50,20));
        $this->assertTrue(superior([1,2,3,4],2));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');
        superior_or_equal(1,1,true,"matrix");
        superior_or_equal(['a'],1,true,"matrix");
        superior_or_equal(['a','b'],1,true,"matrix");
    }

    /**
     * @throws Exception
     */
    public function test_inferior()
    {

        $this->assertTrue(inferior(1,2));
        $this->assertTrue(inferior([1],2));
        $this->assertTrue(inferior([1,2],10));
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');
        inferior(2,10,true,"matrix");
        inferior(20,30,true,"matrix");

        inferior(['a','b'],10,true,"matrix");
        inferior(['a','b','c'],30,true,"matrix");
    }

    /**
     * @throws Exception
     */
    public function test_inferior_or_equal()
    {

        $this->assertTrue(inferior_or_equal(1,1));
        $this->assertTrue(inferior_or_equal([1],1));
        $this->assertTrue(inferior_or_equal(50,200));
        $this->assertTrue(inferior_or_equal([1,2,3,4,5,6],200));
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');
        inferior_or_equal(1,1,true,"matrix");
        inferior_or_equal([1],1,true,"matrix");
        inferior_or_equal([20,2,3],100,true,"matrix");
    }

    public function test_ago()
    {
        $this->assertNotEmpty(ago('en',now()));
        $this->assertNotEmpty(ago('fr',now()));
        $this->assertNotEmpty(ago('es',now()));
    }

    /**
     * @throws Exception
     */
    public function test_remove_helpers()
    {
        $tables = 'helpers';

        $this->assertTrue(remove_tables($this->mysql()->tables(), $tables));
        $this->assertTrue(remove_tables($this->postgresql()->tables(), $tables));
        $this->assertTrue(remove_tables($this->sqlite()->tables(), $tables));


        $this->assertTrue($this->mysql()->users()->set_name($tables)->set_password($tables)->create());
        $this->assertTrue($this->postgresql()->users()->set_name($tables)->set_password($tables)->create());

        $this->assertTrue(remove_users($this->mysql()->users(),$tables));
        $this->assertTrue(remove_users($this->postgresql()->users(),$tables));


    }
    /**
     * @throws \Exception
     */
    public function test_query_view()
    {

        $not_found = 'records was not found';
        $table_empty = 'the current table is empty';

        $code = query_view("index.php",$this->mysql()->model(),$this->mysql()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty,'choose a column','condition','operation','order','reset');


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
        $this->assertContains('choose a column', $code);
        $this->assertContains('condition', $code);
        $this->assertContains('operation', $code);
        $this->assertContains('order', $code);
        $this->assertContains('reset', $code);

       $code = query_view("index.php",$this->postgresql()->model(),$this->postgresql()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty,'choose a column','condition','operation','order','reset');


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
        $this->assertContains('choose a column', $code);
        $this->assertContains('condition', $code);
        $this->assertContains('operation', $code);
        $this->assertContains('order', $code);
        $this->assertContains('reset', $code);

        $code = query_view("index.php",$this->sqlite()->model(),$this->sqlite()->tables(),'create.php','update.php','create','update',$this->table,'expected','superior','superior or equal','inferior','inferior or equal','different','equal','like','select','remove','update','execute','btn btn-primary','record was removed successfully',$not_found,$table_empty,'choose a column','condition','operation','order','reset');

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
        $this->assertContains('choose a column', $code);
        $this->assertContains('condition', $code);
        $this->assertContains('operation', $code);
        $this->assertContains('order', $code);
        $this->assertContains('reset', $code);


    }

    /**
     * @throws Exception
     */
    public function test_is()
    {
        $this->assertTrue(is_true(true));
        $this->assertTrue(is_not_false(true));

        $this->assertTrue(is_true(equal('1','1')));
        $this->assertTrue(is_not_false(equal('1','1')));

        $this->assertTrue(is_false(false));
        $this->assertTrue(is_not_true(false));

        $this->assertTrue(is_false(equal('a','b')));
        $this->assertTrue(is_not_true(equal('a','b')));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');

        is_not_false(true,true,"matrix");
        is_not_true(false,true,"matrix");
        is_false(false,true,'matrix');
        is_true(true,true,'matrix');
    }
    /**
     * @throws Exception
     */
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');

        equal(1,1,true,"matrix");
        equal(10,10,true,"matrix");
    }

    public function test_append()
    {
        $code = '';
        append($code,'i ','am ','very ','happy');
        $this->assertEquals('i am very happy',$code);
    }

    /**
     * @throws Exception
     */
    public function test_different()
    {
        $this->assertTrue(different('','adz'));
        $this->assertTrue(different('','a'));
        $this->assertTrue(different('a','aadz'));

        $this->assertFalse(different('adz','adz'));
        $this->assertFalse(different('a','a'));
        $this->assertFalse(different('aadz','aadz'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');

        different(1,12,true,"matrix");
        different(10,210,true,"matrix");
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
        $req = "SELECT * FROM $this->table";
        $this->assertNotEmpty(req($this->mysql()->connect(),$req));
        $this->assertNotEmpty(req($this->postgresql()->connect(),$req));
        $this->assertNotEmpty(req($this->sqlite()->connect(),$req));
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
        $req = "SELECT * FROM $this->table";
        $this->assertTrue(execute($this->mysql()->connect(),$req));
        $this->assertTrue(execute($this->postgresql()->connect(),$req));
        $this->assertTrue(execute($this->sqlite()->connect(),$req));
    }

    /**
     * @throws \Exception
     */
    public function test_execute_query()
    {

        $sql = '';
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','!=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','<=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertTrue(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','<',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Query::UPDATE,'id','>',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());

        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertTrue(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());

        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertTrue(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());

        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','!=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','<=',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertTrue(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','<',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());
        $this->assertFalse(collection(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','>',1,$this->table,'btn btn-primary','update','','id','desc',$sql))->empty());


        $this->assertTrue(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::DELETE,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));
        $this->assertEmpty(execute_query(1,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));


        $this->assertTrue(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Query::DELETE,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));
        $this->assertEmpty(execute_query(1,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));


        $this->assertTrue(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::DELETE,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));
        $this->assertEmpty(execute_query(1,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',1,$this->table,'btn btn-primary','a','','id','desc',$sql));

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
        $query = execute_query(2,$this->mysql()->model(),$this->mysql()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','azda','','id','desc',$sql);


        $this->assertCount(1,$query);

        $query = execute_query(2,$this->postgresql()->model(),$this->postgresql()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','','','id','desc',$sql);
        $this->assertCount(1,$query);

        $query = execute_query(2,$this->sqlite()->model(),$this->sqlite()->tables(),Imperium::SELECT,'id','=',8,$this->table,'btn btn-primary','','','id','desc',$sql);
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
        $this->assertFalse(def($x,$c));

        $this->assertTrue(not_def($x,$c));
        $this->assertFalse(not_def($a));
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
        $select = tables_select($this->mysql()->tables(),'imperium');

        $this->assertNotContains($this->table,$select );
        $this->assertContains('location',$select);
        $this->assertNotEmpty($select);

        $select = tables_select($this->postgresql()->tables(),'imperium');

        $this->assertNotContains($this->table,$select );
        $this->assertContains('location',$select);
        $this->assertNotEmpty($select);
        
        $select = tables_select($this->sqlite()->tables(),'imperium');

        $this->assertNotContains($this->table,$select );
        $this->assertContains('location',$select);
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
        $this->assertFalse(not_exist('html'));
        $this->assertTrue(not_exist('_e'));
        $this->assertTrue(not_exist('app'));
        $this->assertFalse(not_exist('_html'));
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
        $user = 'marion';

        $this->assertTrue(add_user($this->mysql()->users(),$user,$user));
        $this->assertTrue(add_user($this->postgresql()->users(),$user,$user));
        $this->assertTrue(remove_users($this->mysql()->users(),$user));
        $this->assertTrue(remove_users($this->postgresql()->users(),$user));
    }

    public function test_now()
    {
        $this->assertNotEmpty(now());
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

    public function test_sql_to_json()
    {
        $this->assertTrue(\sql_to_json($this->mysql()->connect(),'app.json','show databases',"select * from {$this->table}",'select * from mysql.user'));
        $this->assertTrue(\sql_to_json($this->postgresql()->connect(),'app.json',"select * from {$this->table}","select * from {$this->table}"));
        $this->assertTrue(\sql_to_json($this->sqlite()->connect(),'app.json',"select * from {$this->table}","select * from {$this->table}"));
    }

    public function test_slug()
    {
         $this->assertEquals('mon-article',slug("Mon article"," "));
         $this->assertEquals('alex-le-chat',slug("ALEX le Chat"," "));
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

    /**
     * @throws Exception
     */
    public function test_imperium_instance()
    {
        $this->assertInstanceOf(Imperium::class,$this->mysql());
        $this->assertInstanceOf(Imperium::class,$this->postgresql());
        $this->assertInstanceOf(Imperium::class,$this->sqlite());
    }

    /**
     * @throws Exception
     */
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
        $this->assertEquals('develop',current_branch('.'));
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

    /**
     * @throws Exception
     */
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('matrix');
        not_in($data,"alex",true,"matrix");
    }

    /**
     * @throws Exception
     */
    public function test_bases()
    {
        $this->assertInstanceOf(Base::class,\base($this->mysql()->connect(),$this->mysql()->tables()));
        $this->assertInstanceOf(Base::class,\base($this->postgresql()->connect(),$this->postgresql()->tables()));
        $this->assertInstanceOf(Base::class,\base($this->sqlite()->connect(),$this->sqlite()->tables()));
    }
}
