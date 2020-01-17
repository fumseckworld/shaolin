<?php


namespace Testing\Http;


use Eywa\Http\Response\Response;
use PHPUnit\Framework\TestCase;

class ReponseTest extends TestCase
{

    /**
     *
     * The response instance
     *
     */
    private Response $response;

    public function setUp(): void
    {
        $this->response = new Response();
    }

    public function test_resonse_ok()
    {
        $this->assertTrue($this->response->success());
        $this->assertTrue($this->response->is(HTTP_OK));
        $this->assertFalse($this->response->is(HTTP_NOT_FOND));
        $this->assertFalse($this->response->is(HTTP_SERVER_ERROR));
        $this->assertFalse($this->response->is(HTTP_CLIENT_ERROR));
        $this->assertFalse($this->response->is(HTTP_REDIRECTION));
        $this->assertFalse($this->response->is(HTTP_REDIRECTION));
        $this->assertEquals('I am a view',$this->response->set_content('I am a view')->content());
        $this->assertEquals(200,$this->response->status());
        $this->assertFalse($this->response->redirect());
    }

    public function test()
    {
        $this->assertFalse($this->response->set_status(404)->success());
        $this->assertEquals(404,$this->response->set_status(404)->send()->status());
        $this->assertEquals('not found',$this->response->set_status(404)->set_content('not found')->send()->content());
        $this->assertTrue($this->response->set_status(404)->is(HTTP_NOT_FOND));

    }
}