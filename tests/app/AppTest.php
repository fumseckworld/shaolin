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

            $this->assertStringContainsString('/',$form);
            $this->assertStringContainsString('username will be use',$form);
            $this->assertStringContainsString('time zone will be use',$form);
            $this->assertStringContainsString('az',$form);
            $this->assertStringContainsString('placeholder="username"',$form);
            $this->assertStringContainsString('placeholder="email"',$form);
            $this->assertStringContainsString('placeholder="email"',$form);
            $this->assertStringContainsString('placeholder="password"',$form);
            $this->assertStringContainsString('placeholder="confirm the password"',$form);
            $this->assertStringContainsString('<option value="fr">French</option>',$form);
            $this->assertStringContainsString('<option value="en">English</option>',$form);
            $this->assertStringContainsString('<option value="">select</option>',$form);
            $this->assertStringContainsString('<option value="">select a time zone</option>',$form);

            $form = secure_register_form('/', '27.0.0.1', '127.0.0.1', 'username', 'username will be use','username can be empty', 'email', 'email will be use', 'email can be empty', 'password', 'password will be use', 'password not be empty', 'confirm the password','create account', 'register', true,['fr' => 'French','en' => 'English' ],
                'select', 'lang will be use','error','select a time zone','success', 'time zone will be use', fa('fas','fa-key'), fa('fas','fa-user'), fa('fas','fas-envelope'),fa('fas','fa-user-plus'), fa('fas', 'fa-globe'));

            $this->assertEquals('',$form);
        }


    }
}