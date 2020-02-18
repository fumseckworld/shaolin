<?php


declare(strict_types=1);

namespace Eywa\Database\Base {

    use Eywa\Database\Table\Table;

    class Base
    {
        /**
         * @var Table
         */
        private Table $table;

        public function __construct()
        {
            $this->table = new Table();
        }

        public function clean()
        {
            foreach ($this->table->show() as $table)
            {
                if (different($table,'migrations'))
                    is_false((new Table())->from($table)->truncate(),true,"Error");

            }

            return true;
        }
    }
}