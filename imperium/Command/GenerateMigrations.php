<?php


namespace Imperium\Command {

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class GenerateMigrations extends Command
    {
        protected static $defaultName = 'db:add';

        protected function configure()
        {
            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new migration')
                // the full command description shown when running the command with
                // the "--help" option
                ->addArgument('name', InputArgument::REQUIRED, 'The migration name.');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $name  = $input->getArgument('name');

            shell_exec("vendor/bin/phinx create $name");
            shell_exec("vendor/bin/phinx seed:create $name");
            return 0;
        }

    }
}