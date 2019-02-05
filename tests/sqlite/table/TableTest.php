<?php


namespace Testing\sqlite\table {

    use Imperium\App;
    use Imperium\Tables\Table;
    use Testing\DatabaseTest;

    class TableTest extends DatabaseTest
    {

        /**
         * @var Table
         */
        private $table;

        public function setUp()
        {
            $this->table = $this->sqlite()->table()->from('tbl');
        }

        public function test_columns_info()
        {
            $this->assertNotEmpty($this->table->get_columns_info());
        }


        public function test_types()
        {
            $this->assertNotEmpty($this->table->types());
        }
        /**
         * @throws \Exception
         */
        public function test_seed()
        {
            $this->assertTrue($this->table->seed(50));
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
                        'id' => null,
                        'name' => faker()->name,
                        'age' => faker()->numberBetween(1,100),
                        'phone' => faker()->randomNumber(8),
                        'sex' => faker()->firstNameMale,
                        'status' => faker()->text(20),
                        'days' => faker()->date(),
                        'date' => faker()->date(),
                    ]);

            }


            $this->assertTrue($this->table->insert_multiples($data->collection()));
        }
        /**
         * @throws \Exception
         */
        public function test_select()
        {

            $this->assertNotEmpty($this->table->select(6));

        }


        /**
         * @throws \Exception
         */
        public function test_change()
        {
            $this->expectException(\Exception::class);
            $this->table->set_charset('utf8')->change_charset();
            $this->table->set_collation('utf8_general_ci')->change_collation();


            $this->table->convert('utf8','utf8_general_ci');
        }

        /**
         * @throws \Exception
         */
        public function test_primary_key()
        {
            $this->assertEquals('id',$this->table->primary_key());
        }

        /**
         * @throws \Exception
         */
        public function test_show()
        {
            $this->assertContains('base',$this->table->show());
            $this->assertNotContains('base',$this->table->hidden(['base'])->show());
            $this->assertContains('base',$this->table->hidden()->show());
        }

        /**
         * @throws \Exception
         */
        public function test_has_columns_type()
        {


            $this->assertTrue($this->table->has_types('INTEGER'));

            $this->assertFalse($this->table->has_types('char'));
        }
        /**
         * @throws \Exception
         */
        public function test_get_tpm_name()
        {
            $this->assertNotEmpty($this->table->get_current_tmp_table());
        }


        /**
         * @throws \Exception
         */
        public function test_type()
        {
            foreach ($this->table->columns() as $column)
                $this->assertNotEmpty($this->table->type($column));
        }

        /**
         * @throws \Exception
         */
        public function test_has()
        {
            $this->assertTrue($this->table->has());
            $this->assertTrue($this->table->has_column('id'));
        }


        /**
         * @throws \Exception
         */

        public function test_remove_by_id()
        {
            $this->assertTrue($this->table->remove(20));
        }

        public function test_not_exist()
        {
            $this->assertTrue($this->table->not_exist('alexandra'));
        }

        /**
         * @throws \Exception
         */
        public function test_the_last_field()
        {

            $columns = $this->table->columns();
            $end = end($columns);
            $this->assertFalse($this->table->is_the_last_field('id',$this->table->columns()));
            $this->assertFalse($this->table->is_the_last_field('name',$this->table->columns()));

            $this->assertTrue($this->table->is_the_last_field($end,$this->table->columns()));
        }

        /**
         * @throws \Exception
         */
        public function test_truncate()
        {
            $this->assertTrue($this->table->truncate(current_table()));

        }
        /**
         * @throws \Exception
         */
        public function test_append_column()
        {
            $column = 'moria';

            $instance = $this->table;

            $this->assertTrue($instance->append_column($column,App::TEXT,255,false,true));
            $this->assertTrue($instance->has_column($column));

        }


        public function test_create()
        {
            $table = 'users';
            $bool =     $this->sqlite()->table()
                ->column(Table::INTEGER,'id',true,0,true,true,false,'',false,Table::SUPERIOR_OR_EQUAL,1)
                ->column(Table::INTEGER,'age',false,0,false,true,true,18,true,Table::SUPERIOR_OR_EQUAL,18)
                ->column(Table::TEXT,'name',false,100,true,false,false,'',true,Table::DIFFERENT,'willy')
                ->column(Table::TEXT,'username',false,100,true,true,true,'champion',true,Table::DIFFERENT,'fumseck')
                ->create($table);
            $this->assertTrue($bool);
            $this->assertTrue($this->sqlite()->table()->drop($table));

        }

        /**
         * @throws \Exception
         */
        public function test_ignore()
        {
            $this->assertNotContains(current_table(),$this->sqlite()->table()->hidden([current_table()])->show());

            $this->assertContains(current_table(),$this->table->hidden()->show());
        }


        /**
         * @throws \Exception
         */
        public function test_columns_to_string()
        {

            $this->assertEquals('id, name, age, phone, sex, status, days, date, moria',$this->table->columns_to_string());
        }


        /**
         * @throws \Exception
         */
        public function test_count()
        {
            $this->assertEquals(349,$this->table->count());
        }


        /**
         * @throws \Exception
         */
        public function test_found()
        {
            $this->assertEquals(10,$this->table->found());

        }


        /**
         * @throws \Exception
         */
        public function test_current()
        {

            $this->assertEquals('tbl',$this->table->current());
        }


        /**
         * @throws \Exception
         */
        public function test_columns_not_exist()
        {
            $this->assertTrue($this->table->column_not_exist('excalibur'));

            $this->assertFalse($this->table->column_not_exist('id'));
        }


        /**
         * @throws \Exception
         */
        public function test_modify_column()
        {
            $this->assertFalse($this->table->modify_column('status',App::VARCHAR,200));
        }

        /**
         * @throws \Exception
         */
        public function test_rename()
        {


            $old = 'name';
            $new = 'username';

            $this->assertTrue($this->table->rename_column($old, $new));
            $this->assertTrue($this->table->rename_column($new, $old));


            $this->assertTrue($this->table->rename($new));

            $this->assertTrue($this->table->drop(current_table()));



        }

    }
}