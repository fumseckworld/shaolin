<?php


namespace Testing\Http;

use Eywa\Exception\Kedavra;
use Eywa\Http\Response\JsonResponse;
use PHPUnit\Framework\TestCase;

class JsonReponseTest extends TestCase
{


    /**
     * @throws Kedavra
     */
    public function test()
    {
        $this->assertEquals('{"os":"linux"}',(new JsonResponse(['os'=> 'linux']))->send()->content());
    }
}