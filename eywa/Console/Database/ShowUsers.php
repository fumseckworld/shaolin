<?php
    
namespace Eywa\Console\Database
{

    use Eywa\Database\User\User;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ShowUsers extends Command
    {
        protected static $defaultName = 'check:users';
            
        protected function configure(): void
        {
            $this->setDescription("Check users")
            ->addArgument('env', InputArgument::REQUIRED, 'The base environment');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $env = strval($input->getArgument('env'));

            not_in(['dev','prod','test'], $env, true, 'The env must be dev, prod or test');

            if (equal($env, 'dev')) {
                $users = (new User(development()))->show();
            } elseif (equal($env, 'prod')) {
                $users = (new User(production()))->show();
            } else {
                $users = (new User(tests()))->show();
            }
            if (not_def($users)) {
                $io->error("No users found");
                return 1;
            }

            $x = new Table($output);
            $x->setStyle('box')->setHeaders(['name'])->setRows($users)->render();

            return 0;
        }
    }
}
