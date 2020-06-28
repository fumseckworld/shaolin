<?php

namespace Testing\Response;

use Imperium\Http\Response\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    public function testSuccess()
    {
        $this->assertTrue((new Response())->send()->success());
        $this->assertTrue((new Response())->send()->is(200));
        $this->assertEquals(200, (new Response())->send()->status());
        $this->assertEquals(2, (new Response('<p>promise</p><p>a</p>'))->sum('<p>'));
        $this->assertEquals('<p>promise</p><p>a</p>', (new Response('<p>promise</p><p>a</p>'))->content());
        $this->assertTrue((new Response('<p>promise</p><p>a</p>'))->see('promise'));
        $this->assertFalse((new Response('<p>promise</p><p>a</p>'))->see('promisesa'));
        $this->assertTrue((new Response('', '', 404))->error());
        $this->assertTrue((new Response('', '', 403))->forbidden());
        $this->assertTrue((new Response('', '', 301))->redirect());
    }
}
