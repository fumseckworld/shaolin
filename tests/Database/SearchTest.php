<?php

namespace Testing\Database;

use App\Search\ArticlesSearch;
use Nol\Html\Form\Generator\FormGenerator;
use Nol\Testing\Unit;
use stdClass;

class SearchTest extends Unit
{

    public function testSuccess()
    {
        $record = new StdClass();
        $record->title = 'A free user';
        $record->content = 'Users can be free for freedom everywhere';
        $this->def(
            $this->search(ArticlesSearch::class)->form(new FormGenerator()),
            $this->search(ArticlesSearch::class)->search('royal', $this->connect()),
            $this->search(ArticlesSearch::class)->each($record, false)
        )
        ->contains($this->search(ArticlesSearch::class)->each($record, false), $record->title, $record->content);
    }
}
