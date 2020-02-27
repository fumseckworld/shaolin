<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateCrud extends Command
    {

        protected static $defaultName = 'make:crud';

        protected function configure()
        {

            $this

                ->setDescription('Create a new crud')
                ->addArgument('controller', InputArgument::REQUIRED, 'The controller name')
                ->addArgument('model', InputArgument::REQUIRED, 'The model name')
                ->addArgument('table', InputArgument::REQUIRED, 'The table name');
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


            $controller = $input->getArgument('controller');
            $model = $input->getArgument('model');
            $table = $input->getArgument('table');

            if(preg_match("#^[a-z]([a-z_]+)$#",$controller) !== 1)
            {
                $io->error('You must use snake case syntax to generate the controller');
                return  1;
            }

            if(preg_match("#^[a-z]([a-z_]+)$#",$model) !== 1)
            {
                $io->error('You must use snake case syntax to generate the model');
                return  1;
            }

            $model_class = collect(explode('_',$model))->for('ucfirst')->join('');

            $controller_class = collect(explode('_',$controller))->for('ucfirst')->join('');


            $model_file  =  base('app','Models',"$model_class.php");
            $controller_file  =  base('app','Controllers',"$controller_class.php");


            if (file_exists($controller_file))
            {
                $io->error('The controller already exist');
                return 1;
            }

            if (file_exists($model_file))
            {
                $io->error('The model already exist');
                return 1;
            }

            $io->title('Generation of the model');


            $namespace = 'App\Models';

            if ((new File($model_file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace\n{ \n\n\tuse Eywa\Database\Model\Model;\n\n\tClass $model_class extends Model\n\t{\n\n\t\tprotected static string \$table = '$table';\n\n\t\tprotected static string \$by = 'id';\n\n\t\tprotected static int \$limit = 20;\n\n\t}\n\n}\n")->flush()) {
                $io->success("The $model model has been generated successfully");

            }else{
                $io->error('Generation of the model has failed');
                return 1;
            }


            $io->title('Generation of the controller');

            $namespace = 'App' . '\\' . 'Controllers';


            if (file_exists($controller_file))
            {
                $io->error("The $controller_class controller already exist");

                return 1;
            }

            if ((new File("$controller_file", EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace { 

	use Eywa\Http\Controller\Controller;
    use Eywa\Http\Response\Response;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Request\Request;
    use Eywa\Html\Form\Form;
    
	Class $controller_class extends Controller
	{

        protected static string \$layout = 'admin';
    
        protected static string \$directory = 'Crud';
		
       /**
        * 
        * @param Request \$request
        * 
        * @return Response
        * 
        * @throws DependencyException
        * @throws Kedavra
        * @throws NotFoundException
        * 
        */
		public function show(Request \$request): Response
		{
		    \$records =  $model_class::paginate([\$this,'display-$table'],\$request->args()->get('page'));
             return \$this->view('show', 'show', 'show', compact('records'));
		} 
		
       /**
        * 
        * @param Request \$request
        * 
        * @return Response
        * 
        * @throws DependencyException
        * @throws Kedavra
        * @throws NotFoundException
        * 
        */
		public function edit(Request \$request): Response
		{
		    \$id = \$request->args()->get('id');
		    \$form = \$this->form(route(edit-$table,compact('id')))->edit(\$id);
            return \$this->view('edit', 'edit', 'edit', compact('form'));
		} 
		
       /**
        * 
        * @param Request \$request
        * 
        * @return Response
        * 
        * @throws DependencyException
        * @throws Kedavra
        * @throws NotFoundException
        * 
        */
		public function update(Request \$request): Response
		{
		   if($model_class::update(intval(\$request->request()->get('id')),\$request->request()->all())
		        \$this->flash(SUCCESS,'The record has been updated successfully');
            else
              \$this->flash(FAILURE,'The record has not been udated',false);
           
            return \$this->back(); 
		}
		
		public function destroy(Request \$request): Response
		{
		   \$id = intval(\$request->args()->get('id'));
		   
		    if($model_class::destroy(\$id))
		        \$this->flash(SUCCESS,'The record has been deleted successfully');
            else
              \$this->flash(FAILURE,'The record has not been deleted',false);
           
            return \$this->back(); 
		}

	}

}
")->flush()) {
                $io->success("The $controller_class controller has been generated successfully");
            }else{
                $io->error("The $controller_class controller has not been generated");

                return 1;
            }

        }



    }
}