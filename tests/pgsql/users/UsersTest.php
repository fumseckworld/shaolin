<?php


namespace Testing\pgsql\users {


    use Testing\DatabaseTest;

    class UsersTest extends DatabaseTest
    {
        /**
         * @throws \Exception
         */
        public function test_show()
        {

            $this->assertNotEmpty($this->postgresql()->users()->show());

            $this->assertContains(self::POSTGRESQL_USER,$this->postgresql()->users()->hidden([])->show());

            $this->assertNotContains(self::POSTGRESQL_USER,$this->postgresql()->users()->hidden([self::POSTGRESQL_USER])->show());

        }

        /**
         * @throws \Exception
         */
        public function test_create_and_drop()
        {

            $name = 'voku';

            $this->assertTrue($this->postgresql()->users()->set_name($name)->set_password($name)->create());
            $this->assertTrue($this->postgresql()->users()->drop($name));
        }

    }
}