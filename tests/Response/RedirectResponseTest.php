<?php

namespace Testing\Response;

use Imperium\Http\Response\RedirectResponse;
use PHPUnit\Framework\TestCase;

class RedirectResponseTest extends TestCase
{

    public function test()
    {
        $this->assertTrue((new RedirectResponse('/'))->send()->to('/'));
    }
}
