<?php

namespace App\Search {

    use Nol\Database\Found\Search;
    use Nol\Html\Form\Generator\FormGenerator;
    use stdClass;

    class ArticlesSearch extends Search
    {
        protected static string $table = 'articles';

        protected static string $prefix = 'article';

        /**
         * @inheritDoc
         */
        public function form(FormGenerator $formGenerator): string
        {
            return $formGenerator
                ->open('/search')
                ->select('created_at', [], [2020, 2019, 2018, 2017, 2016, 2015, 2014, 2013, 2012])
                ->close('watch')
                ->open('/article')
                ->select('title', ['sql' => 'select title from articles'])->close('show')
                ->get();
        }

        /**
         * @inheritDoc
         */
        public function each(stdClass $record, bool $global): string
        {
            return sprintf(
                '<article><header><h2>%s</h2></header>%s</article>',
                $record->title,
                nl2br($record->content)
            );
        }

        /**
         * @inheritDoc
         */
        public function found(stdClass $record): string
        {
            return sprintf(
                '<article><header><h2>%s</h2></header>%s</article>',
                $record->title,
                nl2br($record->content)
            );
        }
    }
}
