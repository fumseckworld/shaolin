<?php
	
	namespace Imperium\Action
	{

        use DI\DependencyException;
        use DI\NotFoundException;
        use Imperium\Controller\Controller;
        use Imperium\Exception\Kedavra;
        use Imperium\Html\Form\Form;
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
             * @var string
             */
            private $current;
            /**
             *
             * The failure truncate message
             *
             * @var string
             *
             */
            private $no_truncated;

            /**
             *
             * The truncated table success message
             *
             * @var string
             *
             */
            private $truncated;


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
             * 
             * @return Response
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             */
            public function show(string $table): Response
            {
                $this->current = $table;

                $all = '<div class="table-responsive"><table class="table table-bordered"><thead>';
                foreach ($this->sql($table)->columns() as $column)
                    append($all,"<th>$column</th>");

                append($all,'</thead><tbody>');
                append($all,$this->sql($table)->paginate([$this,'display'],$this->get('page',1),static::$limit));
                append($all,'</tbody></table></div>');

                return  $this->view('@crud/show',compact('all','table'));
            }

            /**
             *
             * Truncate a table
             *
             * @param string $table
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
            public function clear(string $table): RedirectResponse
            {
                $this->init();

                return  $this->truncate($table) ? $this->to(route('admin','show',[$table]),$this->truncated) : $this->to(route('admin','show',[$table]),$this->no_truncated,false);
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
                $form =  $this->form()->start('admin','update',[$table,$id])->generate($table,$this->config('crud','update_text'),'',Form::EDIT,$id);
                return  $this->view('@crud/edit',compact('form','table'));
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
                $form = $this->form()->start('admin','create',[$table])->generate($table,$this->config('crud','create_text'));

                return $this->view('@crud/create',compact('form','table'));
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

                $values = collect($this->request()->request->all())->del(CSRF_TOKEN,'method','__table__')->for('htmlentities')->all();

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
             * @return Response
             * @throws DependencyException
             * @throws Kedavra
             * @throws LoaderError
             * @throws NotFoundException
             * @throws RuntimeError
             * @throws SyntaxError
             */
            public function home():Response
            {
                $x = collect(['/' => $this->config('crud','select_table_text')]);
                foreach ($this->tables() as $table)
                    $x->put(route('admin','show',[$table,1]),$table);
                $tables = $x->all();
                $form = $this->form()->redirect('table',$tables)->get();
                return  $this->view('@crud/home',compact('tables','form'));
            }

            /**
             * @param $key
             * @param $value
             * @return string
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
            public function display($key,$value): string
            {
                $html =  '<tr>';
                foreach ($value as $v)
                    append($html,'<td>'.substr($v,0,50).'</td>');

                $confirm = $this->config('crud','sure');
                $sure = "onclick=\"return confirm('$confirm');\"";

                append($html,'<td><a href="'.route('admin','edit',[$this->current,$value->id]).'" class="'.$this->config('crud','edit_class').'">'.$this->config('crud','edit_text').'</a></td>');
                append($html,'<td><a href="'.route('admin','remove',[$this->current,$value->id]).'" class="'.$this->config('crud','remove_class').'" '.$sure.'>  '.$this->config('crud','remove_text').'</a></td>');
                return $html . '</tr>';
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

                $this->no_truncated = config($file,'no_truncated');

                $this->truncated = config($file,'truncated');

            }



        }
	}