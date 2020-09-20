<?php

namespace App\Models {

    use Nol\Database\Model\Model;
    use stdClass;

    class Articles extends Model
    {
        protected static string $table = 'articles';

        public function each(stdClass $record): string
        {
            return sprintf(
                '<article><header><h2>%s</h2></header>%s</article>',
                $record->title,
                nl2br($record->content)
            );
        }
    }
}
