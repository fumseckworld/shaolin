<?php

namespace Imperium\Command {

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class Server extends Command
    {
        protected static $defaultName = 'app:run';
        protected function configure()
        {
            $this->setAliases(['serve']);
            $this->setDescription('Run a development server');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $web = 'web';
            $port = config('app','development_server_port');

            $output->write("<info>The web server has been started at :</info> http://localhost:$port\n");

            return shell_exec("php -S localhost:$port -d display_errors=1 -t $web");

        }
    }
}