<?php


namespace Testing\sqlite\users {


    use Testing\DatabaseTest;

    class UsersTest extends DatabaseTest
    {
        /**
         * @throws \Exception
         */
        public function test_show()
        {

            $this->expectException(\Exception::class);
            $this->sqlite()->users()->show();


        }

        /**
         * @throws \Exception
         */
        public function test_create_and_drop()
        {

            $this->expectException(\Exception::class);
            $name = 'voku';
            $this->sqlite()->users()->set_name($name)->set_password($name)->create();
            $this->sqlite()->users()->drop($name);
        }

    }
}