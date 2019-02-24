<?php


namespace Testing\pgsql\table {

    use Imperium\App;
    use Imperium\Tables\Table;
    use Testing\DatabaseTest;

    class TableTest extends DatabaseTest
    {

        /**
         * @var string
         */
        private $table;

        /**
         * @throws \Exception
         */
        public function setUp():void
        {
            $this->table = 'base';
        }

        /**
         * @throws \Exception
         */
        public function test_columns_info()
        {
            $this->assertNotEmpty($this->postgresql()->table()->column()->for($this->table)->info());
        }


        /**
         * @throws \Exception
         */
        public function test_types()
        {
            $this->assertNotEmpty($this->column()->types());
        }
        /**
         * @throws \Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->table()->seed(50));
        }

        /**
         * @throws \Exception
         */
        public function test_insert_multiples()
        {
            $number = 100;
            $data = collection();
            for ($i = 0; $i != $number ; $i++)
            {

                $data->add(
                    [
                        'id' => 'id',
                        'name' => faker()->name,
                        'age' => faker()->numberBetween(1,100),
                        'phone' => faker()->randomNumber(8),
                        'sex' => faker()->firstNameMale,
                        'status' => faker()->text(20),
                        'days' => faker()->date(),
                    ]);

            }


            $this->assertTrue($this->table()->insert_multiples($data->collection()));
        }
        /**
         * @throws \Exception
         */
        public function test_select()
        {

            $this->assertNotEmpty($this->table()->select(6));

        }


        /**
         * @throws \Exception
         */
        public function test_change()
        {
            $this->assertTrue($this->table()->set_charset('UTF8')->change_charset());
            $this->assertTrue($this->table()->set_collation('C')->change_collation());


            $this->assertTrue($this->table()->convert('UTF8','C'));
        }

        /**
         * @throws \Exception
         */
        public function test_primary_key()
        {
            $this->assertEquals('id', $this->column()->primary_key());
        }

        /**
         * @throws \Exception
         */
        public function test_show()
        {
            $this->assertContains('base',$this->table()->show());
        }

        /**
         * @throws \Exception
         */
        public function test_has_columns_type()
        {

            $this->assertTrue($this->column()->has_types('integer'));

            $this->assertFalse($this->column()->has_types('int'));
        }
        /**
         * @throws \Exception
         */
        public function test_get_tpm_name()
        {
            $this->assertNotEmpty($this->table()->get_current_tmp_table());
        }


        /**
         * @throws \Exception
         */
        public function test_type()
        {
            foreach ($this->table()->column()->for($this->table)->show() as $column)
                $this->assertNotEmpty($this->column()->column_type($column));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->table()->has());
            $this->assertTrue($this->column()->exist('id'));
        }


        /**
         * @throws \Exception
         */

        public function test_remove_by_id()
        {
            $this->assertTrue($this->table()->remove(20));
        }

        /**
         * @throws \Exception
         */
        public function test_not_exist()
        {
            $this->assertTrue($this->table()->not_exist('alexandra'));
        }

        /**
         * @throws \Exception
         */
        public function test_import()
        {
            $this->assertTrue($this->postgresql()->model()->dump(current_table()));
            $this->assertTrue($this->postgresql()->table()->import($this->base));
        }

        /**
         * @throws \Exception
         */
        public function test_the_last_field()
        {

            $columns = $this->column()->show();
            $end = collection($columns)->last();

            $this->assertFalse(equal($this->column()->last(),'id'));
            $this->assertFalse(equal($this->column()->last(),'name'));

            $this->assertTrue(equal($this->column()->last(),$end));
        }

        /**
         * @throws \Exception
         */
        public function test_truncate()
        {
            $this->assertTrue($this->table()->truncate(current_table()));

        }

        /**
         * @throws \Exception
         */
        public function test_append_column()
        {
            $column = 'moria';

            $instance = $this->column();

            $this->assertTrue($instance->add($column,App::VARCHAR,255,false));
            $this->assertTrue($instance->exist($column));
            $this->assertTrue($instance->drop($column));

            $this->assertTrue($instance->add($column,App::VARCHAR,255,true));
            $this->assertTrue($instance->exist($column));
            $this->assertTrue($instance->drop($column));

        }

        /**
         * @throws \Exception
         */
        public function test_ignore()
        {

            $this->assertContains(current_table(),$this->table()->show());
        }


        /**
         * @throws \Exception
         */
        public function test_columns_to_string()
        {

            $this->assertEquals('id, name, age, phone, sex, status, days',$this->column()->columns_to_string());
        }


        /**
         * @throws \Exception
         */
        public function test_count()
        {
            $this->assertEquals(0,$this->table()->count());
        }


        /**
         * @throws \Exception
         */
        public function test_found()
        {
            $this->assertEquals(10,$this->table()->found());

        }


        /**
         * @throws \Exception
         */
        public function test_current()
        {
            $this->assertEquals(current_table(),$this->table()->current());
        }


        /**
         * @throws \Exception
         */
        public function test_columns_not_exist()
        {
            $this->assertTrue($this->column()->not_exist('excalibur'));

            $this->assertFalse($this->column()->not_exist('id'));
        }
        /**
         * @throws \Exception
         */
        public function test_dump()
        {

            $this->assertTrue($this->table()->dump());
            $this->assertTrue($this->table()->dump(current_table()));

        }

        /**
         * @throws \Exception
         */
        public function test_rename()
        {


            $old = 'name';
            $new = 'username';

            $this->assertTrue($this->column()->rename($old, $new));
            $this->assertTrue($this->column()->rename($new, $old));


            $this->assertTrue($this->table()->rename($new));

            $this->assertTrue($this->table()->drop(current_table()));



        }

        /**
         * @return Table
         * @throws \Exception
         */
        private function table(): Table
        {
            return $this->postgresql()->table()->from($this->table);
        }

        /**
         * @return \Imperium\Tables\Column
         * @throws \Exception
         */
        private function column(): \Imperium\Tables\Column
        {
            return $this->table()->column()->for($this->table);
        }

    }
}
