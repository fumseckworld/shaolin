<?php


namespace Eywa\Console {


    use Exception;
    use Eywa\Console\Generate\GenerateRouteBase;
    use Eywa\Console\Lang\CreateCatalogues;
    use Eywa\Console\Lang\UpdateCatalogues;
    use Eywa\Console\Routes\AddRoute;
    use Eywa\Console\Routes\ListRoute;
    use Eywa\Console\Routes\RemoveRoute;
    use Eywa\Console\Routes\UpdateRoute;
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
                new CreateCatalogues(),new UpdateCatalogues(), new AddRoute(), new UpdateRoute(), new ListRoute(), new RemoveRoute(), new GenerateRouteBase()
           ];
            $this->add($commands);

            return $this->command->run();
        }
    }
}