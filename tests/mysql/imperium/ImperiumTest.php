<?php

namespace Testing\mysql\imperium {


    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\App;
    use Imperium\Tables\Table;

    use Imperium\Users\Users;
    use Testing\DatabaseTest;
    

    class ImperiumTest extends DatabaseTest
    {
        /**
         * @var string
         */
        private $table;

        /**
         * @throws Exception
         */
        public function setUp():void
        {
            $this->table = 'base';
        }

        /**
         * @throws Exception
         */
        public function test_find()
        {
            $x = $this->mysql()->find($this->table,50);
            $this->assertIsNotString($x);
            $this->assertIsNotArray($x);

            $x = $this->mysql()->find_or_fail($this->table,50);
            $this->assertIsNotString($x);
            $this->assertIsNotArray($x);

        }
        /**
         * @throws Exception
         */
        public function test_all()
        {
            $this->assertNotEmpty($this->mysql()->all($this->table,'id'));
        }

        /**
         * @throws Exception
         */
        public function test_show_columns_type()
        {
            $this->assertNotEmpty($this->mysql()->show_columns_types($this->table));
        }

        /**
         * @throws Exception
         */
        public function test_append_column()
        {
            $this->assertTrue($this->mysql()->append_column($this->table,'salary',App::INT,0,false,true));
            $this->assertTrue($this->mysql()->remove_column($this->table,'salary'));
        }

        public function test_save()
        {
            $number = 5;
            for ($i = 0; $i != $number; ++$i)
            {

                $data= [
                    'id' => 'id',
                    'name' => faker()->name,
                    'age' => faker()->numberBetween(1,100),
                    'phone' => faker()->randomNumber(8),
                    'sex' => faker()->firstNameMale,
                    'status' => faker()->text(20),
                    'days' => faker()->date(),
                    'date' => faker()->date()
                ];
                $this->assertTrue($this->mysql()->update_record(5,$data,$this->table));
                $this->assertTrue($this->mysql()->save($this->table,$data));
            }
        }

        public function test_remove_by_id()
        {
            $this->assertTrue($this->mysql()->remove_record($this->table,50));
        }
        /**
         * @throws \Exception
         */
        public function test_json()
        {
            $filename = 'app.json';
            $this->assertTrue($this->mysql()->json()->set_name($filename)->add($this->mysql()->show_databases(),'database')->generate());

            $this->assertTrue($this->mysql()->bases_users_tables_to_json($filename));
            $this->assertTrue($this->mysql()->bases_users_tables_to_json($filename));

            $query = "SELECT * FROM $this->table";
            $this->assertTrue($this->mysql()->json()->sql($this->mysql()->connect(), $query,'records')->generate());


            $this->assertTrue($this->mysql()->sql_to_json($filename,$query));

            $this->assertTrue($this->mysql()->create_json($filename,$this->mysql()->show_databases()));

            $this->assertTrue($this->mysql()->json()->set_name($filename)->add($this->mysql()->show_databases())->generate());
            $this->assertNotEmpty($this->mysql()->json()->decode($filename));
            $this->assertNotEmpty($this->mysql()->json_decode($filename));



        }

        public function test_change_base()
        {
            $this->assertTrue($this->mysql()->change_base_charset('utf8'));
        }

        public function test_get_host()
        {
            $this->assertEquals('localhost',$this->mysql()->connect()->host());
        }

        /**
         * @throws \Exception
         */
        public function test_remove_column()
        {
            $this->assertFalse($this->mysql()->table()->column()->for($this->table)->drop('id'));

            $this->assertTrue($this->mysql()->table()->column()->for($this->table)->drop('date'));

            $this->assertFalse($this->mysql()->table()->column()->for($this->table)->exist('date'));
        }



        /**
         * @throws \Exception
         */
        public function test_show_users()
        {
            $this->assertNotEmpty($this->mysql()->show_users());

            $this->assertContains('root',$this->mysql()->show_users());
            $this->assertTrue($this->mysql()->user_exist('root'));

        }



        /**
         * @throws \Exception
         */
        public function test_add_databases()
        {
            $name = 'alexandra';
            $this->assertTrue($this->mysql()->add_database($name,'utf8','utf8_general_ci'));
            $this->assertTrue($this->mysql()->remove_database($name));


            $this->assertTrue($this->mysql()->add_database($name));
            $this->assertTrue($this->mysql()->remove_database($name));


        }
        /**
         * @throws \Exception
         */
        public function test_exist()
        {
            $this->assertTrue($this->mysql()->table_exist($this->table));


        }



        /**
         * @throws \Exception
         */
        public function test_remove_user()
        {
            $first = 'dupond';
            $second = 'dupont';
            $this->assertTrue(add_user($first,$first));
            $this->assertTrue(add_user($second,$second));
            $this->assertTrue(remove_users($first,$second));
        }

        /**
         * @throws \Exception
         */

        public function test_pass()
        {
            $this->assertTrue($this->mysql()->change_user_password(self::MYSQL_USER,self::MYSQL_PASS));
        }
        /**
         * @throws \Exception
         */
        public function test_show_columns()
        {
            $this->assertNotEmpty($this->mysql()->table()->column()->for($this->table)->types());

            $this->assertNotEmpty($this->mysql()->show_columns($this->table));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->mysql()->has_column($this->table,'id'));

            $this->assertFalse($this->mysql()->has_column($this->table,'utf8_general_ci'));

            $this->assertTrue($this->mysql()->users()->has());

            $this->assertTrue($this->mysql()->has_users());

            $this->assertTrue($this->mysql()->has_tables());

            $this->assertTrue($this->mysql()->has_bases());

        }

        /**
         * @throws Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->mysql()->seed_database());
        }

        /**
         * @throws Exception
         */
        public function test_add_user()
        {
            $this->assertTrue($this->mysql()->add_user('linux','linux'));

            $this->assertTrue($this->mysql()->remove_user('linux'));
        }


        /**
         * @throws Exception
         */
        public function test_change()
        {
            $this->assertTrue($this->mysql()->change_base_collation('utf8_general_ci'));

            $this->assertTrue($this->mysql()->change_table_charset($this->table,"utf8"));

            $this->assertTrue($this->mysql()->change_table_collation($this->table,"utf8_general_ci"));
        }

        public function test_collection()
        {
            $this->assertInstanceOf(Collection::class,$this->mysql()->collection());

            $this->assertEquals([],$this->mysql()->collection()->collection());

            $this->assertEquals(['3'],$this->mysql()->collection(['3'])->collection());
        }

        /**
         * @throws Exception*/
        public function test_user()
        {
            $this->assertInstanceOf(Users::class,$this->mysql()->users());
            $this->assertNotEmpty($this->mysql()->users()->show());
        }

    }
}
