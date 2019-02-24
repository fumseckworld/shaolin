<?php

namespace Testing\sqlite\imperium {


    use Exception;
    use Imperium\Collection\Collection;
    use Imperium\App;
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
            $this->assertTrue($this->sqlite()->append_column($this->table,'salary',App::INTEGER,0,true));
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
                    'date' => faker()->date(),
                    'salary' => faker()->numberBetween(0,200)
                ];
                $this->assertTrue($this->sqlite()->update_record(5,$data,$this->table));
                $this->assertTrue($this->sqlite()->save($this->table,$data));
            }
        }

        public function test_remove_by_id()
        {
            $this->assertTrue($this->sqlite()->remove_record($this->table,50));
        }





        public function test_change_base()
        {
            $this->expectException(Exception::class);
            $this->assertTrue($this->sqlite()->change_base_charset('UTF8'));
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
            $this->assertFalse($this->sqlite()->table()->column()->for($this->table)->drop('id'));

            $this->assertFalse($this->sqlite()->table()->column()->for($this->table)->drop('date'));

        }



        /**
         * @throws \Exception
         */
        public function test_show_users()
        {
            $this->expectException(Exception::class);
            $this->sqlite()->show_users();



        }



        /**
         * @throws \Exception
         */
        public function test_add_databases()
        {

            $this->expectException(Exception::class);
            $name = 'alexandra';
            $this->sqlite()->add_database($name,'UTF8','C');
            $this->sqlite()->remove_database($name);

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
            $this->expectException(Exception::class);
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
            $this->expectException(Exception::class);
            $this->sqlite()->change_user_password('postgres','postgres');
        }
        /**
         * @throws \Exception
         */
        public function test_show_columns()
        {
            $this->assertNotEmpty($this->sqlite()->table()->column()->for($this->table)->types());

            $this->assertNotEmpty($this->sqlite()->show_columns($this->table));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->sqlite()->has_column($this->table,'id'));

            $this->assertFalse($this->sqlite()->has_column($this->table,'utf8_general_ci'));

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


        /**
         * @throws Exception
         */
        public function test_change()
        {
            $this->expectException(Exception::class);

            $this->sqlite()->change_base_collation('C');

            $this->sqlite()->change_table_charset($this->table,"UTF8");

            $this->sqlite()->change_table_collation($this->table,"C");
        }

        public function test_collection()
        {

            $this->assertInstanceOf(Collection::class,$this->sqlite()->collection());

            $this->assertEquals([],$this->sqlite()->collection()->collection());

            $this->assertEquals(['3'],$this->sqlite()->collection(['3'])->collection());
        }


    }
}
