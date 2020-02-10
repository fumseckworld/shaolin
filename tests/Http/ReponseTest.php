<?php


namespace Testing\Http;


use App\Models\User;
use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Http\Response\Response;
use Eywa\Http\View\View;
use PHPUnit\Framework\TestCase;

class ReponseTest extends TestCase
{

    /**
     *
     * The response instance
     *
     */
    private Response $response;

    /**
     * @throws Kedavra
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function setUp(): void
    {
        $view = (new View('welcome','welcome', 'welcome',['connected'=> true,'users' => User::all()]))->render();
        $this->response = new Response($view);
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

    /**
     * @throws Kedavra
     */
    public function test()
    {
        $this->assertFalse($this->response->set_status(404)->success());
        $this->assertEquals(404,$this->response->set_status(404)->send()->status());
        $this->assertEquals('not found',$this->response->set_status(404)->set_content('not found')->send()->content());
        $this->assertTrue($this->response->set_status(404)->is(HTTP_NOT_FOND));

    }
}