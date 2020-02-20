<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateTest extends Command
    {

        protected static $defaultName = 'make:test';

        protected function configure()
        {

            $this

                ->setDescription('Create a new test')
                ->addArgument('test', InputArgument::REQUIRED, 'The test name')
                ->addArgument('directory',InputArgument::OPTIONAL,'The directory to create the test');
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

            $migration = $input->getArgument('test');

            if(preg_match("#^[a-z]([a-z_]+)$#",$migration) !== 1)
            {
                $io->error('You must use snake case syntax to generate the test');
                return  1;
            }

            $class = collect(explode('_',$migration))->for('ucfirst')->join('');

            $file  =  base('tests',ucfirst($input->getArgument('directory')),"$class.php");

            if (file_exists($file))
            {
                $io->error('The tests already exist');
                return 1;
            }
            $x= ucfirst($input->getArgument('directory'));

            if (def($x) && !is_dir(base('tests',$x)))
            {
                mkdir(base('tests',$x));
            }
            $namespace  = def($x) ? "Testing\\$x" : 'Testing' ;
            $io->title('Generation of the test');


            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace $namespace {

    use Eywa\Testing\Unit;
    
    class $class extends Unit
    {
    
    }
}
")->flush())
            {
                $io->success('The test was successfully generated');
                return 0;
            }

            $io->error('The creation of the test has failed');
            return 1;


        }
    }
}