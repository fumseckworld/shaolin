<?php


namespace Testing\View;


use App\Models\User;
use DI\DependencyException;
use DI\NotFoundException;
use Eywa\Exception\Kedavra;
use Eywa\Http\Response\Response;
use Eywa\Http\View\View;
use PHPUnit\Framework\TestCase;

class EywaTest extends TestCase
{

    /**
     * @var Response
     */
    private Response $logged;
    /**
     * @var Response
     */
    private Response $guest;

    /**
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         */
        public function setup(): void
        {
            app()->cache()->destroy('welcome.php');
            $this->logged = (new Response((new View('eywa','eywa','eywa',['article_title'=>'A la une','connected'=> true,'users' => User::all()]))->render()))->send();
            $this->guest = (new Response((new View('eywa','eywa','eywa',['article_title'=> 'Le figaro ferme son atelier','connected'=> false,'users' => User::all()]))->render()))->send();
        }

        public function test()
        {
            $this->assertTrue($this->logged->success());
            $this->assertStringContainsString('login',$this->logged->content());
            $this->assertStringContainsString('<p>1</p>',$this->logged->content());
            $this->assertStringContainsString('<link rel="stylesheet"  href="/css/app.css">',$this->logged->content());
            $this->assertStringContainsString('<script src="/js/app.js"></script>',$this->logged->content());
            $this->assertStringContainsString('mailto:',$this->logged->content());
            $this->assertStringContainsString('login',$this->logged->content());
            $this->assertStringContainsString('you are logged',$this->logged->content());
            $this->assertStringContainsString('<div class="alert danger">you must be logged</div>',$this->logged->content());
            $this->assertStringContainsString('<h1>A la une</h1>',$this->logged->content());


            $this->assertTrue($this->guest->success());
            $this->assertStringContainsString('<p>1</p>',$this->guest->content());
            $this->assertStringContainsString('<div class="alert danger">you must be logged</div>',$this->guest->content());
            $this->assertStringContainsString('login',$this->guest->content());
            $this->assertStringContainsString('<link rel="stylesheet"  href="/css/app.css">',$this->guest->content());
            $this->assertStringContainsString('<script src="/js/app.js"></script>',$this->guest->content());
            $this->assertStringContainsString('mailto:',$this->guest->content());
            $this->assertStringContainsString('<h1>Le figaro ferme son atelier</h1>',$this->guest->content());
            $this->assertStringContainsString('you must be logged',$this->guest->content());
        }
}