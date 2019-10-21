<?php
	
	namespace Imperium\Action
	{

        use DI\DependencyException;
        use DI\NotFoundException;
        use Imperium\Controller\Controller;
        use Imperium\Exception\Kedavra;
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
             * The table name
             *
             * @var string
             *
             */
            protected static $table = '';

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
             * @param array $values
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws NotFoundException
             * @throws Kedavra
             *
             */
            public function create(array $values): RedirectResponse
            {

                $this->init();

                $table = static::$table;

                $sql = $this->sql($table);

                $columns = $sql->columns();

                $x = collect($columns)->join();

                $values = collect($values)->for('htmlentities')->all();

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
             * @param callable $callable
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
            public function read($callable,int $current_page): Response
            {
                $all = $this->sql(static::$table)->paginate($callable,$current_page,static::$limit);

                return  $this->view('@crud/read',compact('all'));
            }


            /**
             *
             * Update a record
             *
             * @param int $id
             * @param array $values
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public function update(int $id,array $values):RedirectResponse
            {

                $this->init();

                $table = static::$table;

                $sql = $this->sql($table);

                $primary = $sql->key();

                $columns = collect();

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
             * @param int $id
             *
             * @return RedirectResponse
             *
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             *
             */
            public function delete(int $id): RedirectResponse
            {
                $this->init();

                return $this->sql(static::$table)->destroy($id) ? $this->back($this->deleted) : $this->back($this->no_deleted,false);
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

        }
	}