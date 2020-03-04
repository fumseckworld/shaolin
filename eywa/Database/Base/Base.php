<?php


declare(strict_types=1);

namespace Eywa\Database\Base {

    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;

    class Base
    {

        /**
         * @var string
         */
        private string $env;

        /**
         * Base constructor.
         * @param string $env
         */
        public function __construct(string $env)
        {
            $this->env = $env;

        }

        /**
         * @return bool
         * @throws Kedavra
         */
        public function clean()
        {
            if (equal($this->env,'dev'))
            {
                foreach ((new Table(development()))->show() as $table)
                    if (different($table,'migrations'))
                        is_false((new Table(development()))->from($table)->truncate(),true,"Failed to truuncate the $table table");
                return  true;
            }

            if (equal($this->env,'prod'))
            {
                foreach ((new Table(production()))->show() as $table)
                    if (different($table,'migrations'))
                        is_false((new Table(production()))->from($table)->truncate(),true,"Failed to truuncate the $table table");
                return  true;
            }

            if (equal($this->env,'any'))
            {
                foreach ((new Table(production()))->show() as $table)
                    if (different($table,'migrations'))
                        is_false((new Table(production()))->from($table)->truncate(),true,"Failed to truuncate the $table table");

                foreach ((new Table(development()))->show() as $table)
                    if (different($table,'migrations'))
                        is_false((new Table(development()))->from($table)->truncate(),true,"Failed to truuncate the $table table");
                return  true;
            }


            return true;
        }
    }
}