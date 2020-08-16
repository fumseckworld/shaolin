<?php

namespace Testing\Response;

use Nol\Testing\Unit;

class JsonResponseTest extends Unit
{
    public function testGetJson()
    {
        $this->def($this->json(['a' => 206,'darkness' => ['kings' => 'secret']])->content());
    }
}
