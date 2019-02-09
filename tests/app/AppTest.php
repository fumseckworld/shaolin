<?php


namespace Testing\app {


    use Exception;
    use PHPUnit\Framework\TestCase;
    use Symfony\Component\HttpFoundation\Request;

    class AppTest extends TestCase
    {

        /**
         * @throws \Exception
         */
        public function test_request()
        {
            $this->assertInstanceOf(Request::class,app()->request());
        }


        /**
         * @throws Exception
         */
        public function test_register()
        {
            $form = secure_register_form('/', '127.0.0.1', '127.0.0.1', 'username', 'username will be use','username can be empty', 'email', 'email will be use', 'email can be empty', 'password', 'password will be use', 'password not be empty', 'confirm the password','create account', 'register', true,['fr' => 'French','en' => 'English' ],
                'select', 'lang will be use','error','select a time zone','success', 'time zone will be use', fa('fas','fa-key'), fa('fas','fa-user'), fa('fas','fas-envelope'),fa('fas','fa-user-plus'), fa('fas', 'fa-globe'));

            $this->assertContains('/',$form);
            $this->assertContains('username will be use',$form);
            $this->assertContains('time zone will be use',$form);
            $this->assertContains('az',$form);
            $this->assertContains('placeholder="username"',$form);
            $this->assertContains('placeholder="email"',$form);
            $this->assertContains('placeholder="email"',$form);
            $this->assertContains('placeholder="password"',$form);
            $this->assertContains('placeholder="confirm the password"',$form);
            $this->assertContains('<option value="fr">French</option>',$form);
            $this->assertContains('<option value="en">English</option>',$form);
            $this->assertContains('<option value="">select</option>',$form);
            $this->assertContains('<option value="">select a time zone</option>',$form);

            $form = secure_register_form('/', '27.0.0.1', '127.0.0.1', 'username', 'username will be use','username can be empty', 'email', 'email will be use', 'email can be empty', 'password', 'password will be use', 'password not be empty', 'confirm the password','create account', 'register', true,['fr' => 'French','en' => 'English' ],
                'select', 'lang will be use','error','select a time zone','success', 'time zone will be use', fa('fas','fa-key'), fa('fas','fa-user'), fa('fas','fas-envelope'),fa('fas','fa-user-plus'), fa('fas', 'fa-globe'));

            $this->assertEquals('',$form);
        }


    }
}