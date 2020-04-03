<?php

namespace Eywa\Console {


    use Exception;
    use Eywa\Console\App\Coverage;
    use Eywa\Console\App\Dkim;
    use Eywa\Console\App\Key;
    use Eywa\Console\App\Serve;
    use Eywa\Console\App\TestCommand;
    use Eywa\Console\Cache\ClearCache;
    use Eywa\Console\Database\CleanDatabase;
    use Eywa\Console\Database\DropTable;
    use Eywa\Console\Database\ExportDatabase;
    use Eywa\Console\Database\ImportDatabase;
    use Eywa\Console\Database\MigrateDatabase;
    use Eywa\Console\Database\RollbackDatabase;
    use Eywa\Console\Database\SeedDatabase;
    use Eywa\Console\Database\InstallDatabase;
    use Eywa\Console\Database\ShowTable;
    use Eywa\Console\Database\ShowUsers;
    use Eywa\Console\Database\TruncateTable;
    use Eywa\Console\Database\UninstallDatabase;
    use Eywa\Console\Generate\GenerateCommand;
    use Eywa\Console\Generate\GenerateContainer;
    use Eywa\Console\Generate\GenerateForm;
    use Eywa\Console\Generate\GenerateMiddleware;
    use Eywa\Console\Generate\GenerateModel;
    use Eywa\Console\Generate\GenerateValidator;
    use Eywa\Console\Generate\GenerateView;
    use Eywa\Console\Get\Documentation;
    use Eywa\Console\Get\Wanted;
    use Eywa\Console\Git\GitBlame;
    use Eywa\Console\Git\GitCommit;
    use Eywa\Console\Git\GitLog;
    use Eywa\Console\Git\GitSend;
    use Eywa\Console\Git\UngitCommand;
    use Eywa\Console\Mode\DevMode;
    use Eywa\Console\Mode\Maintenance;
    use Eywa\Console\Mode\ProductionMode;
    use Eywa\Console\Routes\AddRoute;
    use Eywa\Console\Routes\FindRoute;
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
         * @param array<mixed> $commands
         *
         * @return Console
         *
         */
        public function add(array $commands): Console
        {
            foreach ($commands as $command) {
                $this->command->add($command);
            }

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
                new ClearCache(), new Dkim(), new Serve(),new Key(),new SeedDatabase(),new Maintenance(),
                new ProductionMode(),new DevMode(),new MigrateDatabase(),new RollbackDatabase(),new Coverage(),
                new TruncateTable(),new ShowTable(),new DropTable(),new ImportDatabase(),new ExportDatabase(),
                new CleanDatabase(),new GenerateMiddleware(),new GenerateCommand(),new GenerateView(),
                new InstallDatabase(), new UninstallDatabase(),new ShowUsers(),new Documentation(),new Wanted(),
                new GenerateValidator(),new GitSend(),new TestCommand(),new GenerateCommand(),new FindRoute() ,
                new ListRoute() , new AddRoute(), new RemoveRoute(), new UpdateRoute(),new Records\Records(),
                new GenerateContainer(),new GenerateForm(),new GenerateModel(),new UngitCommand(),new GitCommit(),
                new GitLog(),new GitBlame()
            ];

            $this->add($commands)->add(commands());

            return $this->command->run();
        }
    }
}
