<?php

namespace Testing\Auth;

use Imperium\Security\Auth\Oauth;
use PHPUnit\Framework\TestCase;
use App\Models\Users;

class AuthTest extends TestCase
{

    private $session;
    
    private $auth;
    
    public function setUp(): void
    {
        $this->session = app()->session();
        $this->auth = new Oauth(app()->session());
    }

    public function test_find()
    {
        $this->assertNotEmpty($this->auth->find('2'));
    }

    public function test_count()
    {
        $this->assertEquals(100,$this->auth->count());
    }


    public function test_username()
    {
        $this->assertEmpty($this->auth->current());
    }

    public function test_logout()
    {
        $response = $this->auth->logout();
        $this->assertTrue($response->isRedirect('/'));
        
    }
    
    public function test_login_bad_password()
    {
        $response = $this->auth->login('a',5);
        $this->assertTrue($response->isRedirect('/'));
        
    }

        
    public function test_not_found()
    {
        $response = $this->auth->login('a',500);
        $this->assertTrue($response->isRedirect('/'));
        $response = $this->auth->remove_account();
        $this->assertTrue($response->isRedirect('/'));
    }


    public function test_login_valid()
    {
    
        $x = Users::find(20);
        $response = $this->auth->login('0000',20);
    
        $this->assertTrue($response->isRedirect('/home'));
        $this->assertNotEmpty($this->auth->current());
        
        $response = $this->auth->remove_account();
        $this->assertTrue($response->isRedirect('/'));
    }
    
}