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

        protected function configure():void
        {

            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new controller')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:controller controller_name')->addArgument('controller', InputArgument::REQUIRED, 'The controller name.')->addArgument('directory',InputArgument::OPTIONAL,'The controller directory')->addArgument('layout',InputArgument::OPTIONAL,'the layout to use');
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

            $controller = strval($input->getArgument('controller'));
            $layout = strval($input->getArgument('layout'));
            $directory = ucfirst(strval($input->getArgument('directory')));

            if(preg_match("#^[a-z]([a-z_]+)$#",$controller) !== 1)
            {
                $io->error('You must use snake case syntax to generate the controller');
                return  1;
            }

            if (def($directory) && ! is_dir(base('app','Controllers',$directory)))
                mkdir(base('app','Controllers',$directory));


            $controller_class = collect(explode('_',$controller))->for('ucfirst')->join('');

            $controller_file  = def($directory)?  base('app','Controllers',$directory,"$controller_class.php"): base('app','Controllers',"$controller_class.php");

            if (file_exists($controller_file))
            {
                $io->error(sprintf('The %s controller already exist',$controller_class));

                return 1;
            }
            $io->title('Generation of the controller');

            $namespace = def($directory) ? "App\Controllers\\$directory" : 'App\Controllers';

            $layout = def($layout) ? "protected static string \$layout = '$layout'" :  "protected static string \$layout = 'layout';";

            $directory = def($directory) ? "protected static string \$directory = '$directory';" : '';

            if ((new File($controller_file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace { 

	use Eywa\Http\Controller\Controller;
    use Eywa\Http\Request\Request;
    
	Class $controller_class extends Controller
	{

        $layout
    
        $directory
        
     
        
    }
}")->flush()) {
                $io->success("The $controller_class controller has been generated successfully");

                return 0;
            }
            $io->error("The $controller_class controller generation has failed");
            return 1;
        }

    }
}