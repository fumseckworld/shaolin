<?php

namespace Testing\Connect;

use Imperium\Database\Connection\Connect;
use Imperium\Testing\Unit;

class ConnectTest extends Unit
{
    private Connect $db;
    public function setUp(): void
    {
        $this->db = new Connect();
    }

    public function testInstance()
    {
        $this->is(Connect::class, $this->db);
    }
}
