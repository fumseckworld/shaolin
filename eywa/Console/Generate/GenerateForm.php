<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateForm extends Command
    {
        protected static $defaultName = 'make:form';

        protected function configure(): void
        {
            $this->setDescription('Create a new form')
            ->addArgument('form', InputArgument::REQUIRED, 'The form name.')
            ->addArgument('directory', InputArgument::OPTIONAL, 'The form directory');
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

            $form = strval($input->getArgument('form'));
            $directory = ucfirst(strval($input->getArgument('directory')));

            if (preg_match("#^[a-z]([a-z_]+)$#", $form) !== 1) {
                $io->error('You must use snake case syntax to generate the form');
                return 1;
            }

            if (def($directory) && !is_dir(base('app', 'Forms', $directory))) {
                mkdir(base('app', 'Forms', $directory));
            }


            $form_class = collect(explode('_', $form))->for('ucfirst')->join('');

            $form_file = def($directory)
            ? base('app', 'Forms', $directory, "$form_class.php")
            : base('app', 'Forms', "$form_class.php");

            if (file_exists($form_file)) {
                $io->error(sprintf('The %s form already exist', $form_class));

                return 1;
            }
            $io->title('Generation of the form');

            $namespace = def($directory) ? "App\Forms\\$directory" : 'App\Forms';


            if (
                (new File($form_file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace {

    use Eywa\Html\Form\Form;
    use Eywa\Http\Parameter\Bag;
    use Eywa\Http\Response\Response;

    class $form_class extends Form
    {

        /**
         * @inheritDoc
         */
        public static string \$redirect_success_url = '/';

        /**
         * @inheritDoc
         */
        public static string \$redirect_error_url = '/error';
        
        /**
         * @inheritDoc
         */
        protected static string \$method = 'POST';

        /**
         * @inheritDoc
         */
        protected static string \$route = 'send';

        /**
         * @inheritDoc
         */
        protected static array \$route_args = [];

        /**
         * @inheritDoc
         */
        protected static array \$options = [];
        
        /**
         * @inheritDoc
         */
        protected static array \$rules = [];
       
        /**
         * @inheritDoc
         */
        public function make(): string
        {
            return '';
        }

        /**
         * @inheritDoc
         */
        public function success(Bag \$bag): Response
        {
            // TODO: Implement success() method.
        }
    }
}\n")->flush()
            ) {
                $io->success(sprintf('The %s form has been generated successfully', $form_class));

                return 0;
            }
            $io->error(sprintf('The %s form generation has failed', $form_class));
            return 1;
        }
    }
}
