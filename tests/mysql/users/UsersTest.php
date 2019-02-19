<?php


namespace Testing\mysql\users {


    use Testing\DatabaseTest;

    class UsersTest extends DatabaseTest
    {
        /**
         * @throws \Exception
         */
        public function test_show()
        {

            $this->assertNotEmpty($this->mysql()->users()->show());

            $this->assertContains(self::MYSQL_USER,$this->mysql()->users()->show());


        }

        /**
         * @throws \Exception
         */
        public function test_create_and_drop()
        {

            $name = 'voku';

            $this->assertTrue($this->mysql()->users()->set_name($name)->set_password($name)->create());
            $this->assertTrue($this->mysql()->users()->drop($name));
        }

    }
}