<?php

namespace Testing\Database;

use App\Models\Articles;
use DI\DependencyException;
use DI\NotFoundException;
use Nol\Exception\Kedavra;
use Nol\Testing\Unit;
use stdClass;

class ModelTest extends Unit
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     * @throws Kedavra
     */
    public function testSuccess()
    {
        $record = new StdClass();
        $record->title = 'i am a title';
        $record->content = 'i am the content';
        $this->def(
            $this->model(Articles::class)->find(1),
            $this->model(Articles::class)->paginate(1),
            $this->model(Articles::class)->paginate(2),
            $this->model(Articles::class)->different('slug', '', 1),
            $this->model(Articles::class)->search('royal'),
            $this->model(Articles::class)->search('willy'),
            $this->model(Articles::class)->search('clement'),
            $this->model(Articles::class)->table(),
            $this->model(Articles::class)->each($record),
        )->different(
            $this->model(Articles::class)->paginate(1),
            $this->model(Articles::class)->paginate(2)
        )->identical('articles', $this->model(Articles::class)->table())
        ->contains($this->model(Articles::class)->each($record), $record->content, $record->title);
    }
}
