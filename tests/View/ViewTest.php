<?php


namespace Testing\View;


use Eywa\Http\View\View;
use Eywa\Testing\Unit;

class ViewTest extends Unit
{

    /**
     *
     * The view instance
     *
     */
    private View $view;

    public function setUp(): void
    {
       $this->view = new View('welcome','Linux','An os simple and easy to use');
    }

    public function test_success()
    {
        $x = $this->view->render();
        $this->assertStringContainsString('<title>Linux</title>',$x->getContent());
        $this->assertStringContainsString('<meta name="description" content="An os simple and easy to use">',$x->getContent());
        $this->assertEquals(200,$x->getStatusCode());
        $this->assertStringContainsString('<h1>welcome</h1>',$x->getContent());
    }

    public function test_change_code()
    {
        $x = $this->view->render(404);
        $this->assertStringContainsString('<title>Linux</title>',$x->getContent());
        $this->assertStringContainsString('<meta name="description" content="An os simple and easy to use">',$x->getContent());
        $this->assertEquals(404,$x->getStatusCode());
        $this->assertStringContainsString('<h1>welcome</h1>',$x->getContent());
    }

    public function test_view()
    {
        $this->assertTrue((new View('bidon','a','a'))->render()->isOk());
        $this->assertTrue($this->file(base('app','Views') .DIRECTORY_SEPARATOR .'bidon.php')->remove());
    }
}