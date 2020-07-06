<?php

namespace Testing\Request;

use ArrayIterator;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Request\Request;
use Imperium\Testing\Unit;

class RequestTest extends Unit
{
    public function testEmptyRequest()
    {
        $request = new Request();
        $this->empty(
            $request->query()->all(),
            $request->request()->all(),
            $request->cookie()->all(),
            $request->server()->all(),
            $request->args()->all(),
            $request->files()->all()
        );
    }
    public function testRequest()
    {
        $request = new Request(['id' => 4, 'username' => 'Willy']);
        $this->identical(4, $request->request()->get('id'))
            ->identical('Willy', $request->request()->get('username'))
            ->empty(
                $request->query()->all(),
                $request->cookie()->all(),
                $request->server()->all(),
                $request->args()->all(),
                $request->files()->all()
            );
    }

    public function testQuery()
    {
        $request = new Request(['id' => 4, 'username' => 'Willy'], ['page' => 50, 'genre' => 'thriller']);

        // $_POST

        $this->assertEquals(4, $request->request()->get('id'));
        $this->assertEquals('Willy', $request->request()->get('username'));

        // $_GET

        $this->assertNotEmpty($request->query()->all());
        $this->identical(50, $request->query()->get('page'))->identical('thriller', $request->query()->get('genre'));

        $this->empty(
            $request->cookie()->all(),
            $request->server()->all(),
            $request->args()->all(),
            $request->files()->all()
        );
    }


    public function testCookies()
    {
        $request = new Request(
            ['id' => 4, 'username' => 'Willy'],
            ['page' => 50, 'genre' => 'thriller'],
            ['os' => 'linux', 'distribution' => 'arch']
        );

        // $_POST

        $this->assertEquals(4, $request->request()->get('id'));
        $this->assertEquals('Willy', $request->request()->get('username'));

        // $_GET

        $this->assertNotEmpty($request->query()->all());
        $this->assertEquals(50, $request->query()->get('page'));
        $this->assertEquals('thriller', $request->query()->get('genre'));

        // $_COOKIE

        $this->assertNotEmpty($request->cookie()->all());
        $this->assertEquals('linux', $request->cookie()->get('os'));
        $this->assertEquals('arch', $request->cookie()->get('distribution'));


        // others

        $this->empty(
            $request->server()->all(),
            $request->args()->all(),
            $request->files()->all()
        );
    }



    public function testFiles()
    {
        $request = new Request(
            ['id' => 4, 'username' => 'Willy'],
            ['page' => 50, 'genre' => 'thriller'],
            ['os' => 'linux', 'distribution' => 'arch'],
            [
                'files' =>
                [
                    'name' => ['LICENSE', 'README.md'],
                    'type' => ['application/octet-stream', 'text/markdown'],
                    'tmp_name' => ['tmp/phppSa5lg', '/tmp/phpvNjGHg'],
                    'error' => [UPLOAD_ERR_OK, UPLOAD_ERR_OK],
                    'size' => [35141, 2133]
                ]
            ]
        );

        // $_POST

        $this->assertEquals(4, $request->request()->get('id'));
        $this->assertEquals('Willy', $request->request()->get('username'));

        // $_GET

        $this->assertNotEmpty($request->query()->all());
        $this->assertEquals(50, $request->query()->get('page'));
        $this->assertEquals('thriller', $request->query()->get('genre'));

        // $_COOKIE

        $this->assertNotEmpty($request->cookie()->all());
        $this->assertEquals('linux', $request->cookie()->get('os'));
        $this->assertEquals('arch', $request->cookie()->get('distribution'));

        // $_FILES

        $this->assertNotEmpty($request->files()->all());
        $this->assertContains('LICENSE', $request->files()->all());
        $this->assertContains('README.md', $request->files()->all());
        $this->assertContains(0, $request->files()->errors());
        $this->assertContains(35141, $request->files()->sizes());
        $this->assertContains(2133, $request->files()->sizes());
        $this->assertContains('application/octet-stream', $request->files()->types());
        $this->assertContains('text/markdown', $request->files()->types());

        $this->assertCount(2, $request->files()->all());
        $this->assertCount(2, $request->files()->types());
        $this->assertCount(2, $request->files()->sizes());
        $this->assertCount(2, $request->files()->errors());

        $this->assertTrue($request->files()->ok());
        // others

        $this->assertEmpty($request->server()->all());
        $this->assertEmpty($request->args()->all());

        $request = new Request([], [], [], ['files' =>
        [
            'name' => ['LICENSE', 'README.md'],
            'type' => ['application/octet-stream', 'text/markdown'],
            'tmp_name' => ['tmp/phppSa5lg', '/tmp/phpvNjGHg'],
            'error' => [UPLOAD_ERR_OK, 1],
            'size' => [35141, 2133]
        ]]);
        $this->assertFalse($request->files()->ok());
    }


