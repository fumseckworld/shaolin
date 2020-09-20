<?php

namespace Nol\Console {

    use Exception;
    use Nol\Console\Http\Server;
    use Symfony\Component\Console\Application;

    /**
     * Class Command
     *
     * @package Imperium\Console\Command
     * @version 12
     *
     * @property Application $app The console instance.
     *
     */
    final class Ji
    {
        /**
         *
         * @param string $name    The application name.
         * @param string $version The application version.
         *
         */
        public function __construct(string $name, string $version)
        {
            $this->app = new Application($name, $version);

            $ji = [
                new Server()
            ];
            $this->add($ji);
        }

        /**
         *
         * Add commands in the console.
         *
         * @param array $commands The commands to add.
         *
         * @return Ji
         *
         */
        final public function add(array $commands): Ji
        {
            foreach ($commands as $command) {
                $this->app->add($command);
            }

            return $this;
        }

        /**
         *
         * Execute the commands.
         *
         * @throws Exception
         *
         * @return int
         *
         */
        final public function run(): int
        {
            return $this->app->run();
        }
    }
}
