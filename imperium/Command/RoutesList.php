<?php


namespace Imperium\Command {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class RoutesList extends Command
    {
        protected static $defaultName = 'routes:list';



        protected function configure()
        {
            $this->setDescription('List all routes created')->setAliases(['routes']);
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int|void|null
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            routes($output, app()->model()->from('routes')->all('id',ASC));

        }

    }
}