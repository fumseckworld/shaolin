<?php

namespace Testing\Request;

use App\Forms\Form;
use App\Forms\LoginForm;
use DI\DependencyException;
use DI\NotFoundException;
use Imperium\Exception\Kedavra;
use Imperium\Http\Request\Request;
use Imperium\Testing\Unit;

class ValidatorTest extends Unit
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function testDisplay()
    {
        $this->def(
            (new LoginForm())->display()
        );
    }
    
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testRequestSuccess()
    {
        $this->success(
            (new LoginForm())->apply(
                new Request(['email' => 'ale@a.fr', 'password' => '000000000'])
            )->see('ok')
        );
    }
    
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function testRequestFail()
    {
        $this->failure(
            (new LoginForm())->apply(
                new Request(['email' => 'al', 'password' => '000000000'])
            )->see('ok')
        );
        
        $this->failure(
            (new LoginForm())->apply(
                new Request(['email' => 'al@a.fr', 'password' => '000'])
            )->see('ok')
        );
        
        
        $this->success(
            (new LoginForm())->apply(
                new Request(['email' => 'al@a.fr', 'password' => '000'])
            )->to('/')
        );
    }
    
    /**
     * @throws DependencyException
     * @throws Kedavra
     * @throws NotFoundException
     */
    public function testForm()
    {
        $this->success(
            (new Form())->apply(
                new Request(
                    [
                        'username' => 'aaaa',
                        'age' => '21'
                    ]
                )
            )->to('/login')
        )
            ->def(
                (new Form())->display()
            )
            ->failure(
                (new Form())->apply(
                    new Request(
                        [
                            'username' => 'a20ea',
                            'age' => 12
                        ]
                    )
                )->see('ok')
            );
    }
}
