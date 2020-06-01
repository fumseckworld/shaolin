<?php

namespace Testing\Request;

use ArrayIterator;
use Directory;
use Imperium\Http\Parameters\Bag;
use Imperium\Http\Request;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\fileExists;

class RequestTest extends TestCase
{
    public function testEmptyRequest()
    {
        $request = new Request();
        $this->assertEmpty($request->query()->all());
        $this->assertEmpty($request->request()->all());
        $this->assertEmpty($request->cookie()->all());
        $this->assertEmpty($request->server()->all());
        $this->assertEmpty($request->args()->all());
        $this->assertEmpty($request->files()->all());
    }
    public function testRequest()
    {
        $request = new Request(['id' => 4, 'username' => 'Willy']);
        $this->assertEquals(4, $request->request()->get('id'));
        $this->assertEquals('Willy', $request->request()->get('username'));
        $this->assertEmpty($request->query()->all());
        $this->assertEmpty($request->cookie()->all());
        $this->assertEmpty($request->server()->all());
        $this->assertEmpty($request->args()->all());
        $this->assertEmpty($request->files()->all());
    }

    public function testQuery()
    {
        $request = new Request(['id' => 4, 'username' => 'Willy'], ['page' => 50, 'genre' => 'thriller']);

        // $_POST

        $this->assertEquals(4, $request->request()->get('id'));
        $this->assertEquals('Willy', $request->request()->get('username'));

        // $_GET

        $this->assertNotEmpty($request->query()->all());
        $this->assertEquals(50, $request->query()->get('page'));
        $this->assertEquals('thriller', $request->query()->get('genre'));

        // others

        $this->assertEmpty($request->cookie()->all());
        $this->assertEmpty($request->server()->all());
        $this->assertEmpty($request->args()->all());
        $this->assertEmpty($request->files()->all());
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

        $this->assertEmpty($request->server()->all());
        $this->assertEmpty($request->args()->all());
        $this->assertEmpty($request->files()->all());
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


        $this->assertNotEmpty($request->server()->all());
        $this->assertEquals('/', $request->server()->get('REQUEST_URI'));
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


        $this->assertNotEmpty($request->args()->all());
        $this->assertEquals('jour-de-gloire', $request->args()->get('slug'));
    }

    public function testSecure()
    {
        $this->assertFalse((new Request())->secure());
    }

    public function testIp()
    {
        $this->assertEquals('127.0.0.1', (new Request())->ip());
        $this->assertTrue((new Request())->local());
    }

    public function testMake()
    {
        $this->assertInstanceOf(Request::class, Request::make());
    }

    public function testHas()
    {
        $request = new Request();
        $key = 'a';
        $value = 'a';
        $this->assertFalse($request->query()->has($key));
        $this->assertTrue($request->query()->add([$key => $value])->has($key));
    }

    public function testInt()
    {
        $this->assertEquals(300, (new Request(['soldiers' => '300']))->request()->int('soldiers'));
    }

    public function testDigits()
    {
        $this->assertEquals(
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
        $this->assertEquals(
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
        $this->assertEquals('30days', (new Request(['days' => '30 days']))->request()->alnum('days'));
    }

    public function testBoolean()
    {
        $this->assertTrue((new Request(['a' => '1']))->request()->bool('a'));
        $this->assertFalse((new Request(['a' => '0']))->request()->bool('a'));
    }

    public function testValues()
    {
        $this->assertEquals(['a', 'b', 'c'], (new Request(['a', 'b', 'c']))->request()->values());
    }

    public function testKeys()
    {
        $this->assertEquals([0, 1, 2], (new Request(['a', 'b', 'c']))->request()->keys());
    }

    public function testDestroy()
    {
        $this->assertFalse((new Request())->request()->destroy('a'));
        $this->assertTrue((new Request(['a' => 152]))->request()->destroy('a'));
    }
    public function testSet()
    {
        $this->assertInstanceOf(Bag::class, (new Request())->request()->set('a', 'b'));
        $this->assertEquals(2, (new Request())->query()->set('a', 2)->get('a'));
    }

    public function testCount()
    {
        $this->assertEquals(1, (new Request())->request()->set('a', 'a')->count());
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf(ArrayIterator::class, (new Request())->request()->getIterator());
    }
}
