<?php

namespace Testing\Security {

    use App\Models\Auth\Authentication;
    use Eywa\Exception\Kedavra;
    use Eywa\Testing\Unit;
    use stdClass;

    class AuthTest extends Unit
    {

        /**
         * @throws Kedavra
         */
        public function testSuccess()
        {

            $username = Authentication::find(1)->username;

            $this->assertTrue($this->auth(Authentication::class)->login($username, '00000000')->to('/home'));
            $this->assertTrue($this->auth(Authentication::class)->login($username, '0000000')->to('/login'));
            $this->assertTrue($this->auth(Authentication::class)->login('a', 'azd')->to('/login'));
            $this->assertTrue($this->auth(Authentication::class)->login('', '')->to('/login'));
            $this->assertFalse($this->auth(Authentication::class)->connected());
            $this->assertInstanceOf(stdClass::class, $this->auth(Authentication::class)->current());
            $this->assertTrue($this->auth(Authentication::class)->logout()->to('/'));
            $this->assertTrue($this->auth(Authentication::class)->clean());
            $this->assertTrue($this->auth(Authentication::class)->deleteAccount()->to('/'));
            $this->assertFalse($this->auth(Authentication::class)->is('admin'));
            $this->assertFalse($this->auth(Authentication::class)->is('redac'));
            $this->assertFalse($this->auth(Authentication::class)->is('superuser'));
            $this->assertTrue($this->auth(Authentication::class)->login('a', 'a')->to('/login'));
        }
    }
}
