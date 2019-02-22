<?php

namespace Imperium\Command {

    use Exception;
    use Symfony\Component\Console\Application;

    class Command
    {

        /**
         * @var Application
         */
        private $command;

        /**
         *
         * Command constructor.
         *
         * @param string $name
         * @param string $version
         *
         */
        public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
        {
            $this->command = new Application($name,$version);
        }

        /**
         *
         * Execute the command
         *
         * @return int
         *
         * @throws Exception
         *
         */
        public function run(): int
        {
             $this->command->addCommands(commands());

             return $this->command->run();
        }
    }
}