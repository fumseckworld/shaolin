<?php


namespace Testing\Security {

    use Eywa\Testing\Unit;

    class AuthTest extends Unit
    {
        public function test_success()
        {
            $this->assertTrue($this->auth()->login('Helmer Torphy', '00000000')->to('/home'));
            $this->assertTrue($this->auth()->login('Helmer Torphy', '0000000')->to('/login'));
            $this->assertTrue($this->auth()->login('a', 'azd')->to('/login'));
            $this->assertTrue($this->auth()->login('', '')->to('/login'));
            $this->assertFalse($this->auth()->connected());
            $this->assertInstanceOf(\stdClass::class, $this->auth()->current());
            $this->assertTrue($this->auth()->logout()->to('/'));
            $this->assertTrue($this->auth()->clean());
            $this->assertTrue($this->auth()->delete_account()->to('/'));
            $this->assertFalse($this->auth()->is('admin'));
            $this->assertFalse($this->auth()->is('redac'));
            $this->assertFalse($this->auth()->is('superuser'));
            $this->assertTrue($this->auth()->login('a', 'a')->to('/login'));
        }
    }
}
