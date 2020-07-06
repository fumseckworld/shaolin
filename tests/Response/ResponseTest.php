<?php

namespace Testing\Response;

use Imperium\Http\Response\Response;
use Imperium\Testing\Unit;

class ResponseTest extends Unit
{

    public function testSuccess()
    {
        $this->success(
            (new Response())->send()->success(),
            (new Response())->send()->is(200)
        )
            ->identic(200, (new Response())->send()->status())
            ->identic(2, (new Response('<p>promise</p><p>a</p>'))->sum('<p>'))
            ->identic('<p>promise</p><p>a</p>', (new Response('<p>promise</p><p>a</p>'))->content())
            ->success((new Response('<p>promise</p><p>a</p>'))->see('promise'))
            ->failure((new Response('<p>promise</p><p>a</p>'))->see('promisesa'))
            ->success(
                (new Response('', '', 404))->error(),
                (new Response('', '', 403))->forbidden(),
                (new Response('', '', 301))->redirect()
            );
    }
}
