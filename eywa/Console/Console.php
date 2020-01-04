<?php


namespace Eywa\Console {


    use Exception;
    use Eywa\Console\Lang\CreateCatalogues;
    use Eywa\Console\Lang\UpdateCatalogues;
    use Symfony\Component\Console\Application;


    class Console
    {
        
        private Application $command;

        public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
        {

            clear_terminal();

            $this->command = new Application($name, $version);

        }

        /**
         *
         * Add a command
         *
         * @param  array $commands
         *
         * @return Console
         *
         */
        public function add(array $commands): Console
        {

            foreach ($commands as $command)

                $this->command->add($command);

            return $this;
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

           $commands = [
                new CreateCatalogues(),new UpdateCatalogues()
           ];
            $this->add($commands);

            return $this->command->run();
        }
    }
}