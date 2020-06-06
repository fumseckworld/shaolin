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

    public function testGet()
    {
        $this->assertEmpty($this->db->get('SHOW TABLES'));
    }
    public function testExec()
    {
        $this->assertTrue($this->db->exec('SHOW TABLES'));
    }
}
