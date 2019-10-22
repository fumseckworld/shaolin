<?php
	
	namespace Imperium\Action
	{

        use DI\DependencyException;
        use DI\NotFoundException;
        use Imperium\Controller\Controller;
        use Imperium\Exception\Kedavra;
        use Imperium\Html\Form\Form;
        use Imperium\Html\Table\Table;
        use Symfony\Component\HttpFoundation\RedirectResponse;
        use Symfony\Component\HttpFoundation\Response;
        use Twig\Error\LoaderError;
        use Twig\Error\RuntimeError;
        use Twig\Error\SyntaxError;


        /**
		 * Class Crud
		 *
		 * @author  Willy Micieli
		 *
		 * @package Imperium\Crud
		 *
		 * @license GPL
		 *
		 * @version 10
		 *
		 *
		 */
		abstract class Crud extends Controller
		{

            /**
             *
             * The created success message
             *
             * @var string
             *
             */
            private $created;

            /**
             *
             * The no created message
             *
             * @var string
             *
             */
            private $no_created;

            /**
             *
             * The updated success message
             *
             * @var string
             *
             */
            private $updated;

            /**
             *
             * The not update failure message
             *
             * @var string
             *
             */
            private $no_updated;

            /**
             *
             * The deleted successfully message
             *
             * @var string
             *
             */
            private $deleted;

            /**
             *
             * The not deleted message
             *
             * @var string
             *
             */
            private $no_deleted;

            /**
             *
             * The pagination limit
             *
             * @var int
             *
             */
            protected static $limit = 20;


            /**
             *
             * Create a new record
             *
             * @param string $table
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public function add(string $table): RedirectResponse
            {

                $this->init();

                $sql = $this->sql($table);

                $columns = $sql->columns();

                $x = collect($columns)->join();

                $values = collect($this->request()->request->all())->del(CSRF_TOKEN)->for('htmlentities')->all();

                $id = $sql->key();

                $query = "INSERT INTO $table ($x) VALUES (";

                foreach($columns as $column)
                    equal($column, $id) ? $sql->connexion()->postgresql() ? append($query, 'DEFAULT, ') : append($query, 'NULL, ') : append($query, $sql->connexion()->pdo()->quote(collect($values)->get($column)) . ', ');

                $query = trim($query, ', ');

                append($query, ')');

                return  $sql->connexion()->execute($query) ? back($this->created) : back($this->no_created,false);

            }

            /**
             *
             * Read all record with a pagination
             *
             * @param string $table
             * @param int $current_page
             *
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             *
             */
            public function show(string $table,int $current_page): Response
            {
                $all = $this->sql($table)->paginate([$this,'home'],$current_page,static::$limit);

                return  $this->view('@crud/show',compact('all'));
            }

            /**
             *
             * Generate a form to update a record
             *
             * @param string $table
             * @param int $id
             *
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             *
             */
            public function edit(string $table,int $id): Response
            {
                $form =  $this->form()->generate($table,$this->config('crud','update_text'),'',Form::EDIT,$id);
                return  $this->view('@crud/edit',compact('form'));
            }

            /**
             *
             * Create a form to add content
             *
             * @param string $table
             *
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             *
             */
            public function create(string $table): Response
            {
                $form = $this->form()->start('create')->generate($table,$this->config('crud','create_text'));

                return $this->view('@crud/create',compact('form'));
            }

            /**
             *
             * Update a record
             *
             * @param string $table
             * @param int $id
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public function refresh(string $table,int $id): RedirectResponse
            {

                $this->init();

                $sql = $this->sql($table);

                $primary = $sql->key();

                $columns = collect();

                $values = collect($this->request()->request->all())->del(CSRF_TOKEN)->all();

                foreach ($values  as $k => $value)
                {
                    if (different($k,$primary))
                        $columns->push("$k =" .$sql->connexion()->pdo()->quote($value));

                }

                $columns =  $columns->join(', ');

                $query = "UPDATE  $table SET $columns WHERE $primary = $id";

                return  $sql->connexion()->execute($query) ? $this->back($this->updated) : $this->back($this->no_updated,false);

            }

            /**
             *
             * Destroy a record
             *
             * @param string $table
             * @param int $id
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public function destroy(string $table,int $id): RedirectResponse
            {
                $this->init();

                return $this->sql($table)->destroy($id) ? $this->back($this->deleted) : $this->back($this->no_deleted,false);
            }

            /**
             *
             *
             * @param $key
             * @param $value
             *
             * @return string
             */
            public function home($key, $value): string
            {


               $html = '<td>';
               foreach ($this->collect(obj($value))->keys()->all() as $key)
                append($html,"<tr>$key</tr>");

                return $html .'</td>';
            }

            /**
             *
             * @throws Kedavra
             */
            private function init()
            {

                $file = 'crud';

                $this->created = config($file,'created');

                $this->no_created = config($file,'no_created');

                $this->updated = config($file,'updated');

                $this->no_updated = config($file,'no_updated');

                $this->deleted = config($file,'deleted');

                $this->no_deleted = config($file,'no_deleted');
            }

            private function all($table)
            {
                return $this->sql($table)->take(static::$limit)->all();
            }

        }
	}