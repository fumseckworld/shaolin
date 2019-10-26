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
            return Todo::destroy($id) ? $this->back('removed') : $this->back('not removed',false);
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
            return Todo::update($this->request()->request->get('id'),$this->collect($this->request()->request->all())->del(CSRF_TOKEN,'method')->all()) ? $this->back('updated') : $this->back('not updated',false);
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
            $todo = collect($this->request()->request->all())->del(CSRF_TOKEN,'method')->all();
            return Todo::create($todo) ? $this->back('created') : $this->back('fail',false);
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
            $form = $this->form()->start('task','add')
                ->row()
                ->input(Form::TEXT,'task','task')
                ->end_row_and_new()
                ->textarea('description','description')
                ->end_row_and_new()
                ->input(Form::DATE,'due','The due date')
                ->end_row_and_new()
                ->select(false,'priority',['none','low','medium','high'])
                ->end_row_and_new()
                ->submit('add')
                ->end_row()
                ->get();
            return $this->view('@todo/home',compact('todo','form'));
        }
    }
}