<?php

namespace Testing\pgsql\imperium {


    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\App;
    use Imperium\Tables\Table;

    use Imperium\Users\Users;
    use Testing\DatabaseTest;

    class ImperiumTest extends DatabaseTest
    {
        /**
         * @throws Exception
         */
        public function setUp()
        {
            $this->table = 'base';
        }

        /**
         * @throws Exception
         */
        public function test_find()
        {

            $this->assertNotEmpty($this->postgresql()->find($this->table,50));
            $this->assertNotEmpty($this->postgresql()->find_or_fail($this->table,50));

        }
        /**
         * @throws Exception
         */
        public function test_all()
        {
            $this->assertNotEmpty($this->postgresql()->all($this->table,'id'));
        }

        /**
         * @throws Exception
         */
        public function test_show_columns_type()
        {
            $this->assertNotEmpty($this->postgresql()->show_columns_types($this->table));
        }

        /**
         * @throws Exception
         */
        public function test_append_column()
        {
            $this->assertTrue($this->postgresql()->append_column($this->table,'salary',App::INT,0,false,true));
            $this->assertTrue($this->postgresql()->remove_column($this->table,'salary'));
        }

        public function test_save()
        {
            $number = 5;
            for ($i = 0; $i != $number; ++$i)
            {

                $data= [
                    'id' => null,
                    'name' => faker()->name,
                    'age' => faker()->numberBetween(1,100),
                    'phone' => faker()->randomNumber(8),
                    'sex' => faker()->firstNameMale,
                    'status' => faker()->text(20),
                    'days' => faker()->date(),
                    'date' => faker()->date()
                ];
                $this->assertTrue($this->postgresql()->update_record(5,$data,$this->table));
                $this->assertTrue($this->postgresql()->save($this->table,$data));
            }
        }

        public function test_remove_by_id()
        {
            $this->assertTrue($this->postgresql()->remove_record($this->table,50));
        }
        /**
         * @throws \Exception
         */
        public function test_json()
        {
            $filename = 'app.json';
            $this->assertTrue($this->postgresql()->json()->set_name($filename)->add($this->postgresql()->show_databases(),'database')->generate());

            $this->assertTrue($this->postgresql()->bases_users_tables_to_json($filename));
            $this->assertTrue($this->postgresql()->bases_users_tables_to_json($filename));

            $query = "SELECT * FROM $this->table";
            $this->assertTrue($this->postgresql()->json()->sql($this->postgresql()->connect(), $query,'records')->generate());


            $this->assertTrue($this->postgresql()->sql_to_json($filename,$query));

            $this->assertTrue($this->postgresql()->create_json($filename,$this->postgresql()->show_databases()));

            $this->assertTrue($this->postgresql()->json()->set_name($filename)->add($this->postgresql()->show_databases())->generate());
            $this->assertNotEmpty($this->postgresql()->json()->decode($filename));
            $this->assertNotEmpty($this->postgresql()->json_decode($filename));



        }

        public function test_change_base()
        {
            $this->assertTrue($this->postgresql()->change_base_charset('UTF8'));
        }

        public function test_get_host()
        {
            $this->assertEquals('localhost',$this->postgresql()->connect()->host());
        }

        /**
         * @throws \Exception
         */
        public function test_remove_column()
        {
            $this->assertFalse($this->postgresql()->table()->from($this->table)->remove_column('id'));

            $this->assertTrue($this->postgresql()->table()->from($this->table)->remove_column('date'));

            $this->assertFalse($this->postgresql()->table()->from($this->table)->has_column('date'));
        }



        /**
         * @throws \Exception
         */
        public function test_show_users()
        {
            $this->assertNotEmpty($this->postgresql()->show_users());

            $this->assertContains('postgres',$this->postgresql()->show_users());
            $this->assertTrue($this->postgresql()->user_exist('postgres'));

            $this->assertNotContains('root',$this->postgresql()->show_users(['root']));
        }

        /**
         * @throws \Exception
         */
        public function test_drop()
        {
            $table = 'luxoria';

            $this->assertTrue($this->postgresql()->table()->column(Table::SERIAL,Table::PRIMARY_KEY,true,0,true,false,false,'',false,'','')->column(Table::VARCHAR,'name',false,255,true,false,false,'',true,Table::DIFFERENT,"willy")->create($table));

            $this->assertTrue($this->postgresql()->remove_table($table));

        }

        /**
         * @throws \Exception
         */
        public function test_add_databases()
        {
            $name = 'alexandra';
            $this->assertTrue($this->postgresql()->add_database($name,'UTF8','C'));
            $this->assertTrue($this->postgresql()->remove_database($name));


            $this->assertTrue($this->postgresql()->add_database($name));
            $this->assertTrue($this->postgresql()->remove_database($name));


        }
        /**
         * @throws \Exception
         */
        public function test_exist()
        {
            $this->assertTrue($this->postgresql()->table_exist($this->table));


        }



        /**
         * @throws \Exception
         */
        public function test_remove_user()
        {
            $name = 'marion';

            $this->assertTrue(add_user($this->postgresql()->users(),$name,$name));
            $this->assertTrue($this->postgresql()->remove_user($name));
        }

        /**
         * @throws \Exception
         */

        public function test_pass()
        {
            $this->assertTrue($this->postgresql()->change_user_password(self::POSTGRESQL_USER,self::POSTGRESQL_PASS));
        }
        /**
         * @throws \Exception
         */
        public function test_show_columns()
        {
            $this->assertNotEmpty($this->postgresql()->table()->from($this->table)->columns_types());

            $this->assertNotEmpty($this->postgresql()->show_columns($this->table));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->postgresql()->has_column($this->table,'id'));

            $this->assertFalse($this->postgresql()->has_column($this->table,'c'));

            $this->assertTrue($this->postgresql()->has_users());

            $this->assertTrue($this->postgresql()->users()->hidden()->has());

            $this->assertTrue($this->postgresql()->has_tables());

            $this->assertTrue($this->postgresql()->has_bases());

        }

        /**
         * @throws Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->postgresql()->seed_database());
        }

        /**
         * @throws Exception
         */
        public function test_add_user()
        {
            $this->assertTrue($this->postgresql()->add_user('linux','linux'));

            $this->assertTrue($this->postgresql()->remove_user('linux'));
        }


        /**
         * @throws Exception
         */
        public function test_change()
        {
            $this->assertTrue($this->postgresql()->change_base_collation('C'));

            $this->assertTrue($this->postgresql()->change_table_charset($this->table,"UTF8"));

            $this->assertTrue($this->postgresql()->change_table_collation($this->table,"C"));
        }

        public function test_collection()
        {
            $this->assertInstanceOf(Collection::class,$this->postgresql()->collection());

            $this->assertEquals([],$this->postgresql()->collection()->collection());

            $this->assertEquals(['3'],$this->postgresql()->collection(['3'])->collection());
        }

        /**
         * @throws Exception
         */
        public function test_user()
        {
            $this->assertInstanceOf(Users::class,$this->postgresql()->users());
            $this->assertNotEmpty($this->postgresql()->users()->show());
        }
    }
}