<?php


namespace Testing\Auth;


use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Testing\Unit;

/**
 * Class AuthTest
 * @package Testing\Auth
 */
class AuthTest extends Unit
{

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_success()
    {
        $this->assertTrue($this->auth()->login('00000000','1')->to('/home'));
        $this->assertFalse($this->auth()->connected());
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_fail()
    {
        $this->assertTrue($this->auth()->login('00adsza000000','1')->to('/'));
        $this->assertTrue($this->auth()->login('00adsza000000','20001')->to('/'));
    }


    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_remove()
    {
        $this->assertTrue($this->auth()->remove_account()->to('/'));
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function test_current()
    {
        $this->assertEquals([],$this->auth()->current());
    }

    /**
     * @throws Kedavra
     */
    public function test_logout()
    {
        $this->assertTrue($this->auth()->logout()->to('/'));
    }


}