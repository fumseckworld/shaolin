<?php

namespace App\Search {

    use Nol\Database\Found\Search;
    use Nol\Html\Form\Generator\FormGenerator;
    use stdClass;

    class ArticlesSearch extends Search
    {
        protected static string $table = 'articles';

        /**
         * @inheritDoc
         */
        public function form(FormGenerator $formGenerator): string
        {
            return $formGenerator
                ->open('/')
                ->select('value', ['sql' => 'select title from articles'])
                ->select('value', ['sql' => 'select created_at from articles ORDER BY created_at desc'])
                ->select('value', [], ['a', 'b'])
                ->close('search');
        }

        /**
         * @inheritDoc
         */
        public function each(stdClass $record, bool $global): string
        {
            return sprintf(
                '<section><header><h2>%s</h2></header><article>%s</article></section>',
                $record->title,
                nl2br($record->content)
            );
        }
    }
}
