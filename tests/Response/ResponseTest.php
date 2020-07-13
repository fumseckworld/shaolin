<?php

namespace Testing\Response;

use Imperium\Http\Response\Response;
use Imperium\Testing\Unit;

class ResponseTest extends Unit
{

    public function testSuccess()
    {
        $this->success(
            $this->response()->send()->ok(),
            $this->response()->send()->code(200)
        )
            ->identical(200, $this->response()->send()->status())
            ->identical(2, $this->response()->set('<p>promise</p><p>a</p>')->calc('<p>'))
            ->identical('<p>promise</p><p>a</p>', $this->response()->set('<p>promise</p><p>a</p>')->content())
            ->success($this->response()->set('<p>promise</p><p>a</p>')->see('promise'))
            ->success($this->response()->set('', 403)->forbidden())
            ->failure(
                $this->response()->set('adadazdazdaa')->see('promise'),
                $this->response()->set('', 400)->error(),
                $this->response()->set('', 404)->forbidden(),
                $this->response()->set('', 200)->redirect(),
            );
    }
}
