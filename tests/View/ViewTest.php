<?php


namespace Testing\View;


use Eywa\Exception\Kedavra;
use Eywa\File\File;
use Eywa\Http\Response\Response;
use Eywa\Http\View\View;
use Eywa\Testing\Unit;

class ViewTest extends Unit
{

    /**
     *
     * The view instance
     *
     */
    private Response $view;

    /**
     * @throws Kedavra
     */
    public function setUp(): void
    {
       $this->view = new Response((new View('linux','Linux','An os simple and easy to use'))->render());
    }

    public function tearDown(): void
    {
        $this->assertTrue(File::delete(base('app','Views','linux.php')));
        $this->assertTrue(File::delete(base('cache','linux.php')));
    }

    public function test_success()
    {
        $x = $this->view->send();
        $this->assertStringContainsString('<title>Linux</title>',$x->content());
        $this->assertStringContainsString('<meta name="description" content="An os simple and easy to use">',$x->content());
        $this->assertEquals(200,$x->status());
    }

    /**
     * @throws Kedavra
     */
    public function test_change_code()
    {
        $x = $this->view->set_status(404)->send();
        $this->assertStringContainsString('<title>Linux</title>',$x->content());
        $this->assertStringContainsString('<meta name="description" content="An os simple and easy to use">',$x->content());
        $this->assertEquals(404,$x->status());
    }

    /**
     * @throws Kedavra
     */
    public function test_view()
    {
        $this->assertTrue((new Response((new View('bidon','a','a'))->render()))->success());
        $this->assertTrue($this->file(base('app','Views') .DIRECTORY_SEPARATOR .'bidon.php')->remove());
    }
}