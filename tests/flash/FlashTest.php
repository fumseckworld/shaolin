<?php

namespace Testing\flash;


use Imperium\Flash\Flash;
use PHPUnit\Framework\TestCase;

class FlashTest extends TestCase
{

    /**
     * @var Flash
     */
    private $flash;

    public function setUp()
    {
        $this->flash = new Flash();
    }

    public function test()
    {
        $this->flash->success('Linux was found');
        $this->flash->failure('Windows was found');
        $this->assertEquals('Linux was found',$this->flash->get(Flash::SUCCESS_KEY));
        $this->assertEquals('',$this->flash->get(Flash::SUCCESS_KEY));
        $this->assertEquals('Windows was found',$this->flash->get(Flash::FAILURE_KEY));
        $this->assertEquals('',$this->flash->get(Flash::FAILURE_KEY));
    }

}