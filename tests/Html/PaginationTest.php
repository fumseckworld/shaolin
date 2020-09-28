<?php

namespace Testing\Html;

use Nol\Html\Pagination\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function test()
    {
        $pagination = (new Pagination(1, 20, 50))->render(['user']);
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('dernier', $pagination);
        $this->assertStringNotContainsString('previous', $pagination);

        $pagination = (new Pagination(2, 20, 50))->render(['user']);
        $this->assertStringContainsString('first', $pagination);
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringNotContainsString('dernier', $pagination);


        $pagination = (new Pagination(2, 2, 50))->render(['user']);
        $this->assertStringContainsString('first', $pagination);
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringContainsString('dernier', $pagination);


        $pagination = (new Pagination(2, 2, 4))->render(['user']);
        $this->assertStringNotContainsString('first', $pagination);
        $this->assertStringNotContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringNotContainsString('dernier', $pagination);
        $pagination = (new Pagination(3, 2, 4))->render(['user']);
        $this->assertEmpty($pagination);
    }
}