    public function testServer()
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            ['REQUEST_URI' => '/']
        );

        $this->empty(
            $request->query()->all(),
            $request->request()->all(),
            $request->cookie()->all(),
            $request->args()->all(),
            $request->files()->all()
        )->def($request->server()->all())->identical('/', $request->server()->get('REQUEST_URI'));
    }

    public function testArgs()
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            ['slug' => 'jour-de-gloire']
        );
        $this->empty(
            $request->query()->all(),
            $request->request()->all(),
            $request->cookie()->all(),
            $request->server()->all(),
            $request->files()->all()
        )->def($request->args()->all())->identical('jour-de-gloire', $request->args()->get('slug'));
    }

    public function testSecure()
    {
        $this->failure((new Request())->secure());
    }

    public function testIp()
    {
        $this->identical('127.0.0.1', (new Request())->ip())->success((new Request())->local());
    }

    public function testMake()
    {
        $this->is(Request::class, Request::make());
    }

    public function testHas()
    {
        $request = new Request();
        $key = 'a';
        $value = 'a';
        $this->failure($request->query()->has($key))
            ->success($request->query()->add([$key => $value])->has($key));
    }

    public function testInt()
    {
        $this->identical(300, (new Request(['soldiers' => '300']))->request()->int('soldiers'));
    }

    public function testDigits()
    {
        $this->identical(
            300,
            (new Request(
                [
                    'paragraph' =>
                    'Leonidas the king of sparte has been gone to war with 300 spartiates to defend sparte'
                ]
            ))->request()->digits('paragraph')
        );
    }
    public function testAlpha()
    {
        $this->identical(
            'Leonidasthekingofspartehasbeengonetowarwithspartiatestodefendsparte',
            (new Request(
                [
                    'paragraph' =>
                    'Leonidas the king of sparte has been gone to war with 300 spartiates to defend sparte'
                ]
            ))->request()->alpha('paragraph')
        );
    }
    public function testAlnum()
    {
        $this->identical('30days', (new Request(['days' => '30 days']))->request()->alnum('days'));
    }

    public function testBoolean()
    {
        $this->success((new Request(['a' => '1']))->request()->bool('a'))
            ->failure((new Request(['a' => '0']))->request()->bool('a'));
    }

    public function testValues()
    {
        $this->identical(['a', 'b', 'c'], (new Request(['a', 'b', 'c']))->request()->values());
    }

    public function testKeys()
    {
        $this->identical([0, 1, 2], (new Request(['a', 'b', 'c']))->request()->keys());
    }

    public function testDestroy()
    {
        $this->failure((new Request())->request()->destroy('a'))
            ->success((new Request(['a' => 152]))->request()->destroy('a'));
    }
    public function testSet()
    {
        $this->is(Bag::class, (new Request())->request()->set('a', 'b'))
            ->identical(2, (new Request())->query()->set('a', 2)->get('a'));
    }

    public function testCount()
    {
        $this->identical(1, (new Request())->request()->set('a', 'a')->count());
    }

    public function testGetIterator()
    {
        $this->is(ArrayIterator::class, (new Request())->request()->getIterator());
    }
}
