<?php

namespace Testing\Response;

use Nol\Http\Response\RedirectResponse;
use Nol\Testing\Unit;

class RedirectResponseTest extends Unit
{

    public function test()
    {
        $this->success((new RedirectResponse('/'))->send()->to('/'));
    }
}
