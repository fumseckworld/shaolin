<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateController extends Command
    {

        protected static $defaultName = 'make:controller';

        protected function configure()
        {

            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new controller')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:controller controller_name')->addArgument('controller', InputArgument::REQUIRED, 'The controller name.');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            $io->title('Generation of the controller');

            $controller = ucfirst(str_replace('Controller', '', $input->getArgument('controller')));
            append($controller, 'Controller');


            $file = $controller ;

            $controller  =  base('app','Controllers',"$controller.php");
            $namespace = 'App' . '\\' . 'Controllers';
            if (file_exists($controller))
            {
                $io->error("The $file controller already exist");

                return 1;
            }
            chdir(base('app','Controllers'));
            if ((new File("$file.php", EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace { \n\n\tuse  Eywa\Http\Controller\Controller;\n\n\tClass $file extends Controller\n\t{\n\n\t\tpublic function before_action()\n\t\t{\n\n\t\t}\n\n\t\tpublic function after_action()\n\t\t{\n\n\t\t}\n\n\t}\n\n}\n")->flush()) {
                $io->success("The $file controller was generated successfully");

                return 0;
            }

            return 1;
        }

    }
}