<?php

namespace Testing\session;


use Imperium\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{

    /**
     *
     * @var Session
     */
    private $session;

    public function setUp()
    {
        $this->session = new Session();
    }

    public function test_save()
    {
        $session = $this->session->set('linux')->set('is')->set('better')->all();

        $this->assertContains('linux',$session);
        $this->assertContains('is',$session);
        $this->assertContains('better',$session);
    }

    public function test_get()
    {
        $this->session->set('linux')->set('is')->set('better');

        $this->assertEquals('linux',$this->session->get(0));
        $this->assertEquals('is',$this->session->get(1));
        $this->assertEquals('better',$this->session->get(2));
    }

    public function test_delete()
    {
        $this->session->set('windows');

        $this->assertTrue($this->session->remove(0));
    }

}