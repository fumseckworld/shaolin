<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateValidator extends Command
    {
        protected static $defaultName = 'make:validator';

        protected function configure(): void
        {
            $this

                ->setDescription('Create a new validator')
                ->addArgument('validator', InputArgument::REQUIRED, 'The validator name')
                ->addArgument('directory', InputArgument::OPTIONAL, 'The directory to create the validator');
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
            $io = new SymfonyStyle($input, $output);


            $validator = strval($input->getArgument('validator'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $validator) !== 1) {
                $io->error('You must use snake case syntax to generate the validator');
                return  1;
            }

            $class = collect(explode('_', $validator))->for('ucfirst')->join('');


            $dir = ucfirst(strval($input->getArgument('directory'))) ?? '';

            $namespace = def($dir) ? "App\Validators\\$dir" : 'App\Validators';


            $file  =  base('app', 'Validators', $dir, "$class.php");


            if (file_exists($file)) {
                $io->error(sprintf('The %s validator already exist', $class));
                return 1;
            }

            $io->title('Generation of the validator');

            if (!is_dir(base('app', 'Validators', $dir)) && def($dir)) {
                $io->title('Creating the directory');

                if (mkdir(base('app', 'Validators', $dir))) {
                    $io->success('Directory has been created successfully');
                }
            }

            if (file_exists($file)) {
                $io->error(sprintf('The %s validator already exist', $class));

                return 1;
            }

            if (
                (new File("$file", EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace {

    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;
    use Eywa\Validate\Validator;

    class $class extends Validator
    {
    
       /**
        * @inheritDoc
        */
        public static string \$redirect_success_url = '';

        /**
         * @inheritDoc
         */
        public static string \$redirect_error_url = '';

        /**
         * @inheritDoc
         */
        public static array \$rules = [];

        /**
         * @inheritDoc
         */
        public function success(Bag \$bag): Response
        {
        }
    }
}\n")->flush()
            ) {
                $io->success(sprintf('The %s validator has been generated successfully', $class));

                return 0;
            }
            $io->error(sprintf('The %s validator generation has failed', $class));
            return 1;
        }
    }
}
