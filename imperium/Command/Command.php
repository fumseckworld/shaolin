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
         */
        public function __construct(string $name = "UNKNOWN", string $version = 'UNKNOWN')
        {
            clear_terminal();

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
            $commands = [
                new GenerateController(),
                new CreateDatabase(),
                new CleanDatabase(),
                new Server(),
                new GenerateRessource(),
                new MigrateDatabase(),
                new RollbackDatabase(),
                new SeedDatabase(),
                new App(),
                new GenerateUser(),
                new AddRoutes(),
                new RoutesList(),
                new UpdateRoutes(),
                new RemoveRoutes(),
                new GenerateCommand(),
                new DumpDatabase(),
                new UpdateDatabase(),
                new GenerateMigrations(),
                new FindRoute(),
                new MaintenanceMode(),
                new RunMode(),
                new Dkim()


            ];

             $this->add($commands)->add(commands());
             return $this->command->run();
        }

        private function add(array $commands)
        {
            foreach ($commands as $command)
                $this->command->add($command);

            return $this;
        }
    }
}
