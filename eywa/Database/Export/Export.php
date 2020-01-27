<?php

declare(strict_types=1);

namespace Eywa\Database\Export {


    class Export
    {
        public function __construct()
        {
        }

        public function dump()
        {
            $quote = $this->determineQuote();
            $command = [
                "{$quote}mysqldump{$quote}",

            ];


            $command[] ='--extended-insert --add-drop-table' ;

            d(join(' ',$command));

        }

        protected function determineQuote(): string
        {

        }
    }
}