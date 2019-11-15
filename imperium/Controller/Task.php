<?php


namespace Imperium\Controller {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Action\Todo;
    use Imperium\Exception\Kedavra;
    use Imperium\Html\Form\Form;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Response;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    class Task extends Controller
    {
        /**
         *
         * Close a task
         *
         * @param int $id
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @throws Kedavra
         *
         */
        public function finish(int $id): RedirectResponse
        {
            return Todo::destroy($id) ? $this->back($this->config('todo','toto_closed')) : $this->back($this->config('todo','toto_not_close'),false);
        }

        /**
         *
         * Update a task
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function update(): RedirectResponse
        {
            return Todo::update($this->request()->request->get('id'),$this->collect($this->request()->request->all())->del(CSRF_TOKEN,'_method')->all()) ? $this->back($this->config('todo','todo_updated')) : $this->back($this->config('todo','todo_not_updated'),false);
        }

        /**
         *
         * Create a task
         *
         * @return RedirectResponse
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         *
         */
        public function add(): RedirectResponse
        {
            $todo = collect($this->request()->request->all())->del(CSRF_TOKEN,'_method')->all();
            return Todo::create($todo) ? $this->back($this->config('todo','todo_created')) : $this->back($this->config('todo','todo_not_created'),false);
        }

        /**
         *
         * Display all tasks
         *
         * @return Response
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         *
         */
        public function home(): Response
        {
            $todo = Todo::query()->by('due',self::ASC)->all();
            $form = $this->form(POST,'task','add')
                ->row()
                ->add('task','text',['placeholder'=> $this->config('todo','task_name'),'required' => 'required'])
                ->end()
                ->row()
                ->add('description','textarea',['required'=> 'required','rows'=> 10,'placeholder'=> $this->config('todo','task_description')])
                ->end()
                ->row()
                ->add('due','date',['required'=>'required'])
                ->end()
                ->row()
                ->select('priority',['none','low','medium','high'])
                ->end()
                ->get(fa('fa','fa-plus'));


            return $this->view('@todo/home',compact('todo','form'));
        }
    }
}