<?php

namespace Testing\Database;

use Nol\Testing\Unit;

class SqlTest extends Unit
{
    public function testSuccess()
    {
        $table = 'articles';
        $this->def(
            $this->sql($table)->sql(),
            $this->sql($table)->where('id', '=', 4)->get(),
            $this->sql($table)->where('id', '!=', 4)->get(),
            $this->sql($table)->only('title')->get(),
            $this->sql($table)->like('a')->get(),
            $this->sql($table)->primary(),
        )
            ->identical('id', $this->sql($table)->primary())
            ->different($this->sql($table)->where('id', '=', 4)->get(), $this->sql($table)->where('id', '=', 3)->get())
            ->different($this->sql($table)->by('id')->get(), $this->sql($table)->by('id', 'ASC')->get());
    }
}
