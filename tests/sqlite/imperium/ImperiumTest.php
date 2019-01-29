<?php

namespace Testing\sqlite\imperium {


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

            $this->assertNotEmpty($this->sqlite()->find($this->table,50));
            $this->assertNotEmpty($this->sqlite()->find_or_fail($this->table,50));

        }
        /**
         * @throws Exception
         */
        public function test_all()
        {
            $this->assertNotEmpty($this->sqlite()->all($this->table,'id'));
        }

        /**
         * @throws Exception
         */
        public function test_show_columns_type()
        {
            $this->assertNotEmpty($this->sqlite()->show_columns_types($this->table));
        }

        /**
         * @throws Exception
         */
        public function test_append_column()
        {
            $this->assertTrue($this->sqlite()->append_column($this->table,'salary',App::INT,0,false,true));
            $this->assertFalse($this->sqlite()->remove_column($this->table,'salary'));
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
                    'date' => faker()->date(),
                    'salary' => faker()->numberBetween(0,20)
                ];
                $this->assertTrue($this->sqlite()->update_record(5,$data,$this->table));
                $this->assertTrue($this->sqlite()->save($this->table,$data));
            }
        }

        public function test_remove_by_id()
        {
            $this->assertTrue($this->sqlite()->remove_record($this->table,50));
        }
        /**
         * @throws \Exception
         */
        public function test_json()
        {
            $filename = 'app.json';
            $this->assertTrue($this->sqlite()->json()->set_name($filename)->add($this->sqlite()->model()->from(current_table())->all(),'database')->generate());


        }

        public function test_change_base()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->change_base_charset('utf8');
        }

        public function test_get_host()
        {
            $this->assertEquals('localhost',$this->sqlite()->connect()->host());
        }

        /**
         * @throws \Exception
         */
        public function test_remove_column()
        {
            $this->assertFalse($this->sqlite()->table()->from($this->table)->remove_column('id'));

            $this->assertTrue($this->sqlite()->table()->from($this->table)->has_column('date'));
        }



        /**
         * @throws \Exception
         */
        public function test_show_users()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->show_users();
            $this->sqlite()->user_exist('root');
        }

        /**
         * @throws \Exception
         */
        public function test_drop()
        {
            $table = 'luxoria';

            $this->assertTrue($this->sqlite()->table()->column(Table::INTEGER,Table::PRIMARY_KEY,true,0,true,false,false,'',false,'','')->column(Table::VARCHAR,'name',false,255,true,false,false,'',true,Table::DIFFERENT,"willy")->create($table));

            $this->assertTrue($this->sqlite()->remove_table($table));

        }

        /**
         * @throws \Exception
         */
        public function test_add_databases()
        {
            $name = 'alexandra';
            $this->expectException(Exception::class);
            $this->sqlite()->add_database($name,'utf8','utf8_general_ci');




        }
        /**
         * @throws \Exception
         */
        public function test_exist()
        {
            $this->assertTrue($this->sqlite()->table_exist($this->table));


        }



        /**
         * @throws \Exception
         */
        public function test_remove_user()
        {
            $name = 'marion';

            $this->expectException(Exception::class);
            add_user($this->sqlite()->users(),$name,$name);
            $this->sqlite()->remove_user($name);
        }

        /**
         * @throws \Exception
         */

        public function test_pass()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->change_user_password('sqlite','mya');
        }
        /**
         * @throws \Exception
         */
        public function test_show_columns()
        {
            $this->assertNotEmpty($this->sqlite()->table()->from($this->table)->columns_types());

            $this->assertNotEmpty($this->sqlite()->show_columns($this->table));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->sqlite()->has_column($this->table,'id'));

            $this->assertFalse($this->sqlite()->has_column($this->table,'c'));
            $this->assertTrue($this->sqlite()->has_tables());

        }

        /**
         * @throws Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->sqlite()->seed_database());
        }

        /**
         * @throws Exception
         */
        public function test_add_user()
        {

            $this->expectException(Exception::class);
            $this->sqlite()->add_user('linux','linux');

            $this->sqlite()->remove_user('linux');
        }


        public function test_collection()
        {
            $this->assertInstanceOf(Collection::class,$this->sqlite()->collection());

            $this->assertEquals([],$this->sqlite()->collection()->collection());

            $this->assertEquals(['3'],$this->sqlite()->collection(['3'])->collection());
        }
    }
}
