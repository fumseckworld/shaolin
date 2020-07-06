<?php

namespace Testing\Response;

use Imperium\Http\Response\RedirectResponse;
use Imperium\Testing\Unit;

class RedirectResponseTest extends Unit
{

    public function test()
    {
        $this->success((new RedirectResponse('/'))->send()->to('/'));
    }
}
