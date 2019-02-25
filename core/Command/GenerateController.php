<?php

namespace Shaolin\Command;

    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class GenerateController extends Command
    {
        protected static $defaultName = 'g';

        protected function configure()
        {
            $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Create a new controller')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('php shaolin g controller_name')
            ->addArgument('controller', InputArgument::REQUIRED, 'The controller name.');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $controller = ucfirst(str_replace('Controller','',$input->getArgument('controller')));
            append($controller,'Controller');


            $output->write("generating $controller controller\n");

            $controllers = collection(config('app','dir'))->get('controller');

            $core  = core_path(collection(config('app','dir'))->get('app'));

            $namespace = config('app','namespace') . '\\' . $controllers;


            $file =  $core .DIRECTORY_SEPARATOR . $controllers  .DIRECTORY_SEPARATOR. $controller .'.php';

            File::create($file);

            File::put($file,"<?php\n\nnamespace $namespace { \n\n\tuse Imperium\Controller\Controller;\n\n\tClass $controller extends Controller\n\t{\n\n\t}\n\n}\n");

            if (File::exist($file))
                $output->write("The controller was generated successfully\n");
        }

}