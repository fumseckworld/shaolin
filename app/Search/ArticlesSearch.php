<?php

namespace App\Search {

    use Nol\Database\Found\Search;
    use stdClass;

    class ArticlesSearch extends Search
    {
        protected static string $table = 'articles';

        /**
         * @inheritDoc
         */
        public function form(): string
        {
            return '';
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
