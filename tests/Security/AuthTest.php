<?php

namespace Testing\Security {

    use Eywa\Database\Model\Model;
    use Eywa\Exception\Kedavra;
    use Eywa\Security\Authentication\Auth;
    use Eywa\Testing\Unit;
    use ReflectionException;
    use stdClass;

    class AuthTest extends Unit
    {

        private Auth $auth;

        /**
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function setUp(): void
        {
            $this->auth = $this->auth('auth');
        }

        /**
         * @throws Kedavra|ReflectionException
         */
        public function testSuccess()
        {
            $username = (new Model('auth'))->find(1)->username;

            $this->assertTrue($this->auth->login($username, '00000000')->to('/home'));
            $this->assertTrue($this->auth->login($username, '0000000')->to('/login'));
            $this->assertTrue($this->auth->login('a', 'azd')->to('/login'));
            $this->assertTrue($this->auth->login('', '')->to('/login'));
            $this->assertFalse($this->auth->connected());
            $this->assertInstanceOf(stdClass::class, $this->auth->current());
            $this->assertTrue($this->auth->logout()->to('/'));
            $this->assertTrue($this->auth->clean());
            $this->assertTrue($this->auth->deleteAccount()->to('/'));
            $this->assertFalse($this->auth->is('admin'));
            $this->assertFalse($this->auth->is('redac'));
            $this->assertFalse($this->auth->is('superuser'));
            $this->assertTrue($this->auth->login('a', 'a')->to('/login'));
        }
    }
}
