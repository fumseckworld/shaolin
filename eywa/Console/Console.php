<?php


namespace Eywa\Console {


    use Exception;
    use Eywa\Console\App\Coverage;
    use Eywa\Console\App\Dkim;
    use Eywa\Console\App\Key;
    use Eywa\Console\App\Serve;
    use Eywa\Console\Cache\ClearCache;
    use Eywa\Console\Database\MigrateDatabase;
    use Eywa\Console\Database\SeedDatabase;
    use Eywa\Console\Generate\GenerateController;
    use Eywa\Console\Generate\GenerateModel;
    use Eywa\Console\Generate\GenerateRouteBase;
    use Eywa\Console\Generate\GenerateView;
    use Eywa\Console\Lang\CreateCatalogues;
    use Eywa\Console\Lang\UpdateCatalogues;
    use Eywa\Console\Mode\Maintenance;
    use Eywa\Console\Mode\ProductionMode;
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
               new CreateCatalogues(),new UpdateCatalogues(), new AddRoute(), new UpdateRoute(), new ListRoute(), new RemoveRoute(), new GenerateRouteBase(), new GenerateView(), new GenerateController(),new GenerateModel(),
               new ClearCache(), new Dkim(), new Serve(),new Key(),new SeedDatabase(),new Maintenance(), new ProductionMode(),new MigrateDatabase(),new Coverage()

           ];

            $this->add($commands)->add(commands());

            return $this->command->run();
        }
    }
}