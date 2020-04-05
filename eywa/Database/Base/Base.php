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
                $tables = new Table(development());
                foreach ($tables->show()->all() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            $tables->from($table)
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
                $tables = new Table(production());
                foreach ($tables->show()->all() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            $tables->from($table)
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
                $tables = new Table(development());
                foreach ($tables->show()->all() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            $tables->from($table)
                                ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                }
                $tables = new Table(production());
                foreach ($tables->show()->all() as $table) {
                    if (different($table, 'migrations')) {
                        is_false(
                            $tables->from($table)
                                ->truncate(),
                            true,
                            sprintf(
                                'The truncate task for the %s table has failed',
                                $table
                            )
                        );
                    }
                    return  true;
                }
            }
            return true;
        }
    }
}
