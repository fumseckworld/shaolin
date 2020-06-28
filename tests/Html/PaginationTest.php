<?php

namespace Testing\Html;

use Imperium\Html\Pagination\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function test()
    {
        $pagination = (new Pagination(1, 20, 50))->render('users');
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('last', $pagination);
        $this->assertStringNotContainsString('previous', $pagination);

        $pagination = (new Pagination(2, 20, 50))->render('users');
        $this->assertStringContainsString('first', $pagination);
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringNotContainsString('last', $pagination);


        $pagination = (new Pagination(2, 2, 50))->render('users');
        $this->assertStringContainsString('first', $pagination);
        $this->assertStringContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringContainsString('last', $pagination);


        $pagination = (new Pagination(2, 2, 4))->render('users');
        $this->assertStringNotContainsString('first', $pagination);
        $this->assertStringNotContainsString('next', $pagination);
        $this->assertStringContainsString('previous', $pagination);
        $this->assertStringNotContainsString('last', $pagination);
        $pagination = (new Pagination(3, 2, 4))->render('users');
        $this->assertEmpty($pagination);
    }
}
