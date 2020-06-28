<?php

namespace Testing\Connect;

use Imperium\Database\Connection\Connect;
use PHPUnit\Framework\TestCase;

class ConnectTest extends TestCase
{
    private Connect $db;
    public function setUp(): void
    {
        $this->db = new Connect();
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Connect::class, $this->db);
    }
}
