<?php

namespace Testing\Connect;

use Nol\Database\Connection\Connect;
use Nol\Testing\Unit;

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
