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
            if (equal($this->env, 'dev')) {
                foreach ((new Table(development(), ''))->show() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            (new Table(development(), $table))
                            ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                }
                return  true;
            }

            if (equal($this->env, 'prod')) {
                foreach ((new Table(production(), ''))->show() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            (new Table(production(), $table))
                            ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                }
                return  true;
            }

            if (equal($this->env, 'any')) {
                foreach ((new Table(production(), ''))->show() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            (new Table(production(), $table))
                            ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                }

                foreach ((new Table(development(), ''))->show() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            (new Table(development(), $table))
                            ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                }
                return  true;
            }


            return true;
        }
    }
}
